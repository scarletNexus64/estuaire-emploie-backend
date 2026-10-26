<?php
namespace Tests\Feature;

use App\Models\{CommunityFiliere, CommunitySpecialty, CommunityDiscussion, CommunityReply, CommunityMentorApplication, CommunityAssistanceRequest, CommunityAssistanceMessage, Notification, User};
use App\Jobs\SendCommunityNotification;
use App\Services\FirebaseNotificationService;
use Database\Seeders\CommunitySpecialtySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\{Queue, Storage};
use Tests\TestCase;

class CommunityIntegrationTest extends TestCase
{
    use RefreshDatabase;
    protected function setUp(): void
    {
        parent::setUp();
        Queue::fake();
        $this->seed(CommunitySpecialtySeeder::class);
    }
    private function specialty(): CommunitySpecialty { return CommunitySpecialty::firstOrFail(); }
    private function mentor(?CommunitySpecialty $specialty = null): User
    {
        $user = User::factory()->create();
        $app = CommunityMentorApplication::create(['user_id'=>$user->id, 'bio'=>'Une présentation complète du mentor.', 'experience'=>'Une expérience professionnelle confirmée.', 'availability'=>'Le soir', 'status'=>'approved']);
        $app->specialties()->attach(($specialty ?? $this->specialty())->id);
        return $user;
    }
    private function assistance(User $owner, ?User $mentor = null): CommunityAssistanceRequest
    {
        return CommunityAssistanceRequest::create(['requester_id'=>$owner->id, 'mentor_id'=>$mentor?->id, 'community_specialty_id'=>$this->specialty()->id, 'title'=>'Besoin de comprendre cet exercice', 'description'=>'Je souhaite une aide pour résoudre cet exercice.']);
    }

    public function test_catalog_lists_all_specialties_and_hides_inactive_entries(): void
    {
        $this->getJson('/api/community/catalog')->assertOk()->assertJsonCount(8, 'data');
        $catalog = $this->getJson('/api/community/catalog')->json('data');
        $this->assertSame(47, collect($catalog)->sum(fn ($f)=>count($f['specialties'])));
        $this->specialty()->update(['is_active'=>false]);
        $this->getJson('/api/community/specialties/'.$this->specialty()->id.'/discussions')->assertNotFound();
        $catalog = $this->getJson('/api/community/catalog')->json('data');
        $this->assertSame(46, collect($catalog)->sum(fn ($f)=>count($f['specialties'])));
    }

    public function test_discussions_replies_resources_resolution_and_privacy(): void
    {
        $s = $this->specialty(); $owner = User::factory()->create(); $other = User::factory()->create();
        $url = '/api/community/specialties/'.$s->id.'/discussions';
        $payload=['title'=>'Comment préparer mon exercice ?', 'content'=>'Je cherche une méthode pour préparer mon exercice.', 'kind'=>'discussion'];
        $this->postJson($url,$payload)->assertUnauthorized();
        $id=$this->actingAs($owner,'sanctum')->postJson($url,$payload)->assertCreated()->json('data.id');
        $this->getJson($url)->assertJsonCount(1,'data.data')->assertJsonMissingPath('data.data.0.user.email');
        $otherSpecialty=CommunitySpecialty::where('id','!=',$s->id)->firstOrFail();
        $this->getJson('/api/community/specialties/'.$otherSpecialty->id.'/discussions')->assertJsonCount(0,'data.data');
        $this->postJson($url,[...$payload,'kind'=>'resource','resource_url'=>'javascript:alert(1)'])->assertUnprocessable();
        $this->postJson($url,[...$payload,'kind'=>'resource','resource_url'=>'https://example.com/cours.pdf'])->assertCreated();
        $this->getJson($url.'?kind=resource')->assertJsonCount(1,'data.data');
        $reply=$this->actingAs($other,'sanctum')->postJson("/api/community/discussions/$id/replies",['content'=>'Voici une méthode pour vous aider.'])->assertCreated()->json('data.id');
        $this->assertDatabaseHas('notifications',['notifiable_id'=>$owner->id,'type'=>'community_reply']);
        Queue::assertPushed(SendCommunityNotification::class);
        $this->patchJson("/api/community/discussions/$id/resolution",['is_resolved'=>true,'reply_id'=>$reply])->assertForbidden();
        $this->actingAs($owner,'sanctum')->patchJson("/api/community/discussions/$id/resolution",['is_resolved'=>true,'reply_id'=>$reply])->assertOk()->assertJsonPath('data.is_resolved',true);
        $this->getJson("/api/community/discussions/$id/replies")->assertJsonCount(1,'data.data');
        $this->actingAs($other,'sanctum')->deleteJson("/api/community/replies/$reply")->assertOk();
        $this->assertFalse(CommunityDiscussion::findOrFail($id)->is_resolved);
        $this->deleteJson("/api/community/discussions/$id")->assertForbidden();
        $this->actingAs($owner,'sanctum')->deleteJson("/api/community/discussions/$id")->assertOk();
    }

    public function test_mentor_application_is_reviewed_before_becoming_visible(): void
    {
        $s=$this->specialty(); $user=User::factory()->create();
        $payload=['specialty_ids'=>[$s->id],'bio'=>'Je souhaite accompagner les étudiants de ma spécialité.','experience'=>'Je possède cinq années de pratique dans cette spécialité.','availability'=>'Samedi matin'];
        $id=$this->actingAs($user,'sanctum')->postJson('/api/community/mentor-application',$payload)->assertCreated()->json('data.id');
        $this->postJson('/api/community/mentor-application',$payload)->assertUnprocessable();
        $this->getJson('/api/community/specialties/'.$s->id.'/mentors')->assertJsonCount(0,'data.data');
        $admin=User::factory()->create(['role'=>'admin','is_super_admin'=>true]);
        $this->actingAs($admin)->get('/admin/community')->assertOk()->assertSee('Candidatures de mentors');
        $this->patch('/admin/community/mentors/'.$id,['status'=>'approved'])->assertRedirect()->assertSessionHasNoErrors();
        $this->assertDatabaseHas('notifications',['type'=>'community_mentor_review','notifiable_id'=>$user->id]);
        $this->actingAs($user,'sanctum')->getJson('/api/community/specialties/'.$s->id.'/mentors')->assertJsonCount(1,'data.data')->assertJsonMissingPath('data.data.0.proof_url')->assertJsonMissingPath('data.data.0.review_message');
        $this->patchJson('/api/community/mentor-application/availability',['is_available'=>false])->assertOk();
        $this->getJson('/api/community/specialties/'.$s->id.'/mentors')->assertJsonCount(0,'data.data');
        $this->getJson('/api/community/account')->assertJsonPath('data.mentor_application.status','approved');
        $this->actingAs($admin)->patch('/admin/community/mentors/'.$id,['status'=>'rejected'])->assertSessionHasErrors('review_message');
        $this->patch('/admin/community/mentors/'.$id,['status'=>'rejected','review_message'=>'Expérience à préciser.'])->assertSessionHasNoErrors();
        $this->actingAs($user,'sanctum')->postJson('/api/community/mentor-application',$payload)->assertCreated()->assertJsonPath('data.status','pending');
    }

    public function test_assistance_private_conversation_files_and_lifecycle(): void
    {
        Storage::fake('local');
        $owner=User::factory()->create();$mentor=$this->mentor();$stranger=User::factory()->create();
        $created=$this->actingAs($owner,'sanctum')->postJson('/api/community/assistance',[
            'specialty_id'=>$this->specialty()->id,'mentor_id'=>$mentor->id,'title'=>'Besoin de revoir cet exercice','description'=>'Merci de m’aider à comprendre comment résoudre cet exercice.',
        ])->assertCreated();
        $id=$created->json('data.id');$url="/api/community/assistance/$id";
        $this->postJson($url.'/messages',['content'=>'Message avant acceptation'])->assertStatus(409);
        $this->actingAs($stranger,'sanctum')->getJson($url)->assertNotFound();
        $this->postJson($url.'/transition',['action'=>'accept'])->assertStatus(409);
        $this->actingAs($mentor,'sanctum')->getJson('/api/community/assistance?scope=mentor')->assertJsonCount(1,'data.data');
        $this->postJson($url.'/transition',['action'=>'accept'])->assertOk()->assertJsonPath('data.status','accepted');
        $this->postJson($url.'/transition',['action'=>'start'])->assertOk()->assertJsonPath('data.status','in_progress');
        $response=$this->actingAs($owner,'sanctum')->post($url.'/messages',[
            'content'=>'Voici mon document.', 'attachment'=>UploadedFile::fake()->create('exercice.pdf',20,'application/pdf'),
        ],['Accept'=>'application/json'])->assertCreated()->assertJsonMissingPath('data.attachment_path');
        $messageId=$response->json('data.id');$fileUrl=$url.'/messages/'.$messageId.'/attachment';
        $this->get($fileUrl)->assertOk();
        $this->actingAs($stranger,'sanctum')->getJson($fileUrl)->assertNotFound();
        $this->actingAs($mentor,'sanctum')->get($fileUrl)->assertOk();
        $this->getJson($url)->assertJsonPath('data.messages.data.0.content','Voici mon document.');
        $this->postJson($url.'/transition',['action'=>'complete'])->assertOk()->assertJsonPath('data.status','completed');
        $this->postJson($url.'/messages',['content'=>'Message après clôture'])->assertStatus(409);
        $this->assertDatabaseHas('notifications',['type'=>'community_assistance','notifiable_id'=>$owner->id]);
    }

    public function test_pool_can_be_claimed_once_and_requests_are_limited(): void
    {
        $owner=User::factory()->create();$mentor=$this->mentor();$second=$this->mentor();
        $assistance=$this->assistance($owner);
        $url='/api/community/assistance/'.$assistance->id;
        $this->actingAs($mentor,'sanctum')->postJson($url.'/transition',['action'=>'accept'])->assertOk();
        $this->actingAs($second,'sanctum')->postJson($url.'/transition',['action'=>'accept'])->assertStatus(409);
        $this->getJson($url)->assertNotFound();
        $this->assistance($owner);$this->assistance($owner);
        $payload=['specialty_id'=>$this->specialty()->id,'title'=>'Une nouvelle question','description'=>'Une description complète du problème à résoudre.'];
        $this->actingAs($owner,'sanctum')->postJson('/api/community/assistance',$payload)->assertUnprocessable();
        $this->postJson($url.'/transition',['action'=>'cancel'])->assertOk();
        $this->postJson('/api/community/assistance',$payload)->assertCreated();
    }

    public function test_wrong_specialty_mentor_and_moderation_permissions_are_rejected(): void
    {
        $other=CommunitySpecialty::where('id','!=',$this->specialty()->id)->firstOrFail();
        $mentor=$this->mentor($other);$owner=User::factory()->create();
        $a=$this->assistance($owner);
        $this->actingAs($mentor,'sanctum')->getJson('/api/community/assistance/'.$a->id)->assertNotFound();
        $this->postJson('/api/community/assistance/'.$a->id.'/transition',['action'=>'accept'])->assertStatus(409);
        $this->actingAs($owner,'sanctum')->postJson('/api/community/assistance',[
            'specialty_id'=>$this->specialty()->id,'mentor_id'=>$mentor->id,'title'=>'Question de spécialité','description'=>'Je souhaite des explications sur cette spécialité.',
        ])->assertUnprocessable();
        $admin=User::factory()->create(['role'=>'admin','is_super_admin'=>false,'permissions'=>[]]);
        $this->actingAs($admin)->get('/admin/community')->assertForbidden();
    }

    public function test_latest_messages_and_replies_are_visible_and_solution_survives_pagination(): void
    {
        $owner = User::factory()->create();
        $mentor = $this->mentor();
        $discussion = CommunityDiscussion::create(['user_id'=>$owner->id, 'community_specialty_id'=>$this->specialty()->id, 'title'=>'Question avec beaucoup de réponses', 'content'=>'Une question détaillée.', 'kind'=>'discussion']);
        for ($i=1; $i<=25; $i++) $discussion->replies()->create(['user_id'=>$mentor->id, 'content'=>"Réponse $i"]);
        $first = $discussion->replies()->oldest('id')->firstOrFail();
        $discussion->update(['accepted_reply_id'=>$first->id, 'is_resolved'=>true]);
        $this->getJson('/api/community/discussions/'.$discussion->id.'/replies')->assertJsonPath('data.data.0.content','Réponse 25');
        $this->getJson('/api/community/discussions/'.$discussion->id)->assertJsonPath('data.accepted_reply.content','Réponse 1');
        $this->actingAs($owner,'sanctum')->deleteJson('/api/community/discussions/'.$discussion->id)->assertOk();
        $this->assertDatabaseMissing('community_replies',['id'=>$first->id]);
        $a = $this->assistance($owner,$mentor);
        $a->update(['status'=>'accepted']);
        for ($i=1; $i<=35; $i++) $a->messages()->create(['user_id'=>$owner->id,'content'=>"Message $i"]);
        $this->getJson('/api/community/assistance/'.$a->id)->assertJsonPath('data.messages.data.0.content','Message 35');
        $this->getJson('/api/community/assistance/'.$a->id.'?page=2')->assertJsonPath('data.messages.data.0.content','Message 5');
    }

    public function test_push_carries_the_correct_private_destination(): void
    {
        $user=User::factory()->create(['fcm_token'=>'test-device']);
        $notification=Notification::create(['type'=>'community_assistance','notifiable_type'=>User::class,'notifiable_id'=>$user->id,'data'=>['title'=>'Assistance','message'=>'Votre demande avance.','assistance_id'=>42]]);
        $firebase=$this->mock(FirebaseNotificationService::class);
        $firebase->shouldReceive('sendToToken')->once()->with('test-device','Assistance','Votre demande avance.',[
            'type'=>'community_assistance','discussion_id'=>'','assistance_id'=>'42','notification_id'=>$notification->id,
        ]);
        (new SendCommunityNotification($notification->id))->handle($firebase);
        $this->assertDatabaseCount('notifications',1);
    }
}

<?php

namespace Tests\Feature;

use App\Models\InsamtechsUsbOrder;
use App\Models\Notification;
use App\Jobs\SendUsbOrderNotification;
use App\Services\FirebaseNotificationService;
use Illuminate\Support\Facades\Queue;
use App\Models\Setting;
use App\Models\User;
use App\Services\InsamtechsCatalogService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InsamtechsUsbOrderTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Queue::fake();
        $this->mock(InsamtechsCatalogService::class)
            ->shouldReceive('catalog')
            ->andReturn([
                ['id' => 10, 'intitule' => ['fr' => 'Montage vidéo'], 'etat' => 1],
                ['id' => 20, 'intitule' => ['fr' => 'Programmation'], 'etat' => 1],
            ]);
    }

    public function test_order_uses_server_prices_and_keeps_requested_formations(): void
    {
        Setting::setMany([
            'insamtechs_usb_unit_price_xaf' => 5000,
            'insamtechs_usb_delivery_price_xaf' => 2000,
        ]);
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum')->postJson('/api/insamtechs-usb/orders', [
            'formation_ids' => [10, 20],
            'customer_name' => 'Alice Test',
            'phone' => '690000000',
            'city' => 'Douala',
            'address' => 'Bonapriso',
            'total_price_xaf' => 1,
        ])->assertCreated()->assertJsonPath('data.total_price_xaf', 12000)
            ->assertJsonPath('data.status', 'pending');

        $order = InsamtechsUsbOrder::firstOrFail();
        $this->assertSame([10, 20], array_column($order->formations, 'id'));
        $this->assertSame($user->id, $order->user_id);
    }

    public function test_order_requires_auth_valid_ids_and_configured_price(): void
    {
        $payload = [
            'formation_ids' => [10], 'customer_name' => 'Alice Test',
            'phone' => '690000000', 'city' => 'Douala', 'address' => 'Bonapriso',
        ];
        $this->postJson('/api/insamtechs-usb/orders', $payload)->assertUnauthorized();

        $this->actingAs(User::factory()->create(), 'sanctum');
        $this->postJson('/api/insamtechs-usb/orders', $payload)->assertUnprocessable();

        Setting::set('insamtechs_usb_unit_price_xaf', 5000);
        $this->postJson('/api/insamtechs-usb/orders', array_merge($payload, ['formation_ids' => [999]]))
            ->assertUnprocessable();
        $this->postJson('/api/insamtechs-usb/orders', array_merge($payload, ['formation_ids' => [10, 10]]))
            ->assertUnprocessable();
        $this->assertSame(0, InsamtechsUsbOrder::count());
    }

    public function test_super_admin_sees_order_and_customer_account(): void
    {
        $customer = User::factory()->create([
            'name' => 'Client USB',
            'email' => 'client-usb@example.test',
        ]);
        $admin = User::factory()->create(['role' => 'admin', 'is_super_admin' => true]);
        InsamtechsUsbOrder::create([
            'user_id' => $customer->id,
            'formations' => [['id' => 10, 'title' => 'Montage vidéo']],
            'customer_name' => 'Client USB',
            'phone' => '690000000',
            'city' => 'Douala',
            'address' => 'Bonapriso',
            'unit_price_xaf' => 5000,
            'delivery_price_xaf' => 2000,
            'total_price_xaf' => 7000,
            'status' => 'pending',
        ]);

        $this->actingAs($admin)
            ->get('/admin/dashboard')
            ->assertOk()
            ->assertSee('Commandes de formations sur clé USB')
            ->assertSee('client-usb@example.test');
        $this->get('/admin/insamtechs-usb')
            ->assertOk()
            ->assertSee('client-usb@example.test')
            ->assertSee('Voir tout le compte')
            ->assertSee('Bonapriso');
    }
    public function test_complete_customer_admin_reply_and_notification_flow(): void
    {
        Setting::set('insamtechs_usb_unit_price_xaf', 5000);
        $customer = User::factory()->create();
        $other = User::factory()->create();
        $admin = User::factory()->create(['role' => 'admin', 'is_super_admin' => true]);
        $created = $this->actingAs($customer, 'sanctum')->postJson('/api/insamtechs-usb/orders', [
            'formation_ids' => [10], 'customer_name' => 'Alice',
            'phone' => '690000000', 'city' => 'Douala', 'address' => 'Bonapriso',
        ])->assertCreated();
        $id = $created->json('data.id');
        $this->assertDatabaseHas('notifications', ['type' => 'usb_order_created', 'notifiable_id' => $customer->id]);

        $this->actingAs($admin)->patch("/admin/insamtechs-usb/orders/{$id}/status", [
            'status' => 'confirmed', 'admin_reply' => 'Votre clé sera disponible vendredi.',
        ])->assertRedirect()->assertSessionHasNoErrors();
        $this->assertDatabaseHas('insamtechs_usb_orders', [
            'id' => $id, 'status' => 'confirmed', 'replied_by' => $admin->id,
            'admin_reply' => 'Votre clé sera disponible vendredi.',
        ]);
        $this->assertNotNull(InsamtechsUsbOrder::findOrFail($id)->replied_at);
        $this->assertSame(1, Notification::where('type', 'usb_order_reply')->count());
        Queue::assertPushed(SendUsbOrderNotification::class, 2);
        $this->get('/admin/insamtechs-usb')->assertOk()->assertSee('Réponse envoyée au client');

        $this->actingAs($customer, 'sanctum')->getJson("/api/insamtechs-usb/orders/{$id}")
            ->assertOk()->assertJsonPath('data.admin_reply', 'Votre clé sera disponible vendredi.')
            ->assertJsonPath('data.status', 'confirmed')->assertJsonMissingPath('data.replied_by');
        $this->getJson('/api/notifications?type=usb_order_reply')->assertOk()
            ->assertJsonPath('data.0.data.order_id', $id)
            ->assertJsonPath('data.0.data.message', 'Votre clé sera disponible vendredi.');
        $this->getJson('/api/insamtechs-usb/orders')->assertOk()->assertJsonCount(1, 'data.data');

        $this->actingAs($other, 'sanctum')->getJson("/api/insamtechs-usb/orders/{$id}")->assertNotFound();
        $this->getJson('/api/insamtechs-usb/orders')->assertOk()->assertJsonCount(0, 'data.data');
        $this->getJson('/api/notifications?type=usb_order_reply')->assertOk()->assertJsonCount(0, 'data');
    }

    public function test_one_reply_only_and_no_duplicate_notification_for_unchanged_status(): void
    {
        $order = $this->makeOrder();
        $admin = User::factory()->create(['role' => 'admin', 'is_super_admin' => true]);
        $url = "/admin/insamtechs-usb/orders/{$order->id}/status";
        $this->actingAs($admin)->patch($url, ['status' => 'pending'])->assertRedirect();
        $this->assertSame(0, Notification::count());
        $this->patch($url, ['status' => 'confirmed', 'admin_reply' => 'Première réponse'])->assertSessionHasNoErrors();
        $this->patch($url, ['status' => 'shipped', 'admin_reply' => 'Autre réponse'])->assertSessionHasErrors('admin_reply');
        $this->assertSame('Première réponse', $order->fresh()->admin_reply);
        $this->assertSame('confirmed', $order->fresh()->status);
        $this->assertSame(1, Notification::count());
        $this->patch($url, ['status' => 'shipped'])->assertSessionHasNoErrors();
        $this->patch($url, ['status' => 'shipped'])->assertSessionHasNoErrors();
        $this->assertSame(2, Notification::count());
        $this->assertDatabaseHas('notifications', ['type' => 'usb_order_status_changed']);
    }

    public function test_reply_validation_and_permissions(): void
    {
        $order = $this->makeOrder();
        $url = "/admin/insamtechs-usb/orders/{$order->id}/status";
        $admin = User::factory()->create(['role' => 'admin', 'is_super_admin' => false, 'permissions' => []]);
        $this->actingAs($admin)->patch($url, ['status' => 'confirmed'])->assertForbidden();
        $this->get('/admin/insamtechs-usb')->assertForbidden();
        $admin->update(['permissions' => ['manage_premium_services']]);
        $this->get('/admin/dashboard')->assertOk()->assertSee('Commandes de formations sur clé USB');
        $this->get('/admin/insamtechs-usb')->assertOk()->assertSee('Bonapriso');
        $this->patch($url, ['status' => 'confirmed', 'admin_reply' => str_repeat('a', 2001)])
            ->assertSessionHasErrors('admin_reply');
        $this->patch($url, ['status' => 'invalid'])->assertSessionHasErrors('status');
        $this->assertSame('pending', $order->fresh()->status);
        $this->assertSame(0, Notification::count());
    }

    public function test_push_payload_opens_order_and_retries_do_not_duplicate_database_notifications(): void
    {
        $order = $this->makeOrder();
        $order->user->update(['fcm_token' => 'test-device']);
        $notification = Notification::create([
            'type' => 'usb_order_reply', 'notifiable_type' => User::class, 'notifiable_id' => $order->user_id,
            'data' => ['title' => 'Réponse USB', 'message' => 'Votre commande est prête.', 'order_id' => $order->id, 'status' => 'confirmed'],
        ]);
        $firebase = $this->mock(FirebaseNotificationService::class);
        $firebase->shouldReceive('sendToToken')->twice()->with('test-device', 'Réponse USB', 'Votre commande est prête.', [
            'type' => 'usb_order_reply', 'order_id' => (string) $order->id,
            'status' => 'confirmed', 'notification_id' => $notification->id,
        ]);
        $job = new SendUsbOrderNotification($notification->id);
        $this->assertTrue($job->afterCommit);
        $this->assertSame('default', $job->queue);
        $job->handle($firebase);
        $job->handle($firebase);
        $this->assertSame(1, Notification::count());
    }

    public function test_detail_requires_authentication(): void
    {
        $order = $this->makeOrder();
        $this->getJson("/api/insamtechs-usb/orders/{$order->id}")->assertUnauthorized();
    }

    private function makeOrder(): InsamtechsUsbOrder
    {
        return InsamtechsUsbOrder::create([
            'user_id' => User::factory()->create()->id,
            'formations' => [['id' => 10, 'title' => 'Montage vidéo']],
            'customer_name' => 'Client USB', 'phone' => '690000000',
            'city' => 'Douala', 'address' => 'Bonapriso',
            'unit_price_xaf' => 5000, 'delivery_price_xaf' => 2000,
            'total_price_xaf' => 7000, 'status' => 'pending',
        ]);
    }

}

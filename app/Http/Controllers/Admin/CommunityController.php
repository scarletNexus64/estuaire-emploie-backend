<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{CommunityMentorApplication, CommunityDiscussion, CommunityAssistanceRequest};
use App\Services\CommunityNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommunityController extends Controller
{
    public function index()
    {
        return view('admin.community.index', [
            'applications' => CommunityMentorApplication::with('user', 'specialties')->orderByRaw("status = 'pending' desc")->latest('id')->paginate(15, ['*'], 'applications_page'),
            'discussions' => CommunityDiscussion::with('user', 'specialty')->latest('id')->paginate(15, ['*'], 'discussions_page'),
            'requests' => CommunityAssistanceRequest::with('requester', 'mentor', 'specialty')->latest('id')->paginate(15, ['*'], 'requests_page'),
        ]);
    }

    public function review(Request $request, CommunityMentorApplication $application, CommunityNotificationService $notifications)
    {
        $data = $request->validate(['status' => 'required|in:approved,rejected', 'review_message' => 'nullable|required_if:status,rejected|string|max:2000']);
        DB::transaction(function () use ($request, $application, $data, $notifications) {
            $locked = CommunityMentorApplication::lockForUpdate()->findOrFail($application->id);
            if ($locked->status === $data['status']) return;
            $locked->update([...$data, 'reviewed_by' => $request->user()->id, 'reviewed_at' => now()]);
            $notifications->send($locked->user_id, 'community_mentor_review', 'Votre candidature de mentor',
                $data['status'] === 'approved' ? 'Votre candidature est validée. Vous pouvez maintenant accompagner les membres de vos spécialités.' : 'Votre candidature n’a pas été retenue. Consultez le motif dans votre espace mentor.');
        });
        return back()->with('success', 'Candidature mise à jour et notification enregistrée.');
    }

    public function destroy(CommunityDiscussion $discussion)
    {
        $discussion->delete();
        return back()->with('success', 'Publication retirée de la communauté.');
    }
}

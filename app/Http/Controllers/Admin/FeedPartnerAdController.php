<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FeedPartnerAd;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

/**
 * Cartes sponsorisées des applications partenaires dans le fil candidat.
 * Le cache public (/api/feed/partner-ads) est invalidé par le modèle.
 */
class FeedPartnerAdController extends Controller
{
    public function index()
    {
        $ads = FeedPartnerAd::orderBy('display_order')->orderBy('id')->get();

        return view('admin.feed.partner-ads.index', compact('ads'));
    }

    public function create()
    {
        return view('admin.feed.partner-ads.form', ['ad' => null, 'isEdit' => false]);
    }

    public function store(Request $request)
    {
        $data = $this->handleUploads($request, $this->validated($request), null);
        $data['is_active'] = $request->boolean('is_active');

        FeedPartnerAd::create($data);

        return redirect()->route('admin.feed-partner-ads.index')
            ->with('success', 'Pub partenaire créée avec succès');
    }

    public function edit(FeedPartnerAd $feedPartnerAd)
    {
        return view('admin.feed.partner-ads.form', ['ad' => $feedPartnerAd, 'isEdit' => true]);
    }

    public function update(Request $request, FeedPartnerAd $feedPartnerAd)
    {
        $data = $this->validated($request);

        if ($request->boolean('remove_image') && $feedPartnerAd->image_path) {
            Storage::disk('public')->delete($feedPartnerAd->image_path);
            $data['image_path'] = null;
        }

        $data = $this->handleUploads($request, $data, $feedPartnerAd);
        $data['is_active'] = $request->boolean('is_active');

        $feedPartnerAd->update($data);

        return redirect()->route('admin.feed-partner-ads.index')
            ->with('success', 'Pub partenaire mise à jour avec succès');
    }

    public function destroy(FeedPartnerAd $feedPartnerAd)
    {
        foreach (['logo_path', 'image_path'] as $field) {
            if ($feedPartnerAd->{$field}) {
                Storage::disk('public')->delete($feedPartnerAd->{$field});
            }
        }

        $feedPartnerAd->delete();

        return redirect()->route('admin.feed-partner-ads.index')
            ->with('success', 'Pub partenaire supprimée avec succès');
    }

    public function toggle(FeedPartnerAd $feedPartnerAd)
    {
        $feedPartnerAd->update(['is_active' => ! $feedPartnerAd->is_active]);

        return redirect()->route('admin.feed-partner-ads.index')
            ->with('success', 'Pub partenaire ' . ($feedPartnerAd->is_active ? 'activée' : 'désactivée'));
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'partner' => ['required', Rule::in(array_keys(FeedPartnerAd::PARTNERS))],
            'name' => 'required|string|max:255',
            'headline' => 'required|string|max:255',
            'body' => 'nullable|string|max:1000',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'cta_label' => 'nullable|string|max:60',
            'accent_color' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'play_store_url' => 'nullable|url|max:2048',
            'app_store_url' => 'nullable|url|max:2048',
            'website_url' => 'nullable|url|max:2048',
            'display_order' => 'nullable|integer|min:0',
        ], [
            'accent_color.regex' => 'La couleur doit être au format hexadécimal, ex. #E4572E.',
        ]);
    }

    private function handleUploads(Request $request, array $data, ?FeedPartnerAd $ad): array
    {
        unset($data['logo'], $data['image']);
        $data['display_order'] = $data['display_order'] ?? 0;
        if (! empty($data['accent_color'])) {
            $data['accent_color'] = strtoupper($data['accent_color']);
        }

        if ($request->hasFile('logo')) {
            if ($ad?->logo_path) {
                Storage::disk('public')->delete($ad->logo_path);
            }
            $data['logo_path'] = $request->file('logo')->store('feed-ads', 'public');
        }

        if ($request->hasFile('image')) {
            if ($ad?->image_path) {
                Storage::disk('public')->delete($ad->image_path);
            }
            $data['image_path'] = $request->file('image')->store('feed-ads/covers', 'public');
        }

        return $data;
    }
}

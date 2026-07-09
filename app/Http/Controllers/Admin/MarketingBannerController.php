<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MarketingBanner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MarketingBannerController extends Controller
{
    public function index()
    {
        $banners = MarketingBanner::query()
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->paginate(20);

        return view('admin.banners.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.banners.form', [
            'banner' => new MarketingBanner([
                'audience' => 'all',
                'is_active' => true,
                'sort_order' => 0,
                'cta_label' => 'Saiba mais',
            ]),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['image_path'] = $this->storeImage($request);

        MarketingBanner::create($data);

        return redirect()
            ->route('admin.banners.index')
            ->with('status', 'Banner criado com sucesso.');
    }

    public function edit(MarketingBanner $banner)
    {
        return view('admin.banners.form', compact('banner'));
    }

    public function update(Request $request, MarketingBanner $banner)
    {
        $data = $this->validated($request);

        if ($request->hasFile('image')) {
            if ($banner->image_path) {
                Storage::disk('public')->delete($banner->image_path);
            }
            $data['image_path'] = $this->storeImage($request);
        }

        $banner->update($data);

        return redirect()
            ->route('admin.banners.index')
            ->with('status', 'Banner atualizado com sucesso.');
    }

    public function destroy(MarketingBanner $banner)
    {
        if ($banner->image_path) {
            Storage::disk('public')->delete($banner->image_path);
        }

        $banner->delete();

        return redirect()
            ->route('admin.banners.index')
            ->with('status', 'Banner removido.');
    }

    public function toggle(MarketingBanner $banner)
    {
        $banner->update(['is_active' => ! $banner->is_active]);

        return back()->with('status', $banner->is_active ? 'Banner ativado.' : 'Banner desativado.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:120'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'link_url' => ['nullable', 'url', 'max:500'],
            'cta_label' => ['nullable', 'string', 'max:60'],
            'audience' => ['required', 'in:all,player,scout'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:999'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'image' => ['nullable', 'image', 'max:5120'],
        ]);

        unset($data['image']);

        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);

        return $data;
    }

    private function storeImage(Request $request): ?string
    {
        if (! $request->hasFile('image')) {
            return null;
        }

        return $request->file('image')->store('marketing-banners', 'public');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MonthlyHighlight;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class MonthlyHighlightController extends Controller
{
    public function index(Request $request)
    {
        $year = (int) $request->query('year', now()->year);
        $month = (int) $request->query('month', now()->month);

        $highlights = MonthlyHighlight::query()
            ->with([
                'user:id,full_name,name,email,plan_group',
                'user.playerProfile.photos',
                'user.playerProfile.videos',
                'user.scoutProfile.photos',
            ])
            ->when($request->filled('year'), fn ($q) => $q->where('year', $year))
            ->when($request->filled('month'), fn ($q) => $q->where('month', $month))
            ->orderByDesc('year')
            ->orderByDesc('month')
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        return view('admin.highlights.index', [
            'highlights' => $highlights,
            'year' => $year,
            'month' => $month,
        ]);
    }

    public function create()
    {
        return view('admin.highlights.form', [
            'highlight' => new MonthlyHighlight([
                'category' => MonthlyHighlight::CATEGORY_PLAYER,
                'month' => (int) now()->month,
                'year' => (int) now()->year,
                'is_active' => true,
                'sort_order' => 0,
            ]),
            'users' => $this->eligibleUsers(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $user = User::query()->findOrFail($data['user_id']);

        MonthlyHighlight::create([
            ...$data,
            'name' => $user->full_name ?: $user->name,
        ]);

        return redirect()
            ->route('admin.highlights.index')
            ->with('status', 'Destaque criado. A landing usa foto, vídeo e história do perfil do usuário.');
    }

    public function edit(MonthlyHighlight $highlight)
    {
        $highlight->load([
            'user:id,full_name,name,email,plan_group',
            'user.playerProfile.photos',
            'user.playerProfile.videos',
            'user.scoutProfile.photos',
        ]);

        return view('admin.highlights.form', [
            'highlight' => $highlight,
            'users' => $this->eligibleUsers($highlight->user_id),
        ]);
    }

    public function update(Request $request, MonthlyHighlight $highlight)
    {
        $data = $this->validated($request, $highlight);
        $user = User::query()->findOrFail($data['user_id']);

        if ($data['category'] !== MonthlyHighlight::CATEGORY_BUSINESSMAN && $highlight->video_path) {
            Storage::disk('public')->delete($highlight->video_path);
            $data['video_path'] = null;
            $data['video_url'] = null;
        }

        if (! filled($highlight->name) || $highlight->user_id !== $user->id) {
            $data['name'] = $user->full_name ?: $user->name;
        }

        $highlight->update($data);

        return redirect()
            ->route('admin.highlights.index')
            ->with('status', 'Destaque atualizado.');
    }

    public function destroy(MonthlyHighlight $highlight)
    {
        if ($highlight->photo_path) {
            Storage::disk('public')->delete($highlight->photo_path);
        }

        if ($highlight->video_path) {
            Storage::disk('public')->delete($highlight->video_path);
        }

        $highlight->delete();

        return redirect()
            ->route('admin.highlights.index')
            ->with('status', 'Destaque removido.');
    }

    public function toggle(MonthlyHighlight $highlight)
    {
        $highlight->update(['is_active' => ! $highlight->is_active]);

        return back()->with('status', $highlight->is_active ? 'Destaque ativado.' : 'Destaque desativado.');
    }

    private function validated(Request $request, ?MonthlyHighlight $highlight = null): array
    {
        $data = $request->validate([
            'category' => ['required', Rule::in(MonthlyHighlight::CATEGORIES)],
            'user_id' => [
                'required',
                'integer',
                Rule::exists('users', 'id')->where(function ($query) use ($request) {
                    $planGroups = MonthlyHighlight::planGroupsForCategory($request->string('category')->toString());
                    $query->whereIn('plan_group', $planGroups)->where('is_active', true);
                }),
            ],
            'month' => ['required', 'integer', 'min:1', 'max:12'],
            'year' => ['required', 'integer', 'min:2024', 'max:2100'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:999'],
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);

        $duplicate = MonthlyHighlight::query()
            ->where('category', $data['category'])
            ->where('month', $data['month'])
            ->where('year', $data['year'])
            ->when($highlight, fn ($q) => $q->where('id', '!=', $highlight->id))
            ->exists();

        if ($duplicate) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'category' => 'Já existe um destaque desta categoria para este mês/ano.',
            ]);
        }

        return $data;
    }

    private function eligibleUsers(?int $includeUserId = null)
    {
        return User::query()
            ->where(function ($query) use ($includeUserId) {
                $query->where('is_active', true)
                    ->whereIn('plan_group', array_keys(MonthlyHighlight::PLAN_GROUP_MAP));

                if ($includeUserId) {
                    $query->orWhere('id', $includeUserId);
                }
            })
            ->orderBy('full_name')
            ->orderBy('email')
            ->get(['id', 'full_name', 'name', 'email', 'plan_group']);
    }
}

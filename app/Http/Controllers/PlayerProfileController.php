<?php

namespace App\Http\Controllers;

use App\Models\PlayerProfile;
use App\Models\PlayerVideo;
use App\Models\PlayerStat;
use App\Models\PlayerPhoto;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PlayerProfileController extends Controller
{
    public function index(Request $request)
    {
        $query = PlayerProfile::query()->with('user');

        if ($position = $request->string('position')->toString()) {
            $query->where('position', $position);
        }

        if ($city = $request->string('city')->toString()) {
            $query->where('city', 'like', '%'.$city.'%');
        }

        if ($state = $request->string('state')->toString()) {
            $query->where('state', 'like', '%'.$state.'%');
        }

        if ($foot = $request->string('dominant_foot')->toString()) {
            $query->where('dominant_foot', $foot);
        }

        if ($minAge = $request->integer('age_min')) {
            $query->where('age', '>=', $minAge);
        }

        if ($maxAge = $request->integer('age_max')) {
            $query->where('age', '<=', $maxAge);
        }

        $players = $query->paginate(12)->withQueryString();

        return view('players.index', [
            'players' => $players,
        ]);
    }

    public function show(User $user)
    {
        abort_unless($user->role === 'player', 404);

        $profile = $user->playerProfile()
            ->with(['videos', 'photos', 'stats'])
            ->firstOrCreate(['user_id' => $user->id]);

        return view('players.show', [
            'user' => $user,
            'profile' => $profile,
        ]);
    }

    public function edit(Request $request)
    {
        $user = $request->user();

        abort_unless($user->role === 'player', 403);

        $profile = $user->playerProfile()->firstOrCreate(['user_id' => $user->id]);

        return view('players.edit', [
            'user' => $user,
            'profile' => $profile,
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        abort_unless($user->role === 'player', 403);

        $data = $request->validate([
            'full_name' => ['nullable', 'string', 'max:255'],
            'position' => ['nullable', 'string', 'max:100'],
            'age' => ['nullable', 'integer', 'min:10', 'max:60'],
            'height_cm' => ['nullable', 'integer', 'min:120', 'max:230'],
            'weight_kg' => ['nullable', 'integer', 'min:40', 'max:130'],
            'current_club' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'max:255'],
            'dominant_foot' => ['nullable', 'in:right,left,both'],
            'bio' => ['nullable', 'string', 'max:800'],
        ]);

        if (! empty($data['full_name'])) {
            $user->full_name = $data['full_name'];
            $user->city = $data['city'] ?? $user->city;
            $user->state = $data['state'] ?? $user->state;
            $user->save();
        }

        $profile = $user->playerProfile()->firstOrCreate(['user_id' => $user->id]);
        $profile->fill(collect($data)->except('full_name')->toArray());
        $profile->save();

        return redirect()->route('me.player-profile.edit')->with('status', 'Perfil atualizado com sucesso.');
    }

    public function videos(Request $request)
    {
        $user = $request->user();

        abort_unless($user->role === 'player', 403);

        $profile = $user->playerProfile()->firstOrCreate(['user_id' => $user->id]);
        $videos = $profile->videos()->orderBy('display_order')->latest()->get();

        return view('players.videos', [
            'user' => $user,
            'profile' => $profile,
            'videos' => $videos,
        ]);
    }

    public function storeVideo(Request $request)
    {
        $user = $request->user();

        abort_unless($user->role === 'player', 403);

        $profile = $user->playerProfile()->firstOrCreate(['user_id' => $user->id]);

        $data = $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'url' => ['required', 'url', 'max:500'],
        ]);

        PlayerVideo::create([
            'player_profile_id' => $profile->id,
            'title' => $data['title'],
            'url' => $data['url'],
        ]);

        return redirect()->route('me.player-videos')->with('status', 'Vídeo adicionado com sucesso.');
    }

    public function stats(Request $request)
    {
        $user = $request->user();

        abort_unless($user->role === 'player', 403);

        $profile = $user->playerProfile()->firstOrCreate(['user_id' => $user->id]);
        $stats = $profile->stats()->latest()->get();

        return view('players.stats', [
            'user' => $user,
            'profile' => $profile,
            'stats' => $stats,
        ]);
    }

    public function storeStat(Request $request)
    {
        $user = $request->user();

        abort_unless($user->role === 'player', 403);

        $profile = $user->playerProfile()->firstOrCreate(['user_id' => $user->id]);

        $data = $request->validate([
            'season' => ['nullable', 'string', 'max:50'],
            'matches_played' => ['required', 'integer', 'min:0'],
            'goals' => ['required', 'integer', 'min:0'],
            'assists' => ['required', 'integer', 'min:0'],
            'minutes_played' => ['required', 'integer', 'min:0'],
        ]);

        PlayerStat::create([
            'player_profile_id' => $profile->id,
            'season' => $data['season'],
            'matches_played' => $data['matches_played'],
            'goals' => $data['goals'],
            'assists' => $data['assists'],
            'minutes_played' => $data['minutes_played'],
        ]);

        return redirect()->route('me.player-stats')->with('status', 'Estatística adicionada com sucesso.');
    }

    public function photos(Request $request)
    {
        $user = $request->user();

        abort_unless($user->role === 'player', 403);

        $profile = $user->playerProfile()->firstOrCreate(['user_id' => $user->id]);
        $photos = $profile->photos()
            ->orderBy('display_order')
            ->latest()
            ->get();

        return view('players.photos', [
            'user' => $user,
            'profile' => $profile,
            'photos' => $photos,
        ]);
    }

    public function storePhoto(Request $request)
    {
        $user = $request->user();

        abort_unless($user->role === 'player', 403);

        $profile = $user->playerProfile()->firstOrCreate(['user_id' => $user->id]);

        $data = $request->validate([
            'photos' => ['required', 'array', 'min:1'],
            'photos.*' => ['required', 'image', 'max:5120'],
            'caption' => ['nullable', 'string', 'max:255'],
        ]);

        $caption = $data['caption'] ?? null;
        $count = 0;

        foreach ($request->file('photos') as $file) {
            $path = $file->store('player-photos', 'public');
            PlayerPhoto::create([
                'player_profile_id' => $profile->id,
                'path' => $path,
                'caption' => $caption,
            ]);
            $count++;
        }

        $message = $count === 1 ? 'Foto adicionada com sucesso.' : "{$count} fotos adicionadas com sucesso.";

        return redirect()->route('me.player-photos')->with('status', $message);
    }

    public function destroyPhoto(PlayerPhoto $photo, Request $request)
    {
        $user = $request->user();

        abort_unless($user->role === 'player', 403);
        abort_unless($photo->playerProfile && $photo->playerProfile->user_id === $user->id, 403);

        if ($photo->path) {
            Storage::disk('public')->delete($photo->path);
        }

        $photo->delete();

        return redirect()->route('me.player-photos')->with('status', 'Foto removida com sucesso.');
    }
}


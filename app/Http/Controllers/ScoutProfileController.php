<?php

namespace App\Http\Controllers;

use App\Models\ScoutProfile;
use App\Models\ScoutPhoto;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ScoutProfileController extends Controller
{
    public function show(User $user)
    {
        abort_unless($user->role === 'scout', 404);

        $profile = $user->scoutProfile()
            ->with('photos')
            ->firstOrCreate(['user_id' => $user->id]);

        return view('scouts.show', [
            'user' => $user,
            'profile' => $profile,
        ]);
    }

    public function edit(Request $request)
    {
        $user = $request->user();

        abort_unless($user->role === 'scout', 403);

        $profile = $user->scoutProfile()->firstOrCreate(['user_id' => $user->id]);

        return view('scouts.edit', [
            'user' => $user,
            'profile' => $profile,
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        abort_unless($user->role === 'scout', 403);

        $data = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'age' => ['nullable', 'integer', 'min:18', 'max:99'],
            'city' => ['nullable', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:255'],
            'has_company' => ['nullable', 'in:0,1'],
            'company_name' => ['nullable', 'required_if:has_company,1', 'string', 'max:255'],
            'scope' => ['nullable', 'in:regional,nacional,internacional'],
            'is_federated' => ['nullable', 'in:0,1'],
            'federation_name' => ['nullable', 'required_if:is_federated,1', 'string', 'max:255'],
        ]);

        $user->full_name = $data['full_name'];
        $user->city = $data['city'] ?? $user->city;
        $user->state = $data['state'] ?? $user->state;
        $user->country = $data['country'] ?? $user->country;
        $user->save();

        $profileData = collect($data)->except('full_name')->toArray();
        $profileData['has_company'] = ($data['has_company'] ?? '0') === '1';
        $profileData['is_federated'] = ($data['is_federated'] ?? '0') === '1';

        if (! $profileData['has_company']) {
            $profileData['company_name'] = null;
        }

        if (! $profileData['is_federated']) {
            $profileData['federation_name'] = null;
        }

        $profile = $user->scoutProfile()->firstOrCreate(['user_id' => $user->id]);
        $profile->fill($profileData);
        $profile->save();

        return redirect()->route('me.scout-profile.edit')->with('status', 'Perfil atualizado com sucesso.');
    }

    public function photos(Request $request)
    {
        $user = $request->user();

        abort_unless($user->role === 'scout', 403);

        $profile = $user->scoutProfile()->firstOrCreate(['user_id' => $user->id]);
        $photos = $profile->photos()
            ->orderBy('display_order')
            ->latest()
            ->get();

        return view('scouts.photos', [
            'user' => $user,
            'profile' => $profile,
            'photos' => $photos,
        ]);
    }

    public function storePhoto(Request $request)
    {
        $user = $request->user();

        abort_unless($user->role === 'scout', 403);

        $profile = $user->scoutProfile()->firstOrCreate(['user_id' => $user->id]);

        $data = $request->validate([
            'photos' => ['required', 'array', 'min:1'],
            'photos.*' => ['required', 'image', 'max:5120'],
            'caption' => ['nullable', 'string', 'max:255'],
        ]);

        $caption = $data['caption'] ?? null;
        $count = 0;

        foreach ($request->file('photos') as $file) {
            $path = $file->store('scout-photos', 'public');
            ScoutPhoto::create([
                'scout_profile_id' => $profile->id,
                'path' => $path,
                'caption' => $caption,
            ]);
            $count++;
        }

        $message = $count === 1 ? 'Foto adicionada com sucesso.' : "{$count} fotos adicionadas com sucesso.";

        return redirect()->route('me.scout-photos')->with('status', $message);
    }

    public function destroyPhoto(ScoutPhoto $photo, Request $request)
    {
        $user = $request->user();

        abort_unless($user->role === 'scout', 403);
        abort_unless($photo->scoutProfile && $photo->scoutProfile->user_id === $user->id, 403);

        if ($photo->path) {
            Storage::disk('public')->delete($photo->path);
        }

        $photo->delete();

        return redirect()->route('me.scout-photos')->with('status', 'Foto removida com sucesso.');
    }
}


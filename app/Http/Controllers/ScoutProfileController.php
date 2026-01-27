<?php

namespace App\Http\Controllers;

use App\Models\ScoutProfile;
use App\Models\User;
use Illuminate\Http\Request;

class ScoutProfileController extends Controller
{
    public function show(User $user)
    {
        abort_unless($user->role === 'scout', 404);

        $profile = $user->scoutProfile()->firstOrCreate(['user_id' => $user->id]);

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
            'full_name' => ['nullable', 'string', 'max:255'],
            'professional_type' => ['nullable', 'in:empresario,agente,treinador,olheiro'],
            'organization' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'max:255'],
            'website' => ['nullable', 'string', 'max:255'],
            'bio' => ['nullable', 'string', 'max:800'],
        ]);

        if (! empty($data['full_name'])) {
            $user->full_name = $data['full_name'];
            $user->city = $data['city'] ?? $user->city;
            $user->state = $data['state'] ?? $user->state;
            $user->save();
        }

        $profile = $user->scoutProfile()->firstOrCreate(['user_id' => $user->id]);
        $profile->fill(collect($data)->except('full_name')->toArray());
        $profile->save();

        return redirect()->route('me.scout-profile.edit')->with('status', 'Perfil atualizado com sucesso.');
    }
}


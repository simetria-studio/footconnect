<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = $request->user();

        if ($user->role === 'player') {
            return view('home.player', [
                'user' => $user,
                'profile' => $user->playerProfile,
            ]);
        }

        return view('home.scout', [
            'user' => $user,
            'profile' => $user->scoutProfile,
        ]);
    }
}

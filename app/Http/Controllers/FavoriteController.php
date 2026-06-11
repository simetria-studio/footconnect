<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\User;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        abort_unless($user->role === 'scout', 403);

        $favorites = Favorite::where('scout_id', $user->id)
            ->with(['player.playerProfile', 'player'])
            ->latest()
            ->paginate(12);

        return view('favorites.index', [
            'favorites' => $favorites,
        ]);
    }

    public function toggle(Request $request, User $player)
    {
        $scout = $request->user();
        abort_unless($scout->role === 'scout', 403);
        abort_unless($player->role === 'player', 404);

        $existing = Favorite::where('scout_id', $scout->id)
            ->where('player_id', $player->id)
            ->first();

        if ($existing) {
            $existing->delete();

            return back()->with('status', 'Jogador removido dos favoritos.');
        }

        Favorite::create([
            'scout_id' => $scout->id,
            'player_id' => $player->id,
        ]);

        return back()->with('status', 'Jogador adicionado aos favoritos.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\NewsPost;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $role = $request->user()?->role;

        $posts = NewsPost::query()
            ->published()
            ->forAudience($role)
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->paginate(12);

        return view('news.index', compact('posts'));
    }

    public function show(Request $request, string $slug)
    {
        $role = $request->user()?->role;

        $post = NewsPost::query()
            ->where('slug', $slug)
            ->published()
            ->forAudience($role)
            ->firstOrFail();

        return view('news.show', compact('post'));
    }
}

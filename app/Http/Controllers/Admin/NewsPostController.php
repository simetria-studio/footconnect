<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NewsPostController extends Controller
{
    public function index()
    {
        $posts = NewsPost::query()
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->paginate(20);

        return view('admin.news.index', compact('posts'));
    }

    public function create()
    {
        return view('admin.news.form', [
            'post' => new NewsPost([
                'audience' => 'all',
                'is_published' => true,
                'published_at' => now(),
            ]),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['image_path'] = $this->storeImage($request);
        $data['slug'] = NewsPost::uniqueSlug($data['title']);

        NewsPost::create($data);

        return redirect()
            ->route('admin.news.index')
            ->with('status', 'Notícia publicada com sucesso.');
    }

    public function edit(NewsPost $news)
    {
        return view('admin.news.form', ['post' => $news]);
    }

    public function update(Request $request, NewsPost $news)
    {
        $data = $this->validated($request);

        if ($request->hasFile('image')) {
            if ($news->image_path) {
                Storage::disk('public')->delete($news->image_path);
            }
            $data['image_path'] = $this->storeImage($request);
        }

        if ($request->filled('slug')) {
            $data['slug'] = NewsPost::uniqueSlug($request->string('slug')->toString(), $news->id);
        }

        $news->update($data);

        return redirect()
            ->route('admin.news.index')
            ->with('status', 'Notícia atualizada com sucesso.');
    }

    public function destroy(NewsPost $news)
    {
        if ($news->image_path) {
            Storage::disk('public')->delete($news->image_path);
        }

        $news->delete();

        return redirect()
            ->route('admin.news.index')
            ->with('status', 'Notícia removida.');
    }

    public function toggle(NewsPost $news)
    {
        $news->is_published = ! $news->is_published;

        if ($news->is_published && ! $news->published_at) {
            $news->published_at = now();
        }

        $news->save();

        return back()->with('status', $news->is_published ? 'Notícia publicada.' : 'Notícia despublicada.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'slug' => ['nullable', 'string', 'max:200'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'body' => ['required', 'string'],
            'audience' => ['required', 'in:all,player,scout'],
            'published_at' => ['nullable', 'date'],
            'image' => ['nullable', 'image', 'max:5120'],
        ]);

        unset($data['image'], $data['slug']);

        $data['is_published'] = $request->boolean('is_published');
        $data['body'] = $this->sanitizeBody($data['body']);

        return $data;
    }

    private function sanitizeBody(string $html): string
    {
        $allowed = '<p><br><strong><b><em><i><u><ul><ol><li><a><h2><h3><blockquote>';
        $clean = strip_tags($html, $allowed);

        // Remove event handlers / javascript: URLs from anchors
        $clean = preg_replace('/\s+on\w+\s*=\s*(".*?"|\'.*?\'|[^\s>]+)/i', '', $clean) ?? $clean;
        $clean = preg_replace('/href\s*=\s*([\'"])\s*javascript:[^\'"]*\1/i', 'href="#"', $clean) ?? $clean;

        return trim($clean);
    }

    private function storeImage(Request $request): ?string
    {
        if (! $request->hasFile('image')) {
            return null;
        }

        return $request->file('image')->store('news-images', 'public');
    }
}

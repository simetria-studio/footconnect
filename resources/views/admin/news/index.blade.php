@extends('admin.layout')

@section('title', 'Notícias')
@section('page-title', 'Notícias')
@section('page-subtitle', 'Conteúdo exibido na home e na seção de notícias')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <p class="small fc-text-secondary mb-0">Publique novidades e campanhas na landing e em /noticias.</p>
    <a href="{{ route('admin.news.create') }}" class="btn btn-success btn-sm">Nova notícia</a>
</div>

<div class="card fc-card">
    <div class="table-responsive">
        <table class="table table-dark table-hover mb-0">
            <thead>
                <tr>
                    <th class="ps-4">Título</th>
                    <th>Público</th>
                    <th>Publicação</th>
                    <th>Status</th>
                    <th class="text-end pe-4">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($posts as $post)
                    <tr class="{{ ! $post->is_published ? 'opacity-50' : '' }}">
                        <td class="ps-4">
                            <div class="fw-semibold">{{ $post->title }}</div>
                            <div class="small fc-text-secondary">/noticias/{{ $post->slug }}</div>
                        </td>
                        <td class="small">{{ $post->audience_label }}</td>
                        <td class="small">{{ optional($post->published_at)->format('d/m/Y H:i') ?? '—' }}</td>
                        <td>
                            @if($post->is_published)
                                <span class="badge bg-success">Publicada</span>
                            @else
                                <span class="badge bg-secondary">Rascunho</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <div class="d-flex justify-content-end gap-1 flex-wrap">
                                <a href="{{ route('admin.news.edit', $post) }}" class="btn btn-sm btn-outline-secondary">Editar</a>
                                <form method="POST" action="{{ route('admin.news.toggle', $post) }}">@csrf
                                    <button class="btn btn-sm btn-outline-success" type="submit">{{ $post->is_published ? 'Despublicar' : 'Publicar' }}</button>
                                </form>
                                <form method="POST" action="{{ route('admin.news.destroy', $post) }}" onsubmit="return confirm('Remover esta notícia?')">@csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" type="submit">Excluir</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 fc-text-secondary">Nenhuma notícia cadastrada.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($posts->hasPages())
        <div class="p-3">{{ $posts->links() }}</div>
    @endif
</div>
@endsection

@extends('admin.layout')

@section('title', $post->exists ? 'Editar notícia' : 'Nova notícia')
@section('page-title', $post->exists ? 'Editar notícia' : 'Nova notícia')
@section('page-subtitle', 'Aparece na landing pública e em /noticias')

@section('content')
<div class="mb-3">
    <a href="{{ route('admin.news.index') }}" class="btn btn-sm btn-outline-secondary">Voltar</a>
</div>

<div class="card fc-card">
    <div class="card-body">
        <form method="POST"
              action="{{ $post->exists ? route('admin.news.update', $post) : route('admin.news.store') }}"
              enctype="multipart/form-data"
              class="row g-3">
            @csrf
            @if($post->exists)
                @method('PUT')
            @endif

            <div class="col-12">
                <label class="form-label" for="title">Título</label>
                <input type="text" class="form-control bg-dark border-secondary text-white" id="title" name="title" value="{{ old('title', $post->title) }}" required maxlength="180">
            </div>

            @if($post->exists)
                <div class="col-12">
                    <label class="form-label" for="slug">Slug (URL)</label>
                    <input type="text" class="form-control bg-dark border-secondary text-white" id="slug" name="slug" value="{{ old('slug', $post->slug) }}">
                </div>
            @endif

            <div class="col-12">
                <label class="form-label" for="excerpt">Resumo</label>
                <textarea class="form-control bg-dark border-secondary text-white" id="excerpt" name="excerpt" rows="2" maxlength="500">{{ old('excerpt', $post->excerpt) }}</textarea>
            </div>

            <div class="col-12 mb-2">
                <label class="form-label" for="body">Conteúdo</label>
                <textarea class="d-none" id="body" name="body" required>{{ old('body', $post->body) }}</textarea>
                <div class="fc-quill-wrap">
                    <div id="body-editor"></div>
                </div>
                <div class="form-text mt-2">Use a barra de ferramentas para negrito, listas, links e títulos.</div>
            </div>

            <div class="col-12 col-md-4">
                <label class="form-label" for="audience">Público</label>
                <select class="form-select bg-dark border-secondary text-white" id="audience" name="audience" required>
                    <option value="all" {{ old('audience', $post->audience) === 'all' ? 'selected' : '' }}>Todos (landing)</option>
                    <option value="player" {{ old('audience', $post->audience) === 'player' ? 'selected' : '' }}>Jogadores</option>
                    <option value="scout" {{ old('audience', $post->audience) === 'scout' ? 'selected' : '' }}>Profissionais</option>
                </select>
                <div class="form-text">A landing pública exibe apenas notícias com público "Todos".</div>
            </div>

            <div class="col-md-4">
                <label class="form-label" for="published_at">Data de publicação</label>
                <input type="datetime-local" class="form-control bg-dark border-secondary text-white" id="published_at" name="published_at" value="{{ old('published_at', optional($post->published_at)->format('Y-m-d\TH:i')) }}">
            </div>

            <div class="col-md-4 d-flex align-items-end">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="1" id="is_published" name="is_published" {{ old('is_published', $post->is_published) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_published">Publicada</label>
                </div>
            </div>

            <div class="col-12">
                <label class="form-label" for="image">Imagem de capa {{ $post->exists ? '(opcional)' : '' }}</label>
                <input type="file" class="form-control bg-dark border-secondary text-white" id="image" name="image" accept="image/*">
                @if($post->image_url)
                    <img src="{{ $post->image_url }}" alt="" class="rounded mt-2" style="max-width: 100%; max-height: 180px; object-fit: cover;">
                @endif
            </div>

            <div class="col-12">
                <button type="submit" class="btn btn-success">{{ $post->exists ? 'Salvar alterações' : 'Publicar notícia' }}</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet">
<style>
    .fc-quill-wrap {
        display: block;
        width: 100%;
        position: relative;
        clear: both;
        margin-bottom: 0.25rem;
        border-radius: 10px;
        overflow: visible;
    }

    .fc-quill-wrap .ql-toolbar.ql-snow {
        display: block;
        width: 100%;
        background: #071a10;
        border: 1px solid rgba(148, 163, 184, 0.35);
        border-radius: 10px 10px 0 0;
        border-bottom: none;
        box-sizing: border-box;
    }

    .fc-quill-wrap .ql-container.ql-snow {
        display: block;
        width: 100%;
        height: 280px !important;
        background: #0b1a12;
        border: 1px solid rgba(148, 163, 184, 0.35);
        border-radius: 0 0 10px 10px;
        box-sizing: border-box;
        font-size: 0.95rem;
        color: #f8fafc;
    }

    .fc-quill-wrap .ql-editor {
        min-height: 100%;
        line-height: 1.65;
        color: #f8fafc;
    }

    .fc-quill-wrap .ql-editor.ql-blank::before {
        color: #6b7280;
        font-style: normal;
    }

    .ql-snow .ql-stroke { stroke: #9ca3af; }
    .ql-snow .ql-fill { fill: #9ca3af; }
    .ql-snow .ql-picker { color: #9ca3af; }
    .ql-snow .ql-picker-options {
        background: #0b1a12;
        border-color: rgba(148, 163, 184, 0.35);
    }
    .ql-snow.ql-toolbar button:hover .ql-stroke,
    .ql-snow .ql-toolbar button:hover .ql-stroke,
    .ql-snow.ql-toolbar button.ql-active .ql-stroke,
    .ql-snow .ql-toolbar button.ql-active .ql-stroke {
        stroke: #22c55e;
    }
    .ql-snow.ql-toolbar button:hover .ql-fill,
    .ql-snow .ql-toolbar button:hover .ql-fill,
    .ql-snow.ql-toolbar button.ql-active .ql-fill,
    .ql-snow .ql-toolbar button.ql-active .ql-fill {
        fill: #22c55e;
    }
    .ql-snow .ql-picker-label:hover,
    .ql-snow .ql-picker-item:hover,
    .ql-snow .ql-picker-label.ql-active,
    .ql-snow .ql-picker-item.ql-selected {
        color: #22c55e;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('body');
    const form = input?.closest('form');
    if (!input || !form) return;

    const quill = new Quill('#body-editor', {
        theme: 'snow',
        placeholder: 'Escreva o conteúdo da notícia...',
        modules: {
            toolbar: [
                [{ header: [2, 3, false] }],
                ['bold', 'italic', 'underline'],
                [{ list: 'ordered' }, { list: 'bullet' }],
                ['link'],
                ['blockquote'],
                ['clean']
            ]
        }
    });

    if (input.value.trim()) {
        quill.root.innerHTML = input.value;
    }

    form.addEventListener('submit', function (event) {
        const html = quill.root.innerHTML;
        const text = quill.getText().trim();

        if (!text) {
            event.preventDefault();
            alert('Escreva o conteúdo da notícia.');
            quill.focus();
            return;
        }

        input.value = html;
    });
});
</script>
@endpush

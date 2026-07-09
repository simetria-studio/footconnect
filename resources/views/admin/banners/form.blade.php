@extends('admin.layout')

@section('title', $banner->exists ? 'Editar banner' : 'Novo banner')
@section('page-title', $banner->exists ? 'Editar banner' : 'Novo banner')
@section('page-subtitle', 'Aparece na landing pública (site) — use público "Todos"')

@section('content')
<div class="mb-3">
    <a href="{{ route('admin.banners.index') }}" class="btn btn-sm btn-outline-secondary">Voltar</a>
</div>

<div class="card fc-card">
    <div class="card-body">
        <form method="POST"
              action="{{ $banner->exists ? route('admin.banners.update', $banner) : route('admin.banners.store') }}"
              enctype="multipart/form-data"
              class="row g-3">
            @csrf
            @if($banner->exists)
                @method('PUT')
            @endif

            <div class="col-12">
                <label class="form-label" for="title">Título</label>
                <input type="text" class="form-control bg-dark border-secondary text-white" id="title" name="title" value="{{ old('title', $banner->title) }}" required maxlength="120">
            </div>

            <div class="col-12">
                <label class="form-label" for="subtitle">Subtítulo</label>
                <input type="text" class="form-control bg-dark border-secondary text-white" id="subtitle" name="subtitle" value="{{ old('subtitle', $banner->subtitle) }}" maxlength="255">
            </div>

            <div class="col-md-6">
                <label class="form-label" for="link_url">Link (URL)</label>
                <input type="url" class="form-control bg-dark border-secondary text-white" id="link_url" name="link_url" value="{{ old('link_url', $banner->link_url) }}" placeholder="https://...">
            </div>

            <div class="col-md-6">
                <label class="form-label" for="cta_label">Texto do botão</label>
                <input type="text" class="form-control bg-dark border-secondary text-white" id="cta_label" name="cta_label" value="{{ old('cta_label', $banner->cta_label) }}" maxlength="60">
            </div>

            <div class="col-md-4">
                <label class="form-label" for="audience">Público</label>
                <select class="form-select bg-dark border-secondary text-white" id="audience" name="audience" required>
                    <option value="all" {{ old('audience', $banner->audience) === 'all' ? 'selected' : '' }}>Todos (landing)</option>
                    <option value="player" {{ old('audience', $banner->audience) === 'player' ? 'selected' : '' }}>Jogadores</option>
                    <option value="scout" {{ old('audience', $banner->audience) === 'scout' ? 'selected' : '' }}>Profissionais</option>
                </select>
                <div class="form-text">A landing pública exibe apenas banners com público "Todos".</div>
            </div>

            <div class="col-md-4">
                <label class="form-label" for="sort_order">Ordem</label>
                <input type="number" min="0" class="form-control bg-dark border-secondary text-white" id="sort_order" name="sort_order" value="{{ old('sort_order', $banner->sort_order ?? 0) }}">
            </div>

            <div class="col-md-4 d-flex align-items-end">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="1" id="is_active" name="is_active" {{ old('is_active', $banner->is_active) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">Ativo</label>
                </div>
            </div>

            <div class="col-md-6">
                <label class="form-label" for="starts_at">Início (opcional)</label>
                <input type="datetime-local" class="form-control bg-dark border-secondary text-white" id="starts_at" name="starts_at" value="{{ old('starts_at', optional($banner->starts_at)->format('Y-m-d\TH:i')) }}">
            </div>

            <div class="col-md-6">
                <label class="form-label" for="ends_at">Fim (opcional)</label>
                <input type="datetime-local" class="form-control bg-dark border-secondary text-white" id="ends_at" name="ends_at" value="{{ old('ends_at', optional($banner->ends_at)->format('Y-m-d\TH:i')) }}">
            </div>

            <div class="col-12">
                <label class="form-label" for="image">Imagem {{ $banner->exists ? '(deixe em branco para manter)' : '' }}</label>
                <input type="file" class="form-control bg-dark border-secondary text-white" id="image" name="image" accept="image/*">
                <div class="form-text">Recomendado: 1920×1080 px (widescreen), até 5 MB. O banner ocupa a largura toda da tela.</div>
                @if($banner->image_url)
                    <img src="{{ $banner->image_url }}" alt="" class="rounded mt-2" style="max-width: 100%; max-height: 160px; object-fit: cover;">
                @endif
            </div>

            <div class="col-12">
                <button type="submit" class="btn btn-success">{{ $banner->exists ? 'Salvar alterações' : 'Criar banner' }}</button>
            </div>
        </form>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Meus vídeos — FootConnect')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-8">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h1 class="h5 fw-bold fc-text-primary mb-1">Seus vídeos em destaque</h1>
                    <p class="small fc-text-secondary mb-0">
                        Adicione links de vídeos (YouTube, Vimeo, etc.) com seus melhores momentos para que empresários, agentes e olheiros possam te avaliar.
                    </p>
                </div>
                <a href="{{ route('home') }}" class="btn btn-sm btn-outline-secondary">Voltar</a>
            </div>

            @if (session('status'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('status') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Formulário de Adicionar Vídeo -->
            <div class="card fc-card mb-4">
                <div class="card-header">
                    <h5 class="mb-0 fw-bold">Adicionar novo vídeo</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('me.player-videos.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="title" class="form-label">Título (opcional)</label>
                            <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" placeholder="Ex: Melhores momentos 2024">
                        </div>
                        <div class="mb-3">
                            <label for="url" class="form-label">URL do vídeo</label>
                            <input type="url" class="form-control" id="url" name="url" value="{{ old('url') }}" placeholder="https://youtube.com/..." required>
                            <div class="form-text">Cole o link completo do vídeo (YouTube, Vimeo, etc.)</div>
                        </div>
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0 small">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <button type="submit" class="btn btn-success">Salvar vídeo</button>
                    </form>
                </div>
            </div>

            <!-- Lista de Vídeos -->
            <div class="card fc-card">
                <div class="card-header">
                    <h5 class="mb-0 fw-bold">Vídeos cadastrados</h5>
                </div>
                <div class="card-body">
                    @forelse($videos as $video)
                        <div class="d-flex align-items-center justify-content-between py-3 border-bottom fc-border">
                            <div class="flex-grow-1">
                                <h6 class="fw-bold fc-text-primary mb-1">{{ $video->title ?: 'Vídeo sem título' }}</h6>
                                <a href="{{ $video->url }}" target="_blank" class="btn btn-sm btn-outline-success">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" class="me-1">
                                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                                        <path d="M6.271 5.055a.5.5 0 0 1 .475 0l3.5 2a.5.5 0 0 1 0 .89l-3.5 2a.5.5 0 0 1-.475 0l-3.5-2a.5.5 0 0 1 0-.89z"/>
                                    </svg>
                                    Assistir
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4">
                            <p class="fc-text-secondary mb-0">Você ainda não cadastrou vídeos.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

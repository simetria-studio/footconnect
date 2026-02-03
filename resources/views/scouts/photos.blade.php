@extends('layouts.app')

@section('title', 'Minhas fotos — FootConnect')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-8">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h1 class="h5 fw-bold fc-text-primary mb-1">Suas fotos em destaque</h1>
                    <p class="small fc-text-secondary mb-0">
                        Você pode adicionar várias fotos: onde trabalhou, times em que jogou ou momentos da sua trajetória. Empresários e profissionais podem ter quantas fotos quiserem.
                    </p>
                </div>
                <a href="{{ route('me.scout-profile.edit') }}" class="btn btn-sm btn-outline-secondary">Voltar para o perfil</a>
            </div>

            @if (session('status'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('status') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Formulário de Adicionar Foto -->
            <div class="card fc-card mb-4">
                <div class="card-header">
                    <h5 class="mb-0 fw-bold">Adicionar fotos</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('me.scout-photos.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="photos" class="form-label">Uma ou várias fotos</label>
                            <input type="file" class="form-control" id="photos" name="photos[]" accept="image/*" multiple>
                            <div class="form-text">Selecione várias imagens de uma vez. Ex.: clubes, agências, eventos. JPG, PNG ou WEBP. Até 5MB por foto.</div>
                        </div>
                        <div class="mb-3">
                            <label for="caption" class="form-label">Legenda (opcional, para todas)</label>
                            <input type="text" class="form-control" id="caption" name="caption" value="{{ old('caption') }}" placeholder="Ex: Clube ABC (2020), Agência XYZ, Joguei pelo Time Y...">
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
                        <button type="submit" class="btn btn-success">Salvar fotos</button>
                    </form>
                </div>
            </div>

            <!-- Lista de Fotos -->
            <div class="card fc-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">Fotos cadastradas ({{ $photos->count() }})</h5>
                </div>
                <div class="card-body">
                    @forelse($photos as $photo)
                        <div class="row align-items-center py-3 border-bottom fc-border">
                            <div class="col-4 col-md-3">
                                <div class="ratio ratio-1x1 rounded overflow-hidden bg-light">
                                    <img
                                        src="{{ asset('storage/'.$photo->path) }}"
                                        alt="{{ $photo->caption ?: 'Foto do profissional' }}"
                                        class="img-fluid object-fit-cover"
                                    >
                                </div>
                            </div>
                            <div class="col-8 col-md-9 d-flex justify-content-between align-items-center">
                                <div class="me-3">
                                    <h6 class="fw-bold fc-text-primary mb-1">
                                        {{ $photo->caption ?: 'Foto sem legenda' }}
                                    </h6>
                                    <p class="small fc-text-secondary mb-0">
                                        Enviada em {{ $photo->created_at->format('d/m/Y H:i') }}
                                    </p>
                                </div>
                                <form method="POST" action="{{ route('me.scout-photos.destroy', $photo) }}" onsubmit="return confirm('Tem certeza que deseja remover esta foto?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        Remover
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4">
                            <p class="fc-text-secondary mb-1">Você ainda não cadastrou fotos.</p>
                            <p class="small mb-0">Adicione uma ou várias fotos de onde trabalhou ou times em que jogou.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Home Profissional — FootConnect')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Quick Actions Grid -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-6 col-lg-3">
            <a href="{{ route('players.index') }}" class="text-decoration-none">
                <div class="card fc-card fc-card-hover h-100">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <div class="fc-avatar-sm bg-success mx-auto">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                                </svg>
                            </div>
                        </div>
                        <h6 class="fw-bold fc-text-primary mb-1">Buscar</h6>
                        <p class="small fc-text-secondary mb-0">Encontrar talentos</p>
                    </div>
                </div>
            </a>
        </div>
        
        <div class="col-6 col-md-6 col-lg-3">
            <a href="{{ route('favorites.index') }}" class="text-decoration-none">
                <div class="card fc-card fc-card-hover h-100">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <div class="fc-avatar-sm bg-warning mx-auto">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                                </svg>
                            </div>
                        </div>
                        <h6 class="fw-bold fc-text-primary mb-1">Favoritos</h6>
                        <p class="small fc-text-secondary mb-0">Lista salva</p>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-6 col-md-6 col-lg-3">
            <a href="{{ route('messages.index') }}" class="text-decoration-none">
                <div class="card fc-card fc-card-hover h-100">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <div class="fc-avatar-sm bg-primary mx-auto">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1zm13 2.383-4.708 2.825L15 11.105zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741M1 11.105l4.708-2.897L1 5.383z"/>
                                </svg>
                            </div>
                        </div>
                        <h6 class="fw-bold fc-text-primary mb-1">Mensagens</h6>
                        <p class="small fc-text-secondary mb-0">Ver conversas</p>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Seção: Busca de Talentos -->
    <div class="mb-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h2 class="h5 fw-bold fc-text-primary mb-0">Busca de talentos</h2>
            <div class="fc-divider flex-grow-1 ms-3"></div>
        </div>

        <a href="{{ route('players.index') }}" class="text-decoration-none">
            <div class="card fc-card fc-card-hover">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3">
                        <div class="fc-avatar bg-success flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                            </svg>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="fw-bold fc-text-primary mb-1">Busca avançada</h6>
                            <p class="small fc-text-secondary mb-0">Filtre por posição, idade, cidade, pé dominante e encontre os melhores talentos</p>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16" class="text-muted">
                            <path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708"/>
                        </svg>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- Seção: Favoritos -->
    <div class="mb-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h2 class="h5 fw-bold fc-text-primary mb-0">Favoritos</h2>
            <div class="fc-divider flex-grow-1 ms-3"></div>
        </div>

        <div class="card fc-card">
            <div class="card-body">
                <div class="d-flex align-items-start gap-3">
                    <div class="fc-avatar-sm bg-warning flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M2 2v13.5a.5.5 0 0 0 .74.439L8 13.069l5.26 2.87A.5.5 0 0 0 14 15.5V2a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2"/>
                        </svg>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="fw-semibold fc-text-primary mb-1">Lista de jogadores salvos</h6>
                        <p class="small fc-text-secondary mb-2">
                            Em breve você poderá salvar e acompanhar jogadores favoritos diretamente aqui.
                        </p>
                        <p class="small text-muted mb-0">Nenhum jogador salvo ainda.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Dica Profissional -->
    <div class="card fc-card">
        <div class="card-body">
            <div class="d-flex align-items-start gap-3">
                <div class="fc-avatar-sm bg-secondary flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                        <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0"/>
                    </svg>
                </div>
                <div class="flex-grow-1">
                    <h6 class="fw-semibold fc-text-primary mb-1">Dica profissional</h6>
                    <p class="small fc-text-secondary mb-0">
                        Use os filtros avançados para encontrar jogadores específicos. Perfis completos com vídeos e estatísticas são mais confiáveis.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Home Jogador — FootConnect')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Quick Actions Grid -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-6 col-lg-3">
            <a href="{{ route('me.player-profile.edit') }}" class="text-decoration-none">
                <div class="card fc-card fc-card-hover h-100">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <div class="fc-avatar-sm bg-success mx-auto">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"/>
                                </svg>
                            </div>
                        </div>
                        <h6 class="fw-bold fc-text-primary mb-1">Perfil</h6>
                        <p class="small fc-text-secondary mb-0">Editar dados</p>
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

    <!-- Seção: Mídia & Estatísticas -->
    <div class="mb-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h2 class="h5 fw-bold fc-text-primary mb-0">Seu conteúdo</h2>
            <div class="fc-divider flex-grow-1 ms-3"></div>
        </div>

        <div class="row g-3">
            <!-- Vídeos -->
            <div class="col-12">
                <a href="{{ route('me.player-videos') }}" class="text-decoration-none">
                    <div class="card fc-card fc-card-hover">
                        <div class="card-body">
                            <div class="d-flex align-items-center gap-3">
                                <div class="fc-avatar bg-success flex-shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M0 12V4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2m6.79-6.907A.5.5 0 0 0 6 5.5v5a.5.5 0 0 0 .79.407L11 8.5V5.5a.5.5 0 0 0-.79-.407z"/>
                                    </svg>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="fw-bold fc-text-primary mb-1">Vídeos em destaque</h6>
                                    <p class="small fc-text-secondary mb-0">Adicione seus melhores momentos para impressionar olheiros e empresários</p>
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16" class="text-muted">
                                    <path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Estatísticas -->
            <div class="col-12">
                <a href="{{ route('me.player-stats') }}" class="text-decoration-none">
                    <div class="card fc-card fc-card-hover">
                        <div class="card-body">
                            <div class="d-flex align-items-center gap-3">
                                <div class="fc-avatar bg-warning flex-shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5.784 4A2.238 2.238 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.325 6.325 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1zM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5"/>
                                    </svg>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="fw-bold fc-text-primary mb-1">Estatísticas</h6>
                                    <p class="small fc-text-secondary mb-0">Registre seus números e mostre sua evolução em campo</p>
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16" class="text-muted">
                                    <path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </a>
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
                        Complete seu perfil com vídeos e estatísticas recentes. Perfis completos recebem 3x mais visualizações de profissionais.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

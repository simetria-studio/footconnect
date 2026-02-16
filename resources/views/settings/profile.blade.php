@extends('layouts.app')

@section('title', 'Configurações de perfil — FootConnect')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-8">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h1 class="h5 fw-bold fc-text-primary mb-0">Configurações da conta</h1>
                <a href="{{ route('home') }}" class="btn btn-sm btn-outline-secondary">Voltar</a>
            </div>

            @if (session('status'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('status') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Perfil Básico -->
            <div class="card fc-card mb-4">
                <div class="card-header">
                    <h5 class="mb-0 fw-bold">Perfil básico</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('settings.profile.update') }}">
                        @csrf
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="full_name" class="form-label">Nome completo</label>
                                <input type="text" class="form-control" id="full_name" name="full_name" value="{{ old('full_name', $user->full_name) }}" required>
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="city" class="form-label">Cidade</label>
                                <input type="text" class="form-control" id="city" name="city" value="{{ old('city', $user->city) }}">
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="state" class="form-label">Estado</label>
                                <input type="text" class="form-control" id="state" name="state" value="{{ old('state', $user->state) }}" placeholder="Ex: SP">
                            </div>
                        </div>

                        @if ($errors->any())
                            <div class="alert alert-danger mt-3">
                                <ul class="mb-0 small">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="mt-3">
                            <button type="submit" class="btn btn-success">Salvar</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Perfil público e fotos -->
            <div class="card fc-card mb-4">
                <div class="card-header">
                    <h5 class="mb-0 fw-bold">Perfil público e fotos</h5>
                </div>
                <div class="card-body">
                    <p class="small fc-text-secondary mb-3">
                        @if($user->role === 'player')
                            Edite suas informações de jogador e adicione fotos ou vídeos que aparecem no seu perfil para profissionais.
                        @else
                            Edite suas informações profissionais e adicione fotos de onde trabalhou ou times que representou.
                        @endif
                    </p>
                    <div class="d-flex flex-wrap gap-2">
                        @if($user->role === 'player')
                            <a href="{{ route('me.player-profile.edit') }}" class="btn btn-outline-success btn-sm">Editar perfil de jogador</a>
                            <a href="{{ route('me.player-photos') }}" class="btn btn-outline-success btn-sm">Gerenciar fotos</a>
                            <a href="{{ route('me.player-videos') }}" class="btn btn-outline-success btn-sm">Gerenciar vídeos</a>
                            <a href="{{ route('me.player-stats') }}" class="btn btn-outline-success btn-sm">Estatísticas</a>
                        @else
                            <a href="{{ route('me.scout-profile.edit') }}" class="btn btn-outline-success btn-sm">Editar perfil profissional</a>
                            <a href="{{ route('me.scout-photos') }}" class="btn btn-outline-success btn-sm">Gerenciar fotos</a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Alterar Senha -->
            <div class="card fc-card mb-4">
                <div class="card-header">
                    <h5 class="mb-0 fw-bold">Alterar senha</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('settings.password.update') }}">
                        @csrf
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="current_password" class="form-label">Senha atual</label>
                                <input type="password" class="form-control" id="current_password" name="current_password" required>
                            </div>
                            <div class="col-12">
                                <label for="password" class="form-label">Nova senha</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="col-12">
                                <label for="password_confirmation" class="form-label">Confirmar nova senha</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                            </div>
                        </div>

                        <div class="mt-3">
                            <button type="submit" class="btn btn-success">Atualizar senha</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Encerrar conta -->
            <div class="card fc-card border-danger">
                <div class="card-header border-danger">
                    <h5 class="mb-0 fw-bold text-danger">Encerrar ou excluir conta</h5>
                </div>
                <div class="card-body">
                    <p class="small fc-text-secondary mb-3">
                        Você pode apenas <strong>encerrar</strong> (desativar) sua conta ou <strong>excluir permanentemente</strong>.
                        <br>
                        - Encerrar conta: cancela sua assinatura (se houver) e bloqueia novo acesso, mas mantém o histórico salvo.<br>
                        - Excluir conta: remove definitivamente seus dados (perfil, mensagens, favoritos, etc.). Esta ação não pode ser desfeita.
                    </p>
                    <div class="d-flex flex-wrap gap-2">
                        <button type="button" class="btn btn-outline-danger"
                                data-bs-toggle="modal" data-bs-target="#cancelAccountModal">
                            Encerrar minha conta
                        </button>
                        <button type="button" class="btn btn-danger"
                                data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                            Excluir permanentemente minha conta
                        </button>
                    </div>
                </div>
            </div>

            <!-- Modal: Encerrar conta -->
            <div class="modal fade" id="cancelAccountModal" tabindex="-1" aria-labelledby="cancelAccountModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content fc-bg-secondary">
                        <div class="modal-header border-0">
                            <h5 class="modal-title text-danger" id="cancelAccountModalLabel">Encerrar conta</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p class="small fc-text-secondary mb-3">
                                Ao encerrar sua conta:
                            </p>
                            <ul class="small fc-text-secondary mb-3 text-start">
                                <li>Sua assinatura atual será <strong>cancelada</strong> (se houver).</li>
                                <li>Seu acesso ao FootConnect será <strong>bloqueado</strong> com este e-mail.</li>
                                <li>Seu histórico (mensagens, favoritos, perfis) será mantido apenas para controle interno.</li>
                            </ul>
                            <p class="small text-warning mb-0">
                                Você poderá voltar no futuro criando uma nova conta com o mesmo e-mail, mas esta conta ficará desativada.
                            </p>
                        </div>
                        <div class="modal-footer border-0">
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Voltar</button>
                            <form method="POST" action="{{ route('settings.account.cancel') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger btn-sm">
                                    Sim, encerrar minha conta
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal: Excluir conta -->
            <div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content fc-bg-secondary">
                        <div class="modal-header border-0">
                            <h5 class="modal-title text-danger" id="deleteAccountModalLabel">Excluir permanentemente conta</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p class="small fc-text-secondary mb-3">
                                Esta ação é <strong>definitiva</strong> e não pode ser desfeita.
                            </p>
                            <ul class="small fc-text-secondary mb-3 text-start">
                                <li>Seu usuário será removido do FootConnect.</li>
                                <li>Seus perfis, fotos, vídeos, estatísticas, favoritos e mensagens serão apagados.</li>
                                <li>Não será possível recuperar nenhum dado desta conta depois.</li>
                            </ul>
                            <p class="small text-warning mb-0">
                                Se você quer apenas parar de pagar e sair do app, use a opção <strong>Encerrar conta</strong>.
                                Use esta opção apenas se tiver certeza absoluta que deseja remover tudo.
                            </p>
                        </div>
                        <div class="modal-footer border-0">
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                            <form method="POST" action="{{ route('settings.account.delete') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm">
                                    Sim, excluir tudo definitivamente
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

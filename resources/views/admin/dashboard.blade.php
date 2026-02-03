@extends('admin.layout')

@section('title', 'Dashboard')

@section('content')
<div class="mb-4">
    <h1 class="h4 fw-bold fc-text-primary mb-1">Dashboard</h1>
    <p class="small fc-text-secondary mb-0">Visão geral do FootConnect</p>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card fc-card h-100">
            <div class="card-body text-center">
                <p class="small fc-text-secondary mb-1">Total de usuários</p>
                <p class="h4 fw-bold fc-text-primary mb-0">{{ $totalUsers }}</p>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card fc-card h-100">
            <div class="card-body text-center">
                <p class="small fc-text-secondary mb-1">Jogadores</p>
                <p class="h4 fw-bold fc-text-primary mb-0">{{ $players }}</p>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card fc-card h-100">
            <div class="card-body text-center">
                <p class="small fc-text-secondary mb-1">Profissionais</p>
                <p class="h4 fw-bold fc-text-primary mb-0">{{ $scouts }}</p>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card fc-card h-100">
            <div class="card-body text-center">
                <p class="small fc-text-secondary mb-1">Conversas</p>
                <p class="h4 fw-bold fc-text-primary mb-0">{{ $conversations }}</p>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card fc-card h-100">
            <div class="card-body text-center">
                <p class="small fc-text-secondary mb-1">Mensagens</p>
                <p class="h4 fw-bold fc-text-primary mb-0">{{ $messages }}</p>
            </div>
        </div>
    </div>
</div>

<div class="card fc-card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold fc-text-primary">Últimos usuários cadastrados</h5>
        <a href="{{ route('admin.users') }}" class="btn btn-sm btn-success">Ver todos</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-dark table-hover mb-0">
                <thead>
                    <tr>
                        <th class="border-0 ps-4">Nome / E-mail</th>
                        <th class="border-0">Tipo</th>
                        <th class="border-0">Cadastro</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentUsers as $u)
                        <tr>
                            <td class="ps-4">
                                <span class="fw-semibold fc-text-primary">{{ $u->full_name ?: $u->email }}</span>
                                @if($u->full_name)
                                    <br><span class="small fc-text-secondary">{{ $u->email }}</span>
                                @endif
                            </td>
                            <td>
                                @if($u->role === 'player')
                                    <span class="badge bg-success">Jogador</span>
                                @else
                                    <span class="badge bg-info">Profissional</span>
                                @endif
                            </td>
                            <td class="small fc-text-secondary">{{ $u->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center py-4 fc-text-secondary">Nenhum usuário ainda.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

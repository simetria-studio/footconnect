@extends('admin.layout')

@section('title', 'Saques PIX')
@section('page-title', 'Saques PIX')
@section('page-subtitle', 'Histórico de pagamentos a afiliados')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card fc-card fc-stat-card"><div class="card-body">
            <p class="stat-label">Total pago</p>
            <p class="stat-value text-success mb-0">R$ {{ number_format($totals['completed'] / 100, 2, ',', '.') }}</p>
        </div></div>
    </div>
    <div class="col-md-4">
        <div class="card fc-card fc-stat-card"><div class="card-body">
            <p class="stat-label">Pendente</p>
            <p class="stat-value text-warning mb-0">R$ {{ number_format($totals['pending'] / 100, 2, ',', '.') }}</p>
        </div></div>
    </div>
    <div class="col-md-4">
        <div class="card fc-card fc-stat-card"><div class="card-body">
            <p class="stat-label">Total de saques</p>
            <p class="stat-value fc-text-primary mb-0">{{ $totals['count'] }}</p>
        </div></div>
    </div>
</div>

<div class="card fc-card mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label small">Status</label>
                <select name="status" class="form-select form-select-sm bg-dark border-secondary text-white">
                    <option value="">Todos</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Pago</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pendente</option>
                    <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Falhou</option>
                </select>
            </div>
            <div class="col-md-2"><button class="btn btn-sm btn-success">Filtrar</button></div>
        </form>
    </div>
</div>

<div class="card fc-card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-dark table-hover mb-0">
                <thead>
                    <tr>
                        <th class="ps-3">Data</th>
                        <th>Usuário</th>
                        <th>Valor</th>
                        <th>PIX</th>
                        <th>Tipo</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($withdrawals as $w)
                        <tr>
                            <td class="ps-3 small">{{ ($w->processed_at ?? $w->created_at)->format('d/m/Y H:i') }}</td>
                            <td>
                                @if($w->user)
                                    <a href="{{ route('admin.users.show', $w->user) }}">{{ $w->user->full_name ?? $w->user->email }}</a>
                                @else — @endif
                            </td>
                            <td class="text-success fw-semibold">{{ $w->formatted_amount }}</td>
                            <td class="small"><code>{{ $w->pix_key }}</code></td>
                            <td class="small">{{ $w->pix_key_type }}</td>
                            <td>
                                <span class="badge {{ $w->status === 'completed' ? 'bg-success' : 'bg-secondary' }}">{{ $w->status_label }}</span>
                                @if($w->is_automatic)<br><span class="small fc-text-secondary">Automático</span>@endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center py-4 fc-text-secondary">Nenhum saque encontrado.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($withdrawals->hasPages())<div class="card-footer">{{ $withdrawals->links() }}</div>@endif
</div>
@endsection

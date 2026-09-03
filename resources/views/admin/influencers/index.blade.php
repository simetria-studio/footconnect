@extends('admin.layout')

@section('title', 'Influenciadores')
@section('page-title', 'Influenciadores')
@section('page-subtitle', 'Cadastro exclusivo pelo admin — sem plano, com PIX e link de indicação')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <p class="small fc-text-secondary mb-0">Contas cortesia para divulgação. Recebem comissão via PIX sem pagar assinatura.</p>
    <a href="{{ route('admin.influencers.create') }}" class="btn btn-success btn-sm">Novo influenciador</a>
</div>

<div class="card fc-card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.influencers.index') }}" class="row g-3 align-items-end">
            <div class="col-12 col-md-6 col-lg-4">
                <label class="form-label small fc-text-secondary">Buscar</label>
                <input type="text" class="form-control bg-dark border-secondary text-white" name="q" value="{{ request('q') }}" placeholder="Nome, e-mail, código ou PIX...">
            </div>
            <div class="col-12 col-md-6 col-lg-4 d-flex gap-2">
                <button type="submit" class="btn btn-success btn-sm">Filtrar</button>
                <a href="{{ route('admin.influencers.index') }}" class="btn btn-outline-secondary btn-sm">Limpar</a>
            </div>
        </form>
    </div>
</div>

<div class="card fc-card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-dark table-hover mb-0">
                <thead>
                    <tr>
                        <th class="border-0 ps-4">Influenciador</th>
                        <th class="border-0">Código / Link</th>
                        <th class="border-0">PIX</th>
                        <th class="border-0">Indicados</th>
                        <th class="border-0">Conta</th>
                        <th class="border-0">Cadastro</th>
                        <th class="border-0 pe-4 text-end">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($influencers as $u)
                        <tr class="{{ ! $u->isActive() ? 'opacity-75' : '' }}">
                            <td class="ps-4">
                                <a href="{{ route('admin.influencers.show', $u) }}" class="fw-semibold fc-text-primary text-decoration-none">{{ $u->full_name ?: $u->name }}</a>
                                <br><span class="small fc-text-secondary">{{ $u->email }}</span>
                            </td>
                            <td>
                                @if($u->referral_code)
                                    <code>{{ $u->referral_code }}</code>
                                @else
                                    <span class="fc-text-secondary">—</span>
                                @endif
                            </td>
                            <td class="small">
                                @if($u->pix_key)
                                    <span class="text-uppercase">{{ $u->pix_key_type }}</span><br>
                                    <span class="fc-text-secondary">{{ $u->pix_key }}</span>
                                @else
                                    <span class="text-warning">Sem PIX</span>
                                @endif
                            </td>
                            <td>{{ $u->referrals_count ?? 0 }}</td>
                            <td>
                                @if($u->isActive())
                                    <span class="badge bg-success">Ativa</span>
                                @else
                                    <span class="badge bg-secondary">Inativa</span>
                                @endif
                            </td>
                            <td class="small fc-text-secondary">{{ $u->created_at->format('d/m/Y') }}</td>
                            <td class="pe-4 text-end">
                                <a href="{{ route('admin.influencers.show', $u) }}" class="btn btn-sm btn-outline-success">Detalhes</a>
                                <a href="{{ route('admin.influencers.edit', $u) }}" class="btn btn-sm btn-outline-secondary">Editar</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 fc-text-secondary">Nenhum influenciador cadastrado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($influencers->hasPages())
        <div class="card-footer fc-border">{{ $influencers->links() }}</div>
    @endif
</div>
@endsection

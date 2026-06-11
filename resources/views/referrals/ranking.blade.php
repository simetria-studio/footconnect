@extends('layouts.app')

@section('title', 'Ranking de Afiliados — FootConnect')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-8">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h1 class="h5 fw-bold fc-text-primary mb-1">Ranking oficial de afiliados</h1>
                    <p class="small fc-text-secondary mb-0">Apenas indicações válidas entram no ranking.</p>
                </div>
                <a href="{{ route('referrals.index') }}" class="btn btn-sm btn-outline-secondary">Voltar</a>
            </div>

            @if($user && $userRank)
                <div class="alert alert-success mb-4">
                    Sua posição atual: <strong>#{{ $userRank }}</strong>
                </div>
            @endif

            <div class="card fc-card">
                <div class="card-body p-0">
                    @forelse($ranking as $index => $entry)
                        <div class="d-flex align-items-center justify-content-between px-3 py-3 border-bottom fc-border {{ $user && $entry->id === $user->id ? 'bg-success bg-opacity-10' : '' }}">
                            <div class="d-flex align-items-center gap-3">
                                <span class="badge {{ $index < 3 ? 'bg-warning text-dark' : 'bg-secondary' }} rounded-pill" style="min-width: 2rem;">
                                    {{ $index + 1 }}º
                                </span>
                                <div>
                                    <p class="mb-0 fw-semibold fc-text-primary">{{ $entry->full_name ?? $entry->name }}</p>
                                    <p class="mb-0 small fc-text-secondary">{{ $entry->referral_code }}</p>
                                </div>
                            </div>
                            <div class="text-end">
                                <p class="mb-0 small fw-bold text-success">
                                    R$ {{ number_format(($entry->total_commission_cents ?? 0) / 100, 2, ',', '.') }}
                                </p>
                                <p class="mb-0 small fc-text-secondary">{{ $entry->valid_referrals_count }} indicados</p>
                            </div>
                        </div>
                    @empty
                        <p class="small fc-text-secondary p-4 mb-0 text-center">Nenhum afiliado no ranking ainda.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Mensagens — FootConnect')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-8">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h1 class="h5 fw-bold fc-text-primary mb-0">Mensagens</h1>
                <a href="{{ route('home') }}" class="btn btn-sm btn-outline-secondary">Home</a>
            </div>

            @forelse($conversations as $conversation)
                @php
                    $other = $conversation->user_one_id === $user->id ? $conversation->userTwo : $conversation->userOne;
                    $last = $conversation->messages->first();
                @endphp
                <a href="{{ route('messages.show', $conversation) }}" class="text-decoration-none">
                    <div class="card fc-card fc-card-hover mb-3">
                        <div class="card-body">
                            <div class="d-flex align-items-center gap-3">
                                <div class="fc-avatar-sm bg-success flex-shrink-0">
                                    {{ strtoupper(substr($other->full_name ?? $other->email, 0, 2)) }}
                                </div>
                                <div class="flex-grow-1 min-w-0">
                                    <h6 class="fw-bold fc-text-primary mb-1">{{ $other->full_name ?? $other->email }}</h6>
                                    @if($last)
                                        <p class="small fc-text-secondary mb-0 text-truncate">{{ $last->body }}</p>
                                    @else
                                        <p class="small text-muted mb-0">Nenhuma mensagem ainda.</p>
                                    @endif
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" class="text-muted flex-shrink-0">
                                    <path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </a>
            @empty
                <div class="card fc-card">
                    <div class="card-body text-center py-5">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" viewBox="0 0 16 16" class="text-muted mb-3">
                            <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1zm13 2.383-4.708 2.825L15 11.105zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741M1 11.105l4.708-2.897L1 5.383z"/>
                        </svg>
                        <p class="fc-text-secondary mb-0">Você ainda não tem conversas.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

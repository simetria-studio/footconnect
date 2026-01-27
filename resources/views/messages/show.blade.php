@extends('layouts.app')

@section('title', 'Conversas — FootConnect')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-8">
            @php
                $other = $conversation->user_one_id === $user->id ? $conversation->userTwo : $conversation->userOne;
            @endphp

            <div class="d-flex align-items-center justify-content-between mb-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="fc-avatar bg-success">
                        {{ strtoupper(substr($other->full_name ?? $other->email, 0, 2)) }}
                    </div>
                    <div>
                        <p class="small text-muted mb-0">Conversando com</p>
                        <h1 class="h6 fw-bold fc-text-primary mb-0">{{ $other->full_name ?? $other->email }}</h1>
                    </div>
                </div>
                <a href="{{ route('messages.index') }}" class="btn btn-sm btn-outline-secondary">Voltar</a>
            </div>

            <!-- Área de Mensagens -->
            <div class="card fc-card mb-3" style="height: 400px; overflow-y: auto;">
                <div class="card-body">
                    <div class="d-flex flex-column gap-3">
                        @forelse($conversation->messages->sortByDesc('created_at')->reverse() as $message)
                            <div class="d-flex {{ $message->sender_id === $user->id ? 'justify-content-end' : 'justify-content-start' }}">
                                <div class="message-bubble {{ $message->sender_id === $user->id ? 'bg-success text-dark' : 'bg-secondary fc-text-primary' }}" style="max-width: 75%; padding: 0.75rem 1rem; border-radius: 16px;">
                                    <p class="mb-0 small">{{ $message->body }}</p>
                                    <p class="mb-0 mt-1" style="font-size: 0.625rem; opacity: 0.7;">
                                        {{ $message->created_at->format('d/m H:i') }}
                                    </p>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5">
                                <p class="fc-text-secondary mb-0">Nenhuma mensagem ainda. Envie a primeira!</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Formulário de Enviar Mensagem -->
            <form method="POST" action="{{ route('messages.store', $conversation) }}" class="card fc-card">
                <div class="card-body">
                    @csrf
                    <div class="input-group">
                        <textarea 
                            name="body" 
                            class="form-control" 
                            rows="2" 
                            placeholder="Escreva sua mensagem..." 
                            required
                            style="resize: none;"
                        ></textarea>
                        <button type="submit" class="btn btn-success">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M15.854.146a.5.5 0 0 1 .11.54l-5.819 14.547a.75.75 0 0 1-1.329.124l-3.178-4.995L.643 7.184a.75.75 0 0 1 .124-1.33L15.314.037a.5.5 0 0 1 .54.11ZM6.636 10.07l2.761 4.338L14.13 2.576zm1.12-4.44L13.713 2.3l-5.006 5.006z"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
    .message-bubble {
        word-wrap: break-word;
    }
</style>
@endpush
@endsection

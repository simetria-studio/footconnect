@extends('layouts.app')

@section('title', 'Conta desativada — FootConnect')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6 text-center py-5">
            <h1 class="h4 fw-bold fc-text-primary mb-3">Conta desativada</h1>
            <p class="fc-text-secondary mb-4">
                Sua conta foi desativada. Isso pode ter sido feito por você ou por um administrador.
                Se precisar esclarecer ou reativar o acesso, entre em contato com o suporte do FootConnect.
            </p>
            @if($errors->has('account'))
                <div class="alert alert-warning mb-4">{{ $errors->first('account') }}</div>
            @endif
            <a href="{{ route('landing') }}" class="btn btn-outline-secondary">Voltar ao início</a>
        </div>
    </div>
</div>
@endsection

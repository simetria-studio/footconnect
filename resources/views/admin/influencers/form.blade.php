@php
    $isEdit = $influencer->exists;
@endphp

@extends('admin.layout')

@section('title', $isEdit ? 'Editar influenciador' : 'Novo influenciador')
@section('page-title', $isEdit ? 'Editar influenciador' : 'Novo influenciador')
@section('page-subtitle', 'Conta cortesia: login, senha, código de indicação e PIX para comissões')

@section('content')
<div class="mb-3">
    <a href="{{ $isEdit ? route('admin.influencers.show', $influencer) : route('admin.influencers.index') }}" class="btn btn-sm btn-outline-secondary">Voltar</a>
</div>

<div class="card fc-card">
    <div class="card-body">
        <form method="POST"
              action="{{ $isEdit ? route('admin.influencers.update', $influencer) : route('admin.influencers.store') }}"
              class="row g-3">
            @csrf
            @if($isEdit)
                @method('PUT')
            @endif

            <div class="col-12">
                <h6 class="fw-bold mb-0">Dados de acesso</h6>
                <p class="small fc-text-secondary mb-0">O influenciador entra no app com e-mail e senha. Não precisa escolher nem pagar plano.</p>
            </div>

            <div class="col-12 col-md-6">
                <label class="form-label" for="full_name">Nome completo</label>
                <input type="text" class="form-control bg-dark border-secondary text-white" id="full_name" name="full_name" value="{{ old('full_name', $influencer->full_name) }}" required maxlength="255">
            </div>

            <div class="col-12 col-md-6">
                <label class="form-label" for="email">Login (e-mail)</label>
                <input type="email" class="form-control bg-dark border-secondary text-white" id="email" name="email" value="{{ old('email', $influencer->email) }}" required maxlength="255">
            </div>

            @unless($isEdit)
                <div class="col-12">
                    <div class="form-check">
                        <input type="hidden" name="generate_password" value="0">
                        <input class="form-check-input" type="checkbox" value="1" id="generate_password" name="generate_password" {{ old('generate_password', '1') == '1' ? 'checked' : '' }}>
                        <label class="form-check-label" for="generate_password">Gerar senha automaticamente</label>
                    </div>
                </div>
                <div class="col-12 col-md-6" id="password-field">
                    <label class="form-label" for="password">Senha (opcional)</label>
                    <input type="text" class="form-control bg-dark border-secondary text-white" id="password" name="password" value="{{ old('password') }}" minlength="8" autocomplete="new-password">
                    <div class="form-text">Deixe em branco para gerar automaticamente. A senha aparece só uma vez após salvar.</div>
                </div>
            @endunless

            <div class="col-12 col-md-6">
                <label class="form-label" for="referral_code">Código de indicação</label>
                <input type="text" class="form-control bg-dark border-secondary text-white text-uppercase" id="referral_code" name="referral_code" value="{{ old('referral_code', $influencer->referral_code) }}" maxlength="16" placeholder="FOOT23">
                <div class="form-text">Opcional. Deve começar com FOOT (ex: FOOTANA). Se vazio, geramos um código.</div>
            </div>

            <div class="col-12 pt-2">
                <h6 class="fw-bold mb-0">PIX para comissões</h6>
                <p class="small fc-text-secondary mb-0">Obrigatório. Usado nos saques automáticos do Indique e Ganhe.</p>
            </div>

            <div class="col-12 col-md-4">
                <label class="form-label" for="pix_key_type">Tipo de chave</label>
                <select class="form-select bg-dark border-secondary text-white" id="pix_key_type" name="pix_key_type" required>
                    <option value="">Selecione</option>
                    @foreach(config('referrals.pix_key_types') as $value => $label)
                        <option value="{{ $value }}" {{ old('pix_key_type', $influencer->pix_key_type) === $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-12 col-md-8">
                <label class="form-label" for="pix_key">Chave PIX</label>
                <input type="text" class="form-control bg-dark border-secondary text-white" id="pix_key" name="pix_key" value="{{ old('pix_key', $influencer->pix_key) }}" required maxlength="255" placeholder="CPF, e-mail, telefone ou chave aleatória">
            </div>

            <div class="col-12 col-md-4">
                <label class="form-label" for="city">Cidade</label>
                <input type="text" class="form-control bg-dark border-secondary text-white" id="city" name="city" value="{{ old('city', $influencer->city) }}">
            </div>
            <div class="col-12 col-md-4">
                <label class="form-label" for="state">Estado</label>
                <select class="form-select bg-dark border-secondary text-white" id="state" name="state">
                    <option value="">Selecione</option>
                    @foreach(config('locations.brazilian_states') as $uf => $name)
                        <option value="{{ $uf }}" {{ old('state', $influencer->state) === $uf ? 'selected' : '' }}>{{ $uf }} — {{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-4">
                <label class="form-label" for="country">País</label>
                <select class="form-select bg-dark border-secondary text-white" id="country" name="country">
                    @foreach(config('locations.countries') as $country)
                        <option value="{{ $country }}" {{ old('country', $influencer->country ?? 'Brasil') === $country ? 'selected' : '' }}>{{ $country }}</option>
                    @endforeach
                </select>
            </div>

            @unless($isEdit)
                <div class="col-12">
                    <div class="form-check">
                        <input type="hidden" name="send_credentials" value="0">
                        <input class="form-check-input" type="checkbox" value="1" id="send_credentials" name="send_credentials" {{ old('send_credentials', '1') == '1' ? 'checked' : '' }}>
                        <label class="form-check-label" for="send_credentials">Enviar login, senha e link por e-mail</label>
                    </div>
                </div>
            @endunless

            <div class="col-12">
                <button type="submit" class="btn btn-success">{{ $isEdit ? 'Salvar alterações' : 'Cadastrar influenciador' }}</button>
            </div>
        </form>
    </div>
</div>
@endsection

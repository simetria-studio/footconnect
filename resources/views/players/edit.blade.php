@extends('layouts.app')

@section('title', 'Editar perfil de atleta — FootConnect')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-8">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h1 class="h5 fw-bold fc-text-primary mb-1">Perfil G1 — Atleta (Jogador)</h1>
                    <p class="small fc-text-secondary mb-0">
                        Preencha suas informações para aumentar suas chances de ser encontrado por profissionais.
                    </p>
                </div>
                <a href="{{ route('home') }}" class="btn btn-sm btn-outline-secondary">Voltar</a>
            </div>

            @if (session('status'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('status') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form method="POST" action="{{ route('me.player-profile.update') }}" class="card fc-card">
                <div class="card-body">
                    @csrf

                    <div class="row g-3">
                        <div class="col-12">
                            <label for="full_name" class="form-label">Nome completo</label>
                            <input type="text" class="form-control" id="full_name" name="full_name" value="{{ old('full_name', $user->full_name) }}" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Modalidade</label>
                            <div class="d-flex flex-wrap gap-3">
                                @foreach(['campo' => 'Futebol de Campo', 'futsal' => 'Futsal', 'fut7' => 'Fut 7'] as $value => $label)
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="modality" id="modality_{{ $value }}" value="{{ $value }}" {{ old('modality', $profile->modality) === $value ? 'checked' : '' }}>
                                        <label class="form-check-label" for="modality_{{ $value }}">{{ $label }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Sexo</label>
                            <div class="d-flex gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="gender" id="gender_male" value="male" {{ old('gender', $profile->gender) === 'male' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="gender_male">Masculino</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="gender" id="gender_female" value="female" {{ old('gender', $profile->gender) === 'female' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="gender_female">Feminino</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="birth_date" class="form-label">Data de nascimento</label>
                            <input type="date" class="form-control" id="birth_date" name="birth_date" value="{{ old('birth_date', $profile->birth_date?->format('Y-m-d')) }}">
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="age" class="form-label">Idade</label>
                            <input type="number" class="form-control" id="age" name="age" value="{{ old('age', $profile->age) }}" min="10" max="60">
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="height_cm" class="form-label">Altura (cm)</label>
                            <input type="number" class="form-control" id="height_cm" name="height_cm" value="{{ old('height_cm', $profile->height_cm) }}" min="100" max="250">
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="position" class="form-label">Posição</label>
                            <input type="text" class="form-control" id="position" name="position" value="{{ old('position', $profile->position) }}" placeholder="Ex: Atacante">
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="dominant_foot" class="form-label">Pé dominante</label>
                            <select class="form-select" id="dominant_foot" name="dominant_foot">
                                <option value="">Selecione</option>
                                <option value="right" {{ old('dominant_foot', $profile->dominant_foot) === 'right' ? 'selected' : '' }}>Destro</option>
                                <option value="left" {{ old('dominant_foot', $profile->dominant_foot) === 'left' ? 'selected' : '' }}>Canhoto</option>
                                <option value="both" {{ old('dominant_foot', $profile->dominant_foot) === 'both' ? 'selected' : '' }}>Ambidestro</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label for="characteristics" class="form-label">Características</label>
                            <textarea class="form-control" id="characteristics" name="characteristics" rows="4" placeholder="Descreva suas características técnicas, físicas e comportamentais...">{{ old('characteristics', $profile->characteristics ?? $profile->bio) }}</textarea>
                        </div>

                        <x-yes-no-field
                            name="is_student"
                            label="Estudante"
                            :value="$profile->is_student"
                            detail-name="school_name"
                            detail-label="Qual escola?"
                            :detail-value="$profile->school_name"
                        />

                        <div class="col-12 yes-no-detail {{ old('is_student', $profile->is_student ? '1' : '0') === '1' ? '' : 'd-none' }}" data-student-grade>
                            <label for="school_grade" class="form-label small">Que série?</label>
                            <input type="text" class="form-control" id="school_grade" name="school_grade" value="{{ old('school_grade', $profile->school_grade) }}" placeholder="Ex: 2º ano do Ensino Médio">
                        </div>

                        <div class="col-12 col-md-4">
                            <label for="city" class="form-label">Cidade</label>
                            <input type="text" class="form-control" id="city" name="city" value="{{ old('city', $profile->city ?? $user->city) }}">
                        </div>

                        <div class="col-12 col-md-4">
                            <label for="state" class="form-label">Estado</label>
                            <select class="form-select" id="state" name="state">
                                <option value="">Selecione</option>
                                @foreach(config('locations.brazilian_states') as $uf => $name)
                                    <option value="{{ $uf }}" {{ old('state', $profile->state ?? $user->state) === $uf ? 'selected' : '' }}>{{ $uf }} — {{ $name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 col-md-4">
                            <label for="country" class="form-label">País</label>
                            <select class="form-select" id="country" name="country">
                                <option value="">Selecione</option>
                                @foreach(config('locations.countries') as $country)
                                    <option value="{{ $country }}" {{ old('country', $profile->country ?? $user->country ?? 'Brasil') === $country ? 'selected' : '' }}>{{ $country }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Instituição</label>
                            <div class="d-flex flex-wrap gap-3 mb-2">
                                @foreach(['clube' => 'Clube', 'projeto' => 'Projeto', 'escolinha' => 'Escolinha'] as $value => $label)
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="institution_type" id="institution_{{ $value }}" value="{{ $value }}" {{ old('institution_type', $profile->institution_type) === $value ? 'checked' : '' }}>
                                        <label class="form-check-label" for="institution_{{ $value }}">{{ $label }}</label>
                                    </div>
                                @endforeach
                            </div>
                            <input type="text" class="form-control" id="institution_name" name="institution_name" value="{{ old('institution_name', $profile->institution_name ?? $profile->current_club) }}" placeholder="Qual? (nome do clube, projeto ou escolinha)">
                        </div>

                        <x-yes-no-field
                            name="is_federated"
                            label="Federado"
                            :value="$profile->is_federated"
                        />

                        <x-yes-no-field
                            name="has_awards"
                            label="Premiações"
                            :value="$profile->has_awards"
                            detail-name="awards_description"
                            detail-label="Qual premiação?"
                            :detail-value="$profile->awards_description"
                        />
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

                    <div class="mt-4">
                        <button type="submit" class="btn btn-success w-100">Salvar perfil</button>
                    </div>
                </div>
            </form>

            <div class="card fc-card mt-4">
                <div class="card-body">
                    <h2 class="h6 fw-bold fc-text-primary mb-2">Fotos e vídeos</h2>
                    <p class="small fc-text-secondary mb-3">
                        Adicione fotos e vídeos para destacar seu talento no perfil público.
                    </p>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('me.player-photos') }}" class="btn btn-outline-success btn-sm">Gerenciar fotos</a>
                        <a href="{{ route('me.player-videos') }}" class="btn btn-outline-success btn-sm">Gerenciar vídeos</a>
                        <a href="{{ route('me.player-stats') }}" class="btn btn-outline-success btn-sm">Estatísticas</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<x-profile-form-scripts />
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const studentField = document.querySelector('[name="is_student"]')?.closest('[data-yes-no-field]');
        const gradeField = document.querySelector('[data-student-grade]');
        if (!studentField || !gradeField) return;

        function toggleGrade() {
            const isYes = studentField.querySelector('.yes-no-radio[value="1"]')?.checked;
            gradeField.classList.toggle('d-none', !isYes);
        }

        studentField.querySelectorAll('.yes-no-radio').forEach(function (radio) {
            radio.addEventListener('change', toggleGrade);
        });
        toggleGrade();
    });
</script>
@endsection

@extends('admin.layout')

@section('title', $highlight->exists ? 'Editar destaque' : 'Novo destaque')
@section('page-title', $highlight->exists ? 'Editar destaque do mês' : 'Novo destaque do mês')
@section('page-subtitle', 'O admin só escolhe o usuário. Foto, vídeo e história vêm do perfil dele.')

@section('content')
<div class="mb-3">
    <a href="{{ route('admin.highlights.index') }}" class="btn btn-sm btn-outline-secondary">Voltar</a>
</div>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0 small">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card fc-card">
    <div class="card-body">
        <form method="POST"
              action="{{ $highlight->exists ? route('admin.highlights.update', $highlight) : route('admin.highlights.store') }}"
              class="row g-3"
              id="highlight-form">
            @csrf
            @if($highlight->exists)
                @method('PUT')
            @endif

            <div class="col-12">
                <div class="alert alert-secondary py-2 mb-0">
                    Crie o destaque selecionando a categoria e o usuário. A landing usa automaticamente a foto, o vídeo e a história do perfil desse usuário.
                </div>
            </div>

            <div class="col-md-4">
                <label class="form-label" for="category">Categoria</label>
                <select class="form-select bg-dark border-secondary text-white" id="category" name="category" required>
                    @foreach(\App\Models\MonthlyHighlight::categoryOptions() as $value => $label)
                        <option value="{{ $value }}" {{ old('category', $highlight->category) === $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-8">
                <label class="form-label" for="user_id">Usuário</label>
                <select class="form-select bg-dark border-secondary text-white" id="user_id" name="user_id" required>
                    <option value="">Selecione um usuário</option>
                    @foreach($users as $user)
                        @php
                            $category = \App\Models\MonthlyHighlight::categoryFromPlanGroup($user->plan_group);
                            $label = ($user->full_name ?: $user->name ?: 'Sem nome').' — '.$user->email.' ('.strtoupper($user->plan_group ?? '-').')';
                        @endphp
                        <option
                            value="{{ $user->id }}"
                            data-category="{{ $category }}"
                            {{ (int) old('user_id', $highlight->user_id) === $user->id ? 'selected' : '' }}
                        >{{ $label }}</option>
                    @endforeach
                </select>
                <div class="form-text">A lista muda conforme a categoria (G1 jogador, G2 empresário, G3 treinador).</div>
            </div>

            <div class="col-md-4">
                <label class="form-label" for="month">Mês</label>
                <select class="form-select bg-dark border-secondary text-white" id="month" name="month" required>
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ (int) old('month', $highlight->month) === $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                        </option>
                    @endfor
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label" for="year">Ano</label>
                <input type="number" class="form-control bg-dark border-secondary text-white" id="year" name="year" value="{{ old('year', $highlight->year) }}" min="2024" max="2100" required>
            </div>

            <div class="col-md-2">
                <label class="form-label" for="sort_order">Ordem</label>
                <input type="number" min="0" class="form-control bg-dark border-secondary text-white" id="sort_order" name="sort_order" value="{{ old('sort_order', $highlight->sort_order ?? 0) }}">
            </div>

            <div class="col-md-2 d-flex align-items-end">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="1" id="is_active" name="is_active" {{ old('is_active', $highlight->is_active) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">Ativo</label>
                </div>
            </div>

            @if($highlight->exists)
                <div class="col-12">
                    <div class="border rounded p-3" style="border-color: rgba(148,163,184,0.25) !important;">
                        <p class="fw-semibold mb-1">Prévia a partir do perfil</p>
                        <p class="small fc-text-secondary mb-0">
                            Nome: {{ $highlight->displayName() }}
                            @if($highlight->displayStory())
                                · História preenchida no perfil
                            @else
                                · Sem história no perfil ainda
                            @endif
                            @if($highlight->displayPhotoUrl())
                                · Com foto
                            @endif
                            @if($highlight->displayVideoUrl())
                                · Com vídeo
                            @endif
                        </p>
                    </div>
                </div>
            @endif

            <div class="col-12">
                <button type="submit" class="btn btn-success">{{ $highlight->exists ? 'Salvar' : 'Criar destaque' }}</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const category = document.getElementById('category');
        const userSelect = document.getElementById('user_id');
        const options = Array.from(userSelect.querySelectorAll('option[data-category]'));

        function syncUsers() {
            const selectedCategory = category.value;
            const current = userSelect.value;
            let keep = false;

            options.forEach(function (option) {
                const match = option.dataset.category === selectedCategory;
                option.hidden = !match;
                option.disabled = !match;
                if (match && option.value === current) {
                    keep = true;
                }
            });

            if (!keep) {
                userSelect.value = '';
            }
        }

        category.addEventListener('change', syncUsers);
        syncUsers();
    });
</script>
@endpush

@props([
    'name',
    'label',
    'value' => false,
    'detailName' => null,
    'detailLabel' => 'Qual?',
    'detailValue' => '',
    'detailRequired' => false,
])

<div class="col-12 yes-no-field" data-yes-no-field>
    <label class="form-label">{{ $label }}</label>
    <div class="d-flex gap-3 mb-2">
        <div class="form-check">
            <input
                class="form-check-input yes-no-radio"
                type="radio"
                name="{{ $name }}"
                id="{{ $name }}_yes"
                value="1"
                {{ old($name, $value ? '1' : '0') === '1' ? 'checked' : '' }}
            >
            <label class="form-check-label" for="{{ $name }}_yes">Sim</label>
        </div>
        <div class="form-check">
            <input
                class="form-check-input yes-no-radio"
                type="radio"
                name="{{ $name }}"
                id="{{ $name }}_no"
                value="0"
                {{ old($name, $value ? '1' : '0') === '0' ? 'checked' : '' }}
            >
            <label class="form-check-label" for="{{ $name }}_no">Não</label>
        </div>
    </div>

    @if($detailName)
        <div class="yes-no-detail {{ old($name, $value ? '1' : '0') === '1' ? '' : 'd-none' }}">
            <label for="{{ $detailName }}" class="form-label small">{{ $detailLabel }}</label>
            <input
                type="text"
                class="form-control"
                id="{{ $detailName }}"
                name="{{ $detailName }}"
                value="{{ old($detailName, $detailValue) }}"
                @if($detailRequired) data-detail-required="1" @endif
            >
        </div>
    @endif
</div>

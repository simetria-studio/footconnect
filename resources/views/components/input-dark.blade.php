@props(['label' => null, 'type' => 'text', 'name' => '', 'value' => '', 'placeholder' => '', 'required' => false, 'help' => null])

<div class="mb-3">
    @if($label)
        <label for="{{ $name }}" class="form-label">{{ $label }}</label>
    @endif
    
    @if($type === 'textarea')
        <textarea 
            name="{{ $name }}" 
            id="{{ $name }}" 
            class="form-control" 
            placeholder="{{ $placeholder }}"
            {{ $required ? 'required' : '' }}
            {{ $attributes }}
        >{{ old($name, $value) }}</textarea>
    @elseif($type === 'select')
        <select 
            name="{{ $name }}" 
            id="{{ $name }}" 
            class="form-select"
            {{ $required ? 'required' : '' }}
            {{ $attributes }}
        >
            {{ $slot }}
        </select>
    @else
        <input 
            type="{{ $type }}" 
            name="{{ $name }}" 
            id="{{ $name }}" 
            class="form-control" 
            value="{{ old($name, $value) }}"
            placeholder="{{ $placeholder }}"
            {{ $required ? 'required' : '' }}
            {{ $attributes }}
        >
    @endif
    
    @if($help)
        <div class="form-text">{{ $help }}</div>
    @endif
    
    @error($name)
        <div class="text-danger small mt-1">{{ $message }}</div>
    @enderror
</div>

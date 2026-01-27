@props(['type' => 'button', 'size' => 'md', 'outline' => false])

@php
    $sizeClass = match($size) {
        'sm' => 'btn-sm',
        'lg' => 'btn-lg',
        default => ''
    };
    
    $class = $outline ? 'btn-outline-success' : 'btn-success';
@endphp

<button type="{{ $type }}" {{ $attributes->merge(['class' => "btn {$class} {$sizeClass}"]) }}>
    {{ $slot }}
</button>

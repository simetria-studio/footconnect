@props(['name' => '', 'size' => 'md', 'color' => 'green'])

@php
    $initials = strtoupper(substr($name, 0, 2));
    $sizeClass = match($size) {
        'sm' => 'fc-avatar-sm',
        'lg' => 'fc-avatar-lg',
        default => ''
    };
    
    $colorClass = match($color) {
        'amber' => 'bg-warning',
        'blue' => 'bg-primary',
        default => 'bg-success'
    };
@endphp

<div class="fc-avatar {{ $sizeClass }} {{ $colorClass }}">
    {{ $initials }}
</div>

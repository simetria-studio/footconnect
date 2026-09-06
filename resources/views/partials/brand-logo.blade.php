@php
    $height = $height ?? 64;
    $class = $class ?? '';
@endphp
<img
    src="{{ asset('imgs/logo.png') }}"
    alt="FootConnect"
    class="fc-brand-logo {{ $class }}"
    style="height: {{ (int) $height }}px; width: auto; max-width: 100%; display: block; object-fit: contain;"
>

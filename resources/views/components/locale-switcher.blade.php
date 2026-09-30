@php
    $locales = config('locales.supported', []);
    $current = app()->getLocale();
@endphp

<nav class="fc-locale" aria-label="{{ __('ui.locale') }}">
    @foreach ($locales as $code => $meta)
        <a
            href="{{ route('locale.switch', $code) }}"
            class="{{ $current === $code ? 'is-active' : '' }}"
            hreflang="{{ $meta['html'] }}"
            lang="{{ $meta['html'] }}"
            @if ($current === $code) aria-current="true" @endif
        >{{ $meta['short'] }}</a>
    @endforeach
</nav>

<style>
    .fc-locale {
        display: inline-flex;
        align-items: center;
        gap: 2px;
        padding: 2px;
        border-radius: 999px;
        border: 1px solid rgba(148, 163, 184, 0.35);
        background: rgba(2, 6, 23, 0.45);
        flex-shrink: 0;
    }

    .fc-locale a {
        color: #94a3b8;
        text-decoration: none;
        font-size: 0.68rem;
        font-weight: 700;
        letter-spacing: 0.04em;
        line-height: 1;
        padding: 0.35rem 0.45rem;
        border-radius: 999px;
    }

    .fc-locale a:hover {
        color: #f8fafc;
    }

    .fc-locale a.is-active {
        color: #052e16;
        background: #22c55e;
    }
</style>

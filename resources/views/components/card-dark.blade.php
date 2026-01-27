@props(['title' => null, 'subtitle' => null, 'footer' => null, 'hover' => true])

<div class="card fc-card {{ $hover ? 'fc-card-hover' : '' }} mb-3">
    @if($title || $subtitle)
        <div class="card-header fc-card-header">
            @if($title)
                <h5 class="mb-0 fw-bold fc-text-primary">{{ $title }}</h5>
            @endif
            @if($subtitle)
                <p class="mb-0 small fc-text-secondary mt-1">{{ $subtitle }}</p>
            @endif
        </div>
    @endif
    
    <div class="card-body">
        {{ $slot }}
    </div>
    
    @if($footer)
        <div class="card-footer">
            {{ $footer }}
        </div>
    @endif
</div>

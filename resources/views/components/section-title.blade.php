@props(['title', 'subtitle' => null])
<div class="text-center mb-12">
    <h2 class="section-title">{{ $title }}</h2>
    @if($subtitle)
        <p class="section-subtitle">{{ $subtitle }}</p>
    @endif
</div>

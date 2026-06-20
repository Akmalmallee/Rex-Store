@props(['size' => 'md', 'color' => 'text-white', 'class' => ''])
@php
    $sizes = ['sm' => 'w-4 h-4', 'md' => 'w-6 h-6', 'lg' => 'w-8 h-8'];
    $sizeClass = $sizes[$size] ?? $sizes['md'];
@endphp
<svg class="animate-spin {{ $sizeClass }} {{ $color }} {{ $class }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
</svg>

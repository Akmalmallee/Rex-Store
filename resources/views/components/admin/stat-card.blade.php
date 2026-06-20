@props([
    'icon',
    'value',
    'label',
    'trend' => null,
])

<div class="glass-card p-6">
    <div class="flex items-center justify-between mb-4">
        <div class="w-12 h-12 bg-[#C8A951]/10 border border-[#C8A951]/20 flex items-center justify-center">
            {!! $icon !!}
        </div>
        @if($trend)
        <span class="text-[10px] tracking-wider text-[#C8A951] border border-[#C8A951]/30 px-2 py-0.5">{{ $trend }}</span>
        @endif
    </div>
    <p class="text-3xl font-light text-white">{{ $value }}</p>
    <p class="text-xs font-light text-gray-500 mt-1">{{ $label }}</p>
</div>

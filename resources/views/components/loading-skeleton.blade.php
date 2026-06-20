@props(['count' => 4, 'luxury' => false])
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
    @for($i = 0; $i < $count; $i++)
        <div class="{{ $luxury ? 'skeleton-luxury' : 'animate-pulse' }} rounded {{ $luxury ? 'p-4' : '' }}">
            <div class="aspect-[4/5] {{ $luxury ? 'bg-white/5' : 'bg-gray-200 dark:bg-gray-800' }} rounded-xl relative overflow-hidden">
                @if($luxury)
                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/[0.02] to-transparent" style="background-size: 200% 100%; animation: shimmer 2s infinite linear;"></div>
                @endif
            </div>
            <div class="mt-4 space-y-2">
                <div class="h-3 {{ $luxury ? 'bg-white/5' : 'bg-gray-200 dark:bg-gray-800' }} rounded w-1/3 relative overflow-hidden">
                    @if($luxury)<div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/[0.02] to-transparent" style="background-size: 200% 100%; animation: shimmer 2s infinite linear;"></div>@endif
                </div>
                <div class="h-4 {{ $luxury ? 'bg-white/5' : 'bg-gray-200 dark:bg-gray-800' }} rounded w-2/3 relative overflow-hidden">
                    @if($luxury)<div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/[0.02] to-transparent" style="background-size: 200% 100%; animation: shimmer 2s infinite linear;"></div>@endif
                </div>
                <div class="h-4 {{ $luxury ? 'bg-white/5' : 'bg-gray-200 dark:bg-gray-800' }} rounded w-1/2 relative overflow-hidden">
                    @if($luxury)<div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/[0.02] to-transparent" style="background-size: 200% 100%; animation: shimmer 2s infinite linear;"></div>@endif
                </div>
            </div>
        </div>
    @endfor
</div>

@props([
    'product',
    'modelUrl' => null,
])

@php
    $colors = $product->colors ?? collect([]);
    $sizes = $product->sizes ?? collect([]);
    $modelUrl = $modelUrl ?: ($product->productModel ? Storage::url($product->productModel->model_file) : null);
@endphp

<div id="3d-viewer-overlay" class="fixed inset-0 z-50 hidden items-center justify-center bg-[#0a0a0a]/95 backdrop-blur-xl">
    <!-- Canvas Container -->
    <div class="relative w-full h-full">
        <div id="three-canvas" class="w-full h-full" data-model-url="{{ $modelUrl }}"></div>

        <!-- Loading Progress -->
        <div data-3d-progress class="absolute inset-0 flex items-center justify-center hidden">
            <div class="text-center">
                <div class="w-16 h-16 mx-auto mb-4 border border-[#C8A951]/30 flex items-center justify-center">
                    <svg class="w-8 h-8 text-[#C8A951] animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                </div>
                <div class="w-48 h-1 bg-white/5 mx-auto">
                    <div data-3d-progress-fill class="h-full bg-[#C8A951] transition-all duration-300" style="width: 0%"></div>
                </div>
                <p class="text-xs font-light text-gray-500 mt-3">Loading 3D preview...</p>
            </div>
        </div>

        <!-- Error State -->
        <div data-3d-error class="absolute inset-0 flex items-center justify-center hidden">
            <div class="text-center">
                <div class="w-16 h-16 mx-auto mb-4 border border-white/10 flex items-center justify-center">
                    <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <p class="text-sm font-light text-gray-400">3D preview unavailable</p>
                <p class="text-xs font-light text-gray-600 mt-1">The model could not be loaded.</p>
            </div>
        </div>

        <!-- Top Bar -->
        <div class="absolute top-0 left-0 right-0 p-4 lg:p-6 flex items-center justify-between bg-gradient-to-b from-black/60 to-transparent">
            <div class="flex items-center gap-3">
                <span class="text-xs tracking-widest uppercase font-light text-white/60">3D Preview</span>
                <span class="text-xs font-light text-white/40 hidden sm:block">·</span>
                <span class="text-xs font-light text-white/80 hidden sm:block">{{ $product->name }}</span>
            </div>
            <button data-3d-close class="w-10 h-10 border border-white/10 flex items-center justify-center text-gray-500 hover:text-white hover:border-white/30 transition-all duration-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Bottom Controls -->
        <div data-3d-controls class="absolute bottom-0 left-0 right-0 p-4 lg:p-6 bg-gradient-to-t from-black/80 via-black/40 to-transparent opacity-0">
            <div class="flex flex-wrap items-end justify-between gap-4">
                <div class="space-y-3">
                    @if($colors->isNotEmpty())
                    <div>
                        <p class="text-[10px] tracking-widest uppercase font-light text-gray-500 mb-2">Color</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach($colors as $color)
                            <button data-3d-color="{{ $color->color_code ?? '#000' }}"
                                    data-3d-texture=""
                                    class="w-8 h-8 border {{ $loop->first ? 'border-[#C8A951] ring-2 ring-[#C8A951]/30' : 'border-white/10' }} transition-all hover:scale-110"
                                    style="background-color: {{ $color->color_code ?? '#000' }}"
                                    title="{{ $color->color }}"></button>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

                <div class="flex items-center gap-2">
                    <button data-3d-rotate class="w-9 h-9 border border-white/10 flex items-center justify-center text-gray-500 hover:text-[#C8A951] hover:border-[#C8A951]/30 transition-all text-[#C8A951]" title="Auto-rotate">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    </button>
                    <button data-3d-reset class="w-9 h-9 border border-white/10 flex items-center justify-center text-gray-500 hover:text-white hover:border-white/30 transition-all" title="Reset view">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    </button>
                </div>
            </div>

            <p class="text-[10px] font-light text-gray-600 mt-4 text-center">Drag to rotate · Scroll to zoom · Pinch on mobile</p>
        </div>
    </div>
</div>

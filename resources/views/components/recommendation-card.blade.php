@props(['product', 'fitScore' => null, 'reason' => null, 'size' => null])

<div class="group bg-[#111] border border-white/5 hover:border-[#C8A951]/30 transition-all duration-500 overflow-hidden">
    <div class="relative">
        @php
            $image = $product->thumbnail
                ? (str_starts_with($product->thumbnail, 'http') ? $product->thumbnail : Storage::url($product->thumbnail))
                : 'https://picsum.photos/seed/' . $product->id . '/800/1000';
        @endphp
        <a href="{{ route('product.detail', $product->slug) }}" class="block aspect-[4/5] overflow-hidden bg-[#1a1a1a]">
            <img src="{{ $image }}" alt="{{ $product->name }}"
                 class="w-full h-full object-cover group-hover:scale-110 transition-all duration-1000 ease-out"
                 loading="lazy">
        </a>

        @if($fitScore)
        <div class="absolute top-3 right-3 flex items-center gap-1.5 bg-black/80 backdrop-blur-sm px-2.5 py-1.5 border border-[#C8A951]/20">
            <svg class="w-3 h-3 text-[#C8A951]" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
            </svg>
            <span class="text-xs font-light text-[#C8A951]">{{ number_format($fitScore * 100) }}%</span>
        </div>
        @endif

        @if($size)
        <div class="absolute bottom-3 left-3 text-[10px] tracking-widest uppercase bg-black/80 backdrop-blur-sm px-2.5 py-1 text-white/70">
            Size: <span class="text-[#C8A951]">{{ $size }}</span>
        </div>
        @endif
    </div>

    <div class="p-4">
        <a href="{{ route('product.detail', $product->slug) }}">
            <p class="text-[10px] tracking-[0.2em] uppercase text-gray-500 font-light mb-1">{{ $product->category->name ?? 'Kategori' }}</p>
            <h3 class="text-sm font-normal text-white/90 group-hover:text-[#C8A951] transition-colors line-clamp-1">{{ $product->name }}</h3>
        </a>
        <div class="flex items-center gap-2 mt-2">
            @if($product->discount > 0)
                @php $finalPrice = $product->price - ($product->price * $product->discount / 100); @endphp
                <span class="text-sm font-light text-white">Rp {{ number_format($finalPrice, 0, ',', '.') }}</span>
                <span class="text-[10px] text-gray-600 line-through">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
            @else
                <span class="text-sm font-light text-white">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
            @endif
        </div>

        @if($reason)
        <div class="mt-3 pt-3 border-t border-white/5">
            <div class="flex items-start gap-2">
                <svg class="w-3.5 h-3.5 text-[#C8A951] mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-xs font-light text-gray-400 leading-relaxed">{{ $reason }}</p>
            </div>
        </div>
        @endif

        <div class="mt-4">
            <button onclick="addToCart({{ $product->id }}, '{{ $size ?? '' }}', null, 1, this)"
                    class="w-full py-2.5 border border-white/10 text-white/60 text-[10px] tracking-widest uppercase font-light
                           hover:border-[#C8A951] hover:text-[#C8A951] transition-all duration-300
                           flex items-center justify-center gap-2">
                <span>+ Try This Fit</span>
            </button>
        </div>
    </div>
</div>

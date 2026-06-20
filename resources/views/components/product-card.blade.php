@props(['product'])
<div class="group product-card bg-transparent">
    <a href="{{ route('product.detail', $product->slug) }}" class="block">
        <div class="relative aspect-[4/5] overflow-hidden bg-[#111]">
            @php
                $isLocal = $product->thumbnail && !str_starts_with($product->thumbnail, 'http');
                $image = $product->thumbnail
                    ? ($isLocal ? Storage::url($product->thumbnail) : $product->thumbnail)
                    : 'https://picsum.photos/seed/default-' . $product->id . '/800/1000';
                if ($isLocal) {
                    $image .= '?t=' . $product->updated_at->timestamp;
                }
            @endphp
            <img src="{{ $image }}" alt="{{ $product->name }}"
                 class="w-full h-full object-cover transition-all duration-1000 cubic-bezier(0.25, 0.1, 0.25, 1) group-hover:scale-110"
                 loading="lazy">

            @if($product->discount > 0)
                <div class="absolute top-3 right-3 text-[10px] tracking-widest uppercase bg-black/80 text-white backdrop-blur-sm px-3 py-1">
                    -{{ number_format($product->discount) }}%
                </div>
            @endif

            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/30 transition-colors duration-500"></div>

            <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-500 translate-y-2 group-hover:translate-y-0">
                <span class="text-[10px] tracking-widest uppercase text-white border-b border-white/30 pb-0.5 font-light">Quick View</span>
            </div>
        </div>
        <div class="pt-4 pb-0 px-0 card-info">
            <p class="text-[10px] tracking-[0.2em] uppercase text-gray-500 font-light mb-1">{{ $product->category->name ?? 'Kategori' }}</p>
            <h3 class="text-sm font-normal text-white/90 group-hover:text-[#C8A951] transition-colors duration-300 line-clamp-1">{{ $product->name }}</h3>
            <div class="flex items-center gap-2 mt-1.5">
                @if($product->discount > 0)
                    @php
                        $finalPrice = $product->price - ($product->price * $product->discount / 100);
                    @endphp
                    <span class="text-sm font-light text-white">Rp {{ number_format($finalPrice, 0, ',', '.') }}</span>
                    <span class="text-[10px] text-gray-600 line-through">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                @else
                    <span class="text-sm font-light text-white">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                @endif
            </div>
            <div class="flex items-center gap-1 mt-2">
                @for($i = 1; $i <= 5; $i++)
                    <svg class="w-3 h-3 {{ $i <= round($product->rating) ? 'text-[#C8A951]' : 'text-gray-700' }}" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                @endfor
                <span class="text-[10px] text-gray-600 ml-1 font-light">({{ $product->reviews_count ?? $product->reviews->count() ?? 0 }})</span>
            </div>
        </div>
    </a>
    <div class="pt-3 px-0 card-cta">
        <button onclick="addToCart({{ $product->id }}, null, null, 1, this)"
                class="w-full py-2.5 border border-white/10 text-white/60 text-[10px] tracking-widest uppercase font-light
                       hover:border-[#C8A951] hover:text-[#C8A951] transition-all duration-300
                       disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2">
            <span class="btn-text">+ Add to Cart</span>
        </button>
    </div>
</div>

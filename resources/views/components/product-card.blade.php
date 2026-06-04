@props(['product'])
<div class="card-product group">
    <a href="{{ route('product.detail', $product->slug) }}" class="block">
        <div class="relative aspect-[4/5] overflow-hidden bg-gray-100 dark:bg-gray-800">
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
                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
                 loading="lazy">

            @if($product->discount > 0)
                <div class="absolute top-3 left-3 bg-red-500 text-white text-xs font-bold px-3 py-1 rounded-full">
                    -{{ number_format($product->discount) }}%
                </div>
            @endif

            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors duration-300"></div>
        </div>
        <div class="p-4">
            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">{{ $product->category->name ?? 'Kategori' }}</p>
            <h3 class="font-medium text-sm mb-1 line-clamp-1 group-hover:text-[#C8A951] transition-colors">{{ $product->name }}</h3>
            <div class="flex items-center gap-2">
                @if($product->discount > 0)
                    @php
                        $finalPrice = $product->price - ($product->price * $product->discount / 100);
                    @endphp
                    <span class="font-semibold text-sm">Rp {{ number_format($finalPrice, 0, ',', '.') }}</span>
                    <span class="text-xs text-gray-400 line-through">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                @else
                    <span class="font-semibold text-sm">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                @endif
            </div>
            <div class="flex items-center gap-1 mt-2">
                @for($i = 1; $i <= 5; $i++)
                    <svg class="w-3.5 h-3.5 {{ $i <= round($product->rating) ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-600' }}" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                @endfor
                <span class="text-xs text-gray-400 ml-1">({{ $product->reviews_count ?? $product->reviews->count() ?? 0 }})</span>
            </div>
        </div>
    </a>
    <div class="px-4 pb-4">
        <button onclick="addToCart({{ $product->id }})"
                class="w-full py-2.5 bg-gray-900 hover:bg-black dark:bg-white dark:hover:bg-gray-200 text-white dark:text-gray-900 text-sm font-medium rounded-lg transition-all duration-300 hover:scale-[1.02]">
            + Add to Cart
        </button>
    </div>
</div>

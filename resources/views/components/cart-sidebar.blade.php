@php
    $cart = auth()->check() ? auth()->user()->cart : null;
@endphp

@if($cart && $cart->items->isNotEmpty())
    <div class="flex flex-col h-full">
        <!-- Header -->
        <div class="border-b border-gray-200 dark:border-gray-700 p-4 flex items-center justify-between">
            <div>
                <h2 class="font-bold text-lg">Keranjang</h2>
                <p class="text-xs text-gray-500">{{ $cart->items->sum('quantity') }} item</p>
            </div>
            <button onclick="toggleCart()" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Items List -->
        <div class="flex-1 overflow-y-auto">
            <div class="space-y-3 p-4" id="cart-items-list">
                @foreach($cart->items as $item)
                <div class="flex gap-3 pb-3 border-b border-gray-100 dark:border-gray-800" id="cart-item-{{ $item->id }}">
                    <!-- Product Image -->
                    <div class="w-16 h-16 flex-shrink-0">
                        <div class="w-full h-full rounded-lg overflow-hidden bg-gray-100 dark:bg-gray-800">
                            @php 
                                $img = $item->product->thumbnail 
                                    ? (str_starts_with($item->product->thumbnail, 'http') 
                                        ? $item->product->thumbnail 
                                        : Storage::url($item->product->thumbnail) . '?t=' . $item->product->updated_at->timestamp)
                                    : 'https://picsum.photos/seed/cart-'.$item->product->id.'/80/80';
                            @endphp
                            <img src="{{ $img }}" alt="" class="w-full h-full object-cover" loading="lazy">
                        </div>
                    </div>

                    <!-- Product Info -->
                    <div class="flex-1 min-w-0">
                        <a href="{{ route('product.detail', $item->product->slug) }}" class="font-semibold text-sm hover:text-[#C8A951] transition-colors line-clamp-2">
                            {{ $item->product->name }}
                        </a>
                        
                        <!-- Variant Info -->
                        @if($item->size || $item->color)
                        <div class="text-xs text-gray-500 mt-1 space-y-0.5">
                            @if($item->size)
                            <div>Size: <span class="font-medium">{{ $item->size }}</span></div>
                            @endif
                            @if($item->color)
                            <div>Color: <span class="font-medium">{{ $item->color }}</span></div>
                            @endif
                        </div>
                        @endif

                        <!-- Price & Quantity -->
                        <div class="flex items-center justify-between mt-2 pt-1.5 border-t border-gray-100 dark:border-gray-800">
                            <div>
                                <p class="text-xs text-gray-500">Rp {{ number_format($item->product->finalPrice, 0, ',', '.') }}</p>
                                <p class="font-semibold text-sm">Rp {{ number_format($item->product->finalPrice * $item->quantity, 0, ',', '.') }}</p>
                            </div>
                            
                            <!-- Quantity & Delete -->
                            <div class="flex flex-col items-end gap-2">
                                <div class="flex items-center gap-1 bg-gray-100 dark:bg-gray-800 rounded-md p-0.5">
                                    <button onclick="updateQuantity({{ $item->id }}, -1)" class="w-6 h-6 rounded flex items-center justify-center hover:bg-white dark:hover:bg-gray-700 text-xs font-semibold text-gray-600 dark:text-gray-300">−</button>
                                    <span class="w-5 text-center text-xs font-semibold" id="qty-{{ $item->id }}">{{ $item->quantity }}</span>
                                    <button onclick="updateQuantity({{ $item->id }}, 1)" class="w-6 h-6 rounded flex items-center justify-center hover:bg-white dark:hover:bg-gray-700 text-xs font-semibold text-gray-600 dark:text-gray-300">+</button>
                                </div>
                                <button onclick="removeSidebarItem({{ $item->id }})" class="text-xs text-red-500 hover:text-red-700 font-medium">
                                    Hapus
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Summary & Checkout -->
        <div class="border-t border-gray-200 dark:border-gray-700 p-4 space-y-3 bg-gray-50 dark:bg-[#0f0f0f]">
            <!-- Summary -->
            <div class="space-y-2 text-sm">
                <div class="flex justify-between text-gray-600 dark:text-gray-400">
                    <span>Subtotal:</span>
                    <span>Rp {{ number_format($cart->items->sum(fn($i) => $i->product->finalPrice * $i->quantity), 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-xs text-gray-500">
                    <span>Pengiriman:</span>
                    <span>Dihitung saat checkout</span>
                </div>
                <div class="flex justify-between font-bold text-base border-t border-gray-200 dark:border-gray-700 pt-2">
                    <span>Total:</span>
                    <span>Rp {{ number_format($cart->items->sum(fn($i) => $i->product->finalPrice * $i->quantity), 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Buttons -->
            <div class="space-y-2.5 pt-2">
                <a href="{{ route('checkout') }}" onclick="toggleCart()" class="block text-center bg-gray-900 hover:bg-black dark:bg-[#C8A951] dark:hover:bg-[#b8963e] text-white dark:text-gray-900 px-4 py-2.5 rounded-lg font-semibold text-sm transition-all">
                    Checkout
                </a>
                <a href="{{ route('shop') }}" onclick="toggleCart()" class="block text-center text-gray-600 dark:text-gray-400 hover:text-[#C8A951] text-sm font-medium transition-colors">
                    Lanjut Belanja
                </a>
            </div>
        </div>
    </div>
@else
    <!-- Empty Cart -->
    <div class="flex flex-col h-full items-center justify-center p-6 text-center">
        <button onclick="toggleCart()" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        
        <svg class="w-16 h-16 text-gray-300 dark:text-gray-700 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
        </svg>

        <h3 class="font-semibold text-gray-900 dark:text-gray-100 mb-1">Keranjang Kosong</h3>
        <p class="text-xs text-gray-500 mb-6">Belum ada item di keranjang</p>

        <a href="{{ route('shop') }}" onclick="toggleCart()" class="inline-flex items-center gap-2 bg-gray-900 hover:bg-black dark:bg-[#C8A951] dark:hover:bg-[#b8963e] text-white dark:text-gray-900 px-6 py-2 rounded-lg font-semibold text-sm transition-all">
            Mulai Belanja
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
            </svg>
        </a>
    </div>
@endif

@push('scripts')
<script>
    async function removeSidebarItem(itemId) {
        if (!confirm('Hapus item ini dari keranjang?')) return;
        try {
            const res = await fetch('/cart/' + itemId, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
            });
            const data = await res.json();
            if (data.success) {
                // Remove item from DOM
                const itemEl = document.getElementById('cart-item-' + itemId);
                if (itemEl) {
                    itemEl.remove();
                    updateCartSidebar();
                }
                showToast('Item dihapus dari keranjang', 'info');
            }
        } catch(e) {
            showToast('Gagal menghapus item', 'error');
        }
    }
</script>
@endpush

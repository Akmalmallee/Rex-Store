<x-app-layout>
    <div class="pt-24 pb-16 min-h-screen bg-gray-50 dark:bg-[#111]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div data-cart-content>
            @if($cart && $cart->items->isNotEmpty())
            <div class="mb-8">
                <a href="{{ route('shop') }}" class="text-sm text-gray-500 hover:text-[#C8A951] inline-flex items-center gap-1 mb-3">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    Back to Shop
                </a>
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold">Shopping Cart</h1>
                        <p class="text-sm text-gray-500 mt-1">{{ $cart->items->sum('quantity') }} item(s)</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 space-y-3">
                    @foreach($cart->items as $item)
                    <div class="bg-white dark:bg-[#1a1a1a] rounded-xl border border-gray-100 dark:border-gray-800 p-4" id="cart-item-{{ $item->id }}">
                        <div class="flex gap-3 sm:gap-4">
                            <div class="w-20 sm:w-24 md:w-28 shrink-0">
                                <div class="aspect-square rounded-lg overflow-hidden bg-gray-100 dark:bg-gray-800">
                                    @php $img = $item->product->thumbnail ? (str_starts_with($item->product->thumbnail, 'http') ? $item->product->thumbnail : Storage::url($item->product->thumbnail) . '?t=' . $item->product->updated_at->timestamp) : 'https://picsum.photos/seed/cart-'.$item->product->id.'/200/200'; @endphp
                                    <img src="{{ $img }}" alt="" class="w-full h-full object-cover" loading="lazy">
                                </div>
                            </div>
                            <div class="flex-1 min-w-0 flex flex-col justify-between">
                                <div class="flex items-start justify-between gap-3">
                                    <a href="{{ route('product.detail', $item->product->slug) }}" class="font-semibold text-sm sm:text-base hover:text-[#C8A951] transition-colors leading-snug">{{ $item->product->name }}</a>
                                    <span class="font-bold text-sm sm:text-base shrink-0">Rp {{ number_format($item->product->finalPrice * $item->quantity, 0, ',', '.') }}</span>
                                </div>
                                @if($item->size || $item->color)
                                <div class="flex flex-wrap gap-x-3 text-xs sm:text-sm text-gray-500 mt-1">
                                    @if($item->size) <span><span class="text-gray-400">Size:</span> {{ $item->size }}</span> @endif
                                    @if($item->color) <span><span class="text-gray-400">Color:</span> {{ $item->color }}</span> @endif
                                </div>
                                @endif
                                <div class="text-xs sm:text-sm text-gray-400 mt-0.5">Rp {{ number_format($item->product->finalPrice, 0, ',', '.') }} × {{ $item->quantity }}</div>
                                <div class="flex items-center justify-between mt-2.5 pt-2.5 border-t border-gray-100 dark:border-gray-800">
                                    <div class="flex items-center gap-1 bg-gray-100 dark:bg-gray-800 rounded-lg p-0.5">
                                        <button onclick="updateQuantity({{ $item->id }}, -1)" class="w-7 h-7 rounded-md flex items-center justify-center hover:bg-white dark:hover:bg-gray-700 transition-colors text-gray-600 dark:text-gray-300 text-sm font-medium">−</button>
                                        <span class="w-7 text-center font-semibold text-sm" id="qty-{{ $item->id }}">{{ $item->quantity }}</span>
                                        <button onclick="updateQuantity({{ $item->id }}, 1)" class="w-7 h-7 rounded-md flex items-center justify-center hover:bg-white dark:hover:bg-gray-700 transition-colors text-gray-600 dark:text-gray-300 text-sm font-medium">+</button>
                                    </div>
                                    <button onclick="removeItem({{ $item->id }})" class="flex items-center gap-1.5 text-xs text-red-500 hover:text-red-700 transition-colors font-medium">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        Hapus
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-[#1a1a1a] rounded-xl border border-gray-100 dark:border-gray-800 p-5 sticky top-28">
                        <h3 class="font-semibold text-base mb-4">Ringkasan Belanja</h3>
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Subtotal ({{ $cart->items->sum('quantity') }} item)</span>
                                <span class="font-medium">Rp {{ number_format($cart->items->sum(fn($i) => $i->product->finalPrice * $i->quantity), 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Ongkos Kirim</span>
                                <span class="text-gray-400 text-xs">Dihitung saat checkout</span>
                            </div>
                            <div class="border-t border-gray-200 dark:border-gray-700 pt-3 mt-3">
                                <div class="flex justify-between font-bold text-base">
                                    <span>Total</span>
                                    <span>Rp {{ number_format($cart->items->sum(fn($i) => $i->product->finalPrice * $i->quantity), 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('checkout') }}" class="block text-center w-full mt-5 bg-gray-900 hover:bg-black dark:bg-[#C8A951] dark:hover:bg-[#b8963e] text-white px-6 py-3 rounded-xl font-semibold transition-all duration-300">
                            Checkout
                        </a>
                        <a href="{{ route('shop') }}" class="block text-center w-full mt-2.5 text-sm text-gray-500 hover:text-[#C8A951] transition-colors">Lanjut Belanja</a>
                    </div>
                </div>
            </div>
            @else
            <div class="text-center py-20 max-w-md mx-auto">
                <svg class="w-24 h-24 mx-auto text-gray-200 dark:text-gray-700 mb-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    <circle cx="9" cy="21" r="1" fill="currentColor"/>
                    <circle cx="15" cy="21" r="1" fill="currentColor"/>
                </svg>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-2">Keranjang Kosong</h3>
                <p class="text-gray-400 mb-8">Belum ada item di keranjang. Yuk mulai belanja!</p>
                <a href="{{ route('shop') }}" class="inline-flex items-center gap-2 bg-gray-900 hover:bg-black dark:bg-white dark:hover:bg-gray-200 text-white dark:text-gray-900 px-8 py-3.5 rounded-xl font-semibold transition-all duration-300">
                    Mulai Belanja
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>
            @endif
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        async function updateQuantity(itemId, delta) {
            const qtyEl = document.getElementById('qty-' + itemId);
            const currentQty = parseInt(qtyEl.textContent);
            const newQty = currentQty + delta;
            if (newQty < 1) return;

            try {
                const res = await fetch('/cart/' + itemId, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ quantity: newQty })
                });
                const data = await res.json();
                if (data.success) location.reload();
            } catch(e) {}
        }

        async function removeItem(itemId) {
            if (!confirm('Hapus item ini dari keranjang?')) return;
            try {
                const res = await fetch('/cart/' + itemId, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                });
                const data = await res.json();
                if (data.success) location.reload();
            } catch(e) {}
        }
    </script>
    @endpush
</x-app-layout>

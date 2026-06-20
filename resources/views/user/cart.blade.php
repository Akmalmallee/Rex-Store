<x-app-layout>
    <div class="pt-28 pb-24 min-h-screen bg-[#0a0a0a]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div data-cart-content>
            @if($cart && $cart->items->isNotEmpty())
            <div class="mb-10 reveal">
                <a href="{{ route('shop') }}" class="text-[10px] tracking-widest uppercase font-light text-gray-500 hover:text-[#C8A951] inline-flex items-center gap-2 mb-4 transition-colors">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 19l-7-7 7-7"/></svg>
                    Back to Shop
                </a>
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-light tracking-tight text-white">Shopping Cart</h1>
                        <p class="text-xs font-light text-gray-500 mt-1" id="cart-count-label">{{ $cart->items->sum('quantity') }} items</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 space-y-4">
                    @foreach($cart->items as $item)
                    <div class="glass-card p-5 hover:border-[#C8A951]/20 transition-all duration-500 group" id="cart-item-{{ $item->id }}" data-cart-row="{{ $item->id }}" data-stagger>
                        <div class="flex gap-4">
                            <div class="w-20 sm:w-24 shrink-0">
                                <div class="aspect-square overflow-hidden bg-[#111] rounded-sm">
                                    @php $img = $item->product->thumbnail ? (str_starts_with($item->product->thumbnail, 'http') ? $item->product->thumbnail : Storage::url($item->product->thumbnail) . '?t=' . $item->product->updated_at->timestamp) : 'https://picsum.photos/seed/cart-'.$item->product->id.'/200/200'; @endphp
                                    <img src="{{ $img }}" alt="" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" loading="lazy">
                                </div>
                            </div>
                            <div class="flex-1 min-w-0 flex flex-col justify-between">
                                <div class="flex items-start justify-between gap-3">
                                    <a href="{{ route('product.detail', $item->product->slug) }}" class="text-sm font-light text-white/90 hover:text-[#C8A951] transition-colors leading-snug">{{ $item->product->name }}</a>
                                    <span class="text-sm font-light text-white shrink-0" data-subtotal>Rp {{ number_format($item->product->finalPrice * $item->quantity, 0, ',', '.') }}</span>
                                </div>
                                @if($item->size || $item->color)
                                <div class="flex flex-wrap gap-x-3 text-xs font-light text-gray-500 mt-1">
                                    @if($item->size) <span><span class="text-gray-600">Size:</span> {{ $item->size }}</span> @endif
                                    @if($item->color) <span><span class="text-gray-600">Color:</span> {{ $item->color }}</span> @endif
                                </div>
                                @endif
                                <div class="text-xs font-light text-gray-600 mt-0.5">Rp {{ number_format($item->product->finalPrice, 0, ',', '.') }} × {{ $item->quantity }}</div>
                                <div class="flex items-center justify-between mt-3 pt-3 border-t border-white/5">
                                    <div class="flex items-center gap-0 bg-white/5 border border-white/10 group-hover:border-[#C8A951]/30 transition-colors duration-500">
                                        <button onclick="updateQuantity({{ $item->id }}, -1)" class="w-8 h-8 flex items-center justify-center hover:bg-white/10 transition-colors text-gray-300 text-xs font-light">−</button>
                                        <span class="w-8 text-center text-xs font-light text-gray-300" id="qty-{{ $item->id }}">{{ $item->quantity }}</span>
                                        <button onclick="updateQuantity({{ $item->id }}, 1)" class="w-8 h-8 flex items-center justify-center hover:bg-white/10 transition-colors text-gray-300 text-xs font-light">+</button>
                                    </div>
                                    <button onclick="removeItem({{ $item->id }})" class="flex items-center gap-1.5 text-[10px] font-light text-red-400/60 hover:text-red-400 transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        Remove
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="lg:col-span-1 reveal reveal-delay-1">
                    <div class="glass-card p-6 sticky top-28" id="order-summary">
                        <p class="text-xs tracking-widest uppercase font-light text-gray-300 mb-5">Order Summary</p>
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between text-xs font-light">
                                <span class="text-gray-500">Subtotal <span id="summary-item-count">({{ $cart->items->sum('quantity') }} items)</span></span>
                                <span class="text-white" id="summary-subtotal">Rp {{ number_format($cart->items->sum(fn($i) => $i->product->finalPrice * $i->quantity), 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-xs font-light">
                                <span class="text-gray-500">Shipping</span>
                                <span class="text-[#C8A951] text-[10px] tracking-wider">FREE</span>
                            </div>
                            <div class="border-t border-white/5 pt-3 mt-3">
                                <div class="flex justify-between text-sm text-white">
                                    <span class="font-light">Total</span>
                                    <span class="text-[#C8A951]" id="summary-total">Rp {{ number_format($cart->items->sum(fn($i) => $i->product->finalPrice * $i->quantity), 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('checkout') }}" class="block text-center w-full mt-5 bg-[#C8A951] text-black text-xs tracking-widest uppercase font-medium py-3.5 hover:bg-white transition-all duration-500">
                            Proceed to Checkout
                        </a>
                        <a href="{{ route('shop') }}" class="block text-center w-full mt-3 text-[10px] tracking-widest uppercase font-light text-gray-500 hover:text-[#C8A951] transition-colors">Continue Shopping</a>
                    </div>
                </div>
            </div>
            @else
            <div class="text-center py-20 max-w-md mx-auto reveal">
                <div class="w-20 h-20 mx-auto rounded-full bg-[#C8A951]/5 border border-[#C8A951]/10 flex items-center justify-center mb-6">
                    <svg class="w-8 h-8 text-[#C8A951]/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                </div>
                <h3 class="text-sm tracking-widest uppercase font-light text-white/60 mb-2">Cart Empty</h3>
                <p class="text-xs font-light text-gray-500 mb-8">No items in your cart yet.</p>
                <a href="{{ route('shop') }}" class="text-xs tracking-widest uppercase font-light text-[#C8A951] border-b border-[#C8A951]/30 pb-0.5 hover:text-white hover:border-white transition-colors">
                    Start Shopping
                </a>
            </div>
            @endif
            </div>
        </div>
    </div>
</x-app-layout>

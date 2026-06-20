@php
    $cart = auth()->check() ? auth()->user()->cart : null;
@endphp

@if($cart && $cart->items->isNotEmpty())
    <div class="flex flex-col h-full">
        <!-- Header -->
        <div class="border-b border-white/5 p-5 flex items-center justify-between">
            <div>
                <h2 class="text-sm tracking-widest uppercase font-light">Cart</h2>
                <p class="text-[10px] text-gray-500 font-light mt-0.5">{{ $cart->items->sum('quantity') }} items</p>
            </div>
            <button onclick="toggleCart()" class="text-gray-500 hover:text-white transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Items List -->
        <div class="flex-1 overflow-y-auto scrollbar-hide">
            <div class="space-y-0" id="cart-items-list">
                @foreach($cart->items as $item)
                <div class="flex gap-3 p-5 border-b border-white/5" id="cart-item-{{ $item->id }}">
                    <!-- Product Image -->
                    <div class="w-16 h-16 flex-shrink-0">
                        <div class="w-full h-full overflow-hidden bg-[#111]">
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
                        <a href="{{ route('product.detail', $item->product->slug) }}" class="text-xs font-light text-white/80 hover:text-[#C8A951] transition-colors line-clamp-2">
                            {{ $item->product->name }}
                        </a>
                        
                        @if($item->size || $item->color)
                        <div class="text-[10px] text-gray-500 font-light mt-1 space-y-0.5">
                            @if($item->size) <div>Size: <span class="text-gray-400">{{ $item->size }}</span></div> @endif
                            @if($item->color) <div>Color: <span class="text-gray-400">{{ $item->color }}</span></div> @endif
                        </div>
                        @endif

                        <div class="flex items-center justify-between mt-2 pt-2 border-t border-white/5">
                            <div>
                                <p class="text-[10px] text-gray-500 font-light">Rp {{ number_format($item->product->finalPrice, 0, ',', '.') }}</p>
                                <p class="text-xs font-light text-white">Rp {{ number_format($item->product->finalPrice * $item->quantity, 0, ',', '.') }}</p>
                            </div>
                            
                            <div class="flex flex-col items-end gap-2">
                                <div class="flex items-center gap-0 bg-white/5 border border-white/10">
                                    <button onclick="updateQuantity({{ $item->id }}, -1)" class="w-6 h-6 flex items-center justify-center hover:bg-white/10 text-xs font-light text-gray-300">−</button>
                                    <span class="w-5 text-center text-[10px] font-light text-gray-300" id="qty-{{ $item->id }}">{{ $item->quantity }}</span>
                                    <button onclick="updateQuantity({{ $item->id }}, 1)" class="w-6 h-6 flex items-center justify-center hover:bg-white/10 text-xs font-light text-gray-300">+</button>
                                </div>
                                <button onclick="removeSidebarItem({{ $item->id }})" class="text-[10px] font-light text-red-400/60 hover:text-red-400 transition-colors">
                                    Remove
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Summary & Checkout -->
        <div class="border-t border-white/5 p-5 space-y-4 bg-black/40 backdrop-blur-sm">
            <div class="space-y-2 text-sm">
                <div class="flex justify-between text-xs font-light text-gray-400">
                    <span>Subtotal</span>
                    <span class="text-white">Rp {{ number_format($cart->items->sum(fn($i) => $i->product->finalPrice * $i->quantity), 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-[10px] text-gray-500 font-light">
                    <span>Shipping</span>
                    <span>Calculated at checkout</span>
                </div>
                <div class="flex justify-between text-sm text-white border-t border-white/5 pt-3 mt-3">
                    <span class="font-light">Total</span>
                    <span>Rp {{ number_format($cart->items->sum(fn($i) => $i->product->finalPrice * $i->quantity), 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="space-y-2.5">
                <a href="{{ route('checkout') }}" onclick="toggleCart()" class="block text-center w-full bg-[#C8A951] text-black text-xs tracking-widest uppercase font-medium py-3 hover:bg-white transition-all duration-500">
                    Checkout
                </a>
                <a href="{{ route('shop') }}" onclick="toggleCart()" class="block text-center text-[10px] tracking-widest uppercase font-light text-gray-500 hover:text-[#C8A951] transition-colors">
                    Continue Shopping
                </a>
            </div>
        </div>
    </div>
@else
    <!-- Empty Cart -->
    <div class="flex flex-col h-full items-center justify-center p-8 text-center">
        <button onclick="toggleCart()" class="absolute top-4 right-4 text-gray-500 hover:text-white transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        
        <svg class="w-12 h-12 text-gray-700 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
        </svg>

        <h3 class="text-xs tracking-widest uppercase font-light text-white/60 mb-1">Cart Empty</h3>
        <p class="text-[10px] text-gray-500 font-light mb-6">No items in your cart</p>

        <a href="{{ route('shop') }}" onclick="toggleCart()" class="text-xs tracking-widest uppercase font-light text-[#C8A951] border-b border-[#C8A951]/30 hover:text-white hover:border-white transition-colors pb-0.5">
            Start Shopping
        </a>
    </div>
@endif

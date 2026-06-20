<x-app-layout>
    <div class="pt-28 pb-24 min-h-screen bg-[#0a0a0a]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-10 reveal">
                <a href="{{ route('cart') }}" class="text-[10px] tracking-widest uppercase font-light text-gray-500 hover:text-[#C8A951] inline-flex items-center gap-2 mb-4 transition-colors">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 19l-7-7 7-7"/></svg>
                    Back to Cart
                </a>
                <h1 class="text-3xl font-light tracking-tight text-white">Checkout</h1>
            </div>

            @if(!$cart || $cart->items->isEmpty())
            <div class="text-center py-20 reveal">
                <p class="text-sm font-light text-gray-500">Your cart is empty.</p>
                <a href="{{ route('shop') }}" class="text-xs tracking-widest uppercase font-light text-[#C8A951] border-b border-[#C8A951]/30 pb-0.5 hover:text-white hover:border-white transition-colors mt-6 inline-flex">Shop Now</a>
            </div>
            @else
            <form action="{{ route('checkout.store') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                @csrf
                <div class="lg:col-span-2 space-y-6">
                    <!-- Shipping Address -->
                    <div class="glass-card p-8 reveal" data-stagger>
                        <p class="text-xs tracking-widest uppercase font-light text-gray-300 mb-6 flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#C8A951]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Shipping Address
                        </p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label class="label-luxury">Recipient Name</label>
                                <input type="text" name="recipient_name" class="input-luxury" value="{{ old('recipient_name', Auth::user()->name ?? '') }}" required>
                                @error('recipient_name') <p class="text-red-400 text-[10px] font-light mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div class="md:col-span-2">
                                <label class="label-luxury">Address</label>
                                <textarea name="address" rows="2" class="input-luxury" required>{{ old('address', Auth::user()->address ?? '') }}</textarea>
                                @error('address') <p class="text-red-400 text-[10px] font-light mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="label-luxury">City</label>
                                <input type="text" name="city" class="input-luxury" value="{{ old('city') }}" required>
                                @error('city') <p class="text-red-400 text-[10px] font-light mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="label-luxury">Phone</label>
                                <input type="text" name="phone" class="input-luxury" value="{{ old('phone', Auth::user()->phone ?? '') }}" required>
                                @error('phone') <p class="text-red-400 text-[10px] font-light mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div class="md:col-span-2">
                                <label class="label-luxury">Order Notes (Optional)</label>
                                <textarea name="notes" rows="2" class="input-luxury" placeholder="Special instructions for your order">{{ old('notes') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Shipping Courier -->
                    <div class="glass-card p-8 reveal" data-stagger>
                        <p class="text-xs tracking-widest uppercase font-light text-gray-300 mb-6 flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#C8A951]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
                            Shipping Courier
                        </p>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            @foreach([
                                ['code' => 'JNE', 'est' => '2-3 days'],
                                ['code' => 'J&T', 'est' => '2-4 days'],
                                ['code' => 'SiCepat', 'est' => '1-2 days'],
                            ] as $courier)
                            <label class="flex items-center gap-3 p-4 border border-white/10 cursor-pointer hover:border-[#C8A951] transition-all duration-300 has-[:checked]:border-[#C8A951] has-[:checked]:bg-[#C8A951]/5 has-[:checked]:shadow-[0_0_20px_rgba(200,169,81,0.05)]">
                                <input type="radio" name="shipping_courier" value="{{ $courier['code'] }}" class="text-[#C8A951] focus:ring-[#C8A951]" {{ $loop->first ? 'checked' : '' }}>
                                <div>
                                    <p class="text-xs font-light text-white/80">{{ $courier['code'] }}</p>
                                    <p class="text-[10px] text-gray-500 font-light">{{ $courier['est'] }}</p>
                                </div>
                            </label>
                            @endforeach
                        </div>
                        @error('shipping_courier') <p class="text-red-400 text-[10px] font-light mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Payment Method -->
                    <div class="glass-card p-8 reveal" data-stagger>
                        <p class="text-xs tracking-widest uppercase font-light text-gray-300 mb-6 flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#C8A951]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                            Payment Method
                        </p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach(['Bank Transfer', 'Dana', 'OVO', 'GoPay', 'COD'] as $method)
                            <label class="flex items-center gap-3 p-4 border border-white/10 cursor-pointer hover:border-[#C8A951] transition-all duration-300 has-[:checked]:border-[#C8A951] has-[:checked]:bg-[#C8A951]/5 has-[:checked]:shadow-[0_0_20px_rgba(200,169,81,0.05)]">
                                <input type="radio" name="payment_method" value="{{ $method }}" class="text-[#C8A951] focus:ring-[#C8A951]" {{ $loop->first ? 'checked' : '' }}>
                                <div>
                                    <p class="text-xs font-light text-white/80">{{ $method }}</p>
                                    @if($method == 'COD')
                                    <p class="text-[10px] text-gray-500 font-light">Pay on delivery</p>
                                    @endif
                                </div>
                            </label>
                            @endforeach
                        </div>
                        @error('payment_method') <p class="text-red-400 text-[10px] font-light mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Order Summary Sidebar -->
                <div class="lg:col-span-1">
                    <div class="glass-card p-6 sticky top-28 reveal reveal-delay-1">
                        <p class="text-xs tracking-widest uppercase font-light text-gray-300 mb-5">Order Summary</p>
                        <div class="space-y-3 max-h-64 overflow-y-auto scrollbar-hide mb-4">
                            @foreach($cart->items as $item)
                            <div class="flex gap-3">
                                <div class="w-12 h-12 overflow-hidden bg-[#111] shrink-0 rounded-sm">
                                    @php $img = $item->product->thumbnail ? (str_starts_with($item->product->thumbnail, 'http') ? $item->product->thumbnail : Storage::url($item->product->thumbnail) . '?t=' . $item->product->updated_at->timestamp) : 'https://picsum.photos/seed/checkout-'.$item->product->id.'/200/200'; @endphp
                                    <img src="{{ $img }}" alt="" class="w-full h-full object-cover">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-light text-white/80 truncate">{{ $item->product->name }}</p>
                                    <p class="text-[10px] text-gray-500 font-light">x{{ $item->quantity }}</p>
                                    <p class="text-xs font-light text-white mt-0.5">Rp {{ number_format($item->product->finalPrice * $item->quantity, 0, ',', '.') }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="border-t border-white/5 pt-4 space-y-2 text-sm">
                            <div class="flex justify-between text-xs font-light">
                                <span class="text-gray-500">Subtotal</span>
                                <span class="text-white">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-xs font-light">
                                <span class="text-gray-500">Shipping</span>
                                <span class="text-[#C8A951] text-[10px] tracking-wider">FREE</span>
                            </div>
                            <div class="border-t border-white/5 pt-2 flex justify-between text-sm text-white">
                                <span class="font-light">Total</span>
                                <span class="text-[#C8A951]" id="checkout-total">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                            </div>
                        </div>
                        <button type="submit" class="w-full mt-6 bg-[#C8A951] text-black text-xs tracking-widest uppercase font-medium py-3.5 hover:bg-white transition-all duration-500 relative overflow-hidden group">
                            <span class="relative z-10">Place Order</span>
                            <span class="absolute inset-0 bg-white translate-y-full group-hover:translate-y-0 transition-transform duration-500"></span>
                        </button>
                    </div>
                </div>
            </form>
            @endif
        </div>
    </div>
</x-app-layout>

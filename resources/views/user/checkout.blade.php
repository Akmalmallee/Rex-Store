<x-app-layout>
    <div class="pt-24 pb-16 min-h-screen bg-gray-50 dark:bg-[#111]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold mb-8">Checkout</h1>

            @if(!$cart || $cart->items->isEmpty())
            <div class="text-center py-20">
                <p class="text-gray-500">Your cart is empty.</p>
                <a href="{{ route('shop') }}" class="btn-primary mt-4 inline-flex">Shop Now</a>
            </div>
            @else
            <form action="{{ route('checkout.store') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                @csrf
                <div class="lg:col-span-2 space-y-6">
                    <!-- Shipping Address -->
                    <div class="bg-white dark:bg-[#1a1a1a] rounded-xl p-6 shadow-sm">
                        <h3 class="font-semibold text-lg mb-4">Shipping Address</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium mb-1">Address</label>
                                <textarea name="address" rows="2" class="input-field" required>{{ old('address', Auth::user()->address ?? '') }}</textarea>
                                @error('address') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">City</label>
                                <input type="text" name="city" class="input-field" value="{{ old('city') }}" required>
                                @error('city') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Phone</label>
                                <input type="text" name="phone" class="input-field" value="{{ old('phone', Auth::user()->phone ?? '') }}" required>
                                @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium mb-1">Order Notes (Optional)</label>
                                <textarea name="notes" rows="2" class="input-field" placeholder="Special instructions for your order">{{ old('notes') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Shipping Courier -->
                    <div class="bg-white dark:bg-[#1a1a1a] rounded-xl p-6 shadow-sm">
                        <h3 class="font-semibold text-lg mb-4">Shipping Courier</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <label class="flex items-center gap-3 p-4 rounded-xl border-2 border-gray-200 dark:border-gray-700 cursor-pointer hover:border-[#C8A951] transition-colors has-[:checked]:border-[#C8A951] has-[:checked]:bg-[#C8A951]/5">
                                <input type="radio" name="shipping_courier" value="JNE" class="text-[#C8A951] focus:ring-[#C8A951]" checked>
                                <div>
                                    <p class="font-medium text-sm">JNE</p>
                                    <p class="text-xs text-gray-500">2-3 days</p>
                                </div>
                            </label>
                            <label class="flex items-center gap-3 p-4 rounded-xl border-2 border-gray-200 dark:border-gray-700 cursor-pointer hover:border-[#C8A951] transition-colors has-[:checked]:border-[#C8A951] has-[:checked]:bg-[#C8A951]/5">
                                <input type="radio" name="shipping_courier" value="J&T" class="text-[#C8A951] focus:ring-[#C8A951]">
                                <div>
                                    <p class="font-medium text-sm">J&T</p>
                                    <p class="text-xs text-gray-500">2-4 days</p>
                                </div>
                            </label>
                            <label class="flex items-center gap-3 p-4 rounded-xl border-2 border-gray-200 dark:border-gray-700 cursor-pointer hover:border-[#C8A951] transition-colors has-[:checked]:border-[#C8A951] has-[:checked]:bg-[#C8A951]/5">
                                <input type="radio" name="shipping_courier" value="SiCepat" class="text-[#C8A951] focus:ring-[#C8A951]">
                                <div>
                                    <p class="font-medium text-sm">SiCepat</p>
                                    <p class="text-xs text-gray-500">1-2 days</p>
                                </div>
                            </label>
                        </div>
                        @error('shipping_courier') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Payment Method -->
                    <div class="bg-white dark:bg-[#1a1a1a] rounded-xl p-6 shadow-sm">
                        <h3 class="font-semibold text-lg mb-4">Payment Method</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach(['Bank Transfer', 'Dana', 'OVO', 'GoPay', 'COD'] as $method)
                            <label class="flex items-center gap-3 p-4 rounded-xl border-2 border-gray-200 dark:border-gray-700 cursor-pointer hover:border-[#C8A951] transition-colors has-[:checked]:border-[#C8A951] has-[:checked]:bg-[#C8A951]/5">
                                <input type="radio" name="payment_method" value="{{ $method }}" class="text-[#C8A951] focus:ring-[#C8A951]" {{ $loop->first ? 'checked' : '' }}>
                                <div>
                                    <p class="font-medium text-sm">{{ $method }}</p>
                                    @if($method == 'COD')
                                    <p class="text-xs text-gray-500">Pay on delivery</p>
                                    @endif
                                </div>
                            </label>
                            @endforeach
                        </div>
                        @error('payment_method') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Order Summary Sidebar -->
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-[#1a1a1a] rounded-xl p-6 shadow-sm sticky top-28">
                        <h3 class="font-semibold text-lg mb-4">Order Summary</h3>
                        <div class="space-y-3 max-h-64 overflow-y-auto mb-4">
                            @foreach($cart->items as $item)
                            <div class="flex gap-3">
                                <div class="w-14 h-14 rounded-lg overflow-hidden bg-gray-100 dark:bg-gray-800 shrink-0">
                                    @php $img = $item->product->thumbnail ? (str_starts_with($item->product->thumbnail, 'http') ? $item->product->thumbnail : Storage::url($item->product->thumbnail) . '?t=' . $item->product->updated_at->timestamp) : 'https://picsum.photos/seed/checkout-'.$item->product->id.'/200/200'; @endphp
                                    <img src="{{ $img }}" alt="" class="w-full h-full object-cover">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium truncate">{{ $item->product->name }}</p>
                                    <p class="text-xs text-gray-500">x{{ $item->quantity }}</p>
                                    <p class="text-sm font-semibold">Rp {{ number_format($item->product->finalPrice * $item->quantity, 0, ',', '.') }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-4 space-y-2 text-sm">
                            <div class="flex justify-between"><span class="text-gray-500">Subtotal</span><span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span></div>
                            <div class="flex justify-between"><span class="text-gray-500">Shipping</span><span class="text-green-500">Free</span></div>
                            <div class="border-t border-gray-200 dark:border-gray-700 pt-2 flex justify-between font-semibold text-base">
                                <span>Total</span>
                                <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                            </div>
                        </div>
                        <button type="submit" class="w-full mt-6 bg-[#C8A951] hover:bg-[#b8963e] text-white px-6 py-3 rounded-xl font-medium transition-all duration-300">
                            Place Order
                        </button>
                    </div>
                </div>
            </form>
            @endif
        </div>
    </div>
</x-app-layout>

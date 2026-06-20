<x-app-layout>
    <div class="pt-28 pb-24 min-h-screen bg-[#0a0a0a] flex items-center">
        <div class="max-w-lg mx-auto px-4 sm:px-6 lg:px-8 w-full">
            <div class="text-center reveal">
                <div class="w-20 h-20 mx-auto mb-6 relative">
                    <div class="absolute inset-0 bg-[#C8A951]/20 rounded-full animate-ping opacity-25"></div>
                    <div class="relative w-20 h-20 rounded-full bg-[#C8A951]/10 border border-[#C8A951]/30 flex items-center justify-center">
                        <svg class="w-10 h-10 text-[#C8A951]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                </div>
                <h1 class="text-2xl font-light tracking-tight text-white mb-2">Payment Successful!</h1>
                <p class="text-sm font-light text-gray-400 mb-2">Order <span class="text-white font-mono">#{{ $order->invoice_number }}</span></p>
                <p class="text-xs font-light text-gray-500 mb-10">Your payment has been confirmed. We'll start processing your order right away.</p>

                <div class="glass-card p-6 mb-8 text-left">
                    <div class="flex items-center justify-between text-sm py-2 border-b border-white/5">
                        <span class="text-gray-500 font-light">Payment Method</span>
                        <span class="text-white font-light">{{ $order->payment_method }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm py-2 border-b border-white/5">
                        <span class="text-gray-500 font-light">Channel</span>
                        <span class="text-white font-light">{{ $payment->payment_channel ?? 'Manual Transfer' }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm py-2">
                        <span class="text-gray-500 font-light">Total Paid</span>
                        <span class="text-[#C8A951] font-light">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 justify-center">
                    <a href="{{ route('orders.show', $order->id) }}" class="block text-center px-8 py-3.5 border border-white/20 text-white text-xs tracking-widest uppercase font-light hover:bg-white/5 transition-all duration-500">
                        View Order
                    </a>
                    <a href="{{ route('shop') }}" class="block text-center px-8 py-3.5 bg-[#C8A951] text-black text-xs tracking-widest uppercase font-medium hover:bg-white transition-all duration-500">
                        Continue Shopping
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

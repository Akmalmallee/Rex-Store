<x-app-layout>
    <div class="pt-28 pb-24 min-h-screen bg-[#0a0a0a] flex items-center">
        <div class="max-w-lg mx-auto px-4 sm:px-6 lg:px-8 w-full">
            <div class="text-center reveal">
                <div class="w-20 h-20 mx-auto mb-6">
                    <div class="w-20 h-20 rounded-full bg-red-500/10 border border-red-500/20 flex items-center justify-center">
                        <svg class="w-10 h-10 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </div>
                </div>
                <h1 class="text-2xl font-light tracking-tight text-white mb-2">Payment Failed</h1>
                <p class="text-sm font-light text-gray-400 mb-2">Order <span class="text-white font-mono">#{{ $order->invoice_number }}</span></p>
                <p class="text-xs font-light text-gray-500 mb-10">{{ $reason ?? 'Your payment could not be processed. Please try again.' }}</p>

                <div class="glass-card p-6 mb-8 text-left">
                    <p class="text-xs font-light text-gray-400 mb-3">What you can do:</p>
                    <ul class="space-y-2 text-sm font-light text-gray-300">
                        <li class="flex items-start gap-2">
                            <span class="text-[#C8A951] mt-0.5">→</span>
                            <a href="{{ route('checkout.payment', $order->id) }}" class="hover:text-[#C8A951] transition-colors">Try paying again</a>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-[#C8A951] mt-0.5">→</span>
                            <span>Choose a different payment method</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-[#C8A951] mt-0.5">→</span>
                            <a href="{{ route('orders.show', $order->id) }}" class="hover:text-[#C8A951] transition-colors">Contact support from your order page</a>
                        </li>
                    </ul>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 justify-center">
                    <a href="{{ route('checkout.payment', $order->id) }}" class="block text-center px-8 py-3.5 bg-[#C8A951] text-black text-xs tracking-widest uppercase font-medium hover:bg-white transition-all duration-500">
                        Try Again
                    </a>
                    <a href="{{ route('orders.show', $order->id) }}" class="block text-center px-8 py-3.5 border border-white/20 text-white text-xs tracking-widest uppercase font-light hover:bg-white/5 transition-all duration-500">
                        View Order
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

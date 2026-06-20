<x-app-layout>
    <div class="pt-28 pb-24 min-h-screen bg-[#0a0a0a]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-10 reveal">
                <h1 class="text-3xl font-light tracking-tight text-white">My Orders</h1>
                <p class="text-xs font-light text-gray-500 mt-1">{{ $orders->total() }} total orders</p>
            </div>

            @if($orders->isNotEmpty())
            <div class="space-y-4">
                @foreach($orders as $order)
                <a href="{{ route('orders.show', $order->id) }}" class="block glass-card p-6 hover:border-[#C8A951]/20 transition-all duration-500 group" data-stagger>
                    <div class="flex flex-wrap items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center group-hover:bg-[#C8A951]/10 transition-colors">
                                <svg class="w-5 h-5 text-gray-500 group-hover:text-[#C8A951] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            </div>
                            <div>
                                <p class="text-sm font-light text-white/90">#{{ $order->invoice_number }}</p>
                                <p class="text-xs font-light text-gray-500">{{ $order->created_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <span class="text-xs font-light text-gray-500">{{ $order->items->count() }} items</span>
                            <span class="text-sm font-light text-white">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                            <span class="px-3 py-1 text-[10px] tracking-wider uppercase font-light
                                @switch($order->status)
                                    @case('pending') text-yellow-400 bg-yellow-500/10 border border-yellow-500/20 @break
                                    @case('processing') text-blue-400 bg-blue-500/10 border border-blue-500/20 @break
                                    @case('shipped') text-purple-400 bg-purple-500/10 border border-purple-500/20 @break
                                    @case('completed') text-green-400 bg-green-500/10 border border-green-500/20 @break
                                    @case('cancelled') text-red-400 bg-red-500/10 border border-red-500/20 @break
                                    @default text-gray-400 bg-white/5 border border-white/10
                                @endswitch">
                                {{ ucfirst($order->status) }}
                            </span>
                            <svg class="w-4 h-4 text-gray-600 group-hover:text-[#C8A951] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
            <div class="mt-8">{{ $orders->links() }}</div>
            @else
            <div class="text-center py-20 max-w-md mx-auto reveal">
                <div class="w-20 h-20 mx-auto rounded-full bg-white/5 border border-white/10 flex items-center justify-center mb-6">
                    <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <h3 class="text-sm tracking-widest uppercase font-light text-white/60 mb-2">No Orders Yet</h3>
                <p class="text-xs font-light text-gray-500 mb-8">Start shopping to create your first order</p>
                <a href="{{ route('shop') }}" class="text-xs tracking-widest uppercase font-light text-[#C8A951] border-b border-[#C8A951]/30 pb-0.5 hover:text-white hover:border-white transition-colors">
                    Shop Now
                </a>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <div class="pt-24 pb-16 min-h-screen bg-gray-50 dark:bg-[#111]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold mb-8">My Orders</h1>

            @if($orders->isNotEmpty())
            <div class="space-y-4">
                @foreach($orders as $order)
                <a href="{{ route('orders.show', $order->id) }}" class="block bg-white dark:bg-[#1a1a1a] rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex flex-wrap items-center justify-between gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Order #{{ $order->invoice_number }}</p>
                            <p class="text-xs text-gray-400">{{ $order->created_at->format('d M Y H:i') }}</p>
                        </div>
                        <div class="flex items-center gap-4">
                            <span class="text-sm font-medium">{{ $order->items->count() }} items</span>
                            <span class="font-semibold">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                            <span class="px-3 py-1 rounded-full text-xs font-medium 
                                @switch($order->status)
                                    @case('pending') bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 @break
                                    @case('paid') bg-blue-100 text-blue-700 dark:bg-blue-900/30 @break
                                    @case('process') bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 @break
                                    @case('shipped') bg-purple-100 text-purple-700 dark:bg-purple-900/30 @break
                                    @case('completed') bg-green-100 text-green-700 dark:bg-green-900/30 @break
                                    @case('cancelled') bg-red-100 text-red-700 dark:bg-red-900/30 @break
                                @endswitch">
                                {{ ucfirst($order->status) }}
                            </span>
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
            <div class="mt-8">{{ $orders->links() }}</div>
            @else
            <div class="text-center py-20">
                <svg class="w-20 h-20 mx-auto text-gray-300 dark:text-gray-600 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                <h3 class="text-xl font-medium text-gray-500 mb-2">No orders yet</h3>
                <p class="text-gray-400 mb-6">Start shopping to create your first order</p>
                <a href="{{ route('shop') }}" class="btn-primary inline-flex">Shop Now</a>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>

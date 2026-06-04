<x-app-layout>
    <div class="pt-24 pb-16 min-h-screen bg-gray-50 dark:bg-[#111]">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <a href="{{ route('orders') }}" class="text-sm text-gray-500 hover:text-[#C8A951] mb-2 inline-flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        Back to Orders
                    </a>
                    <h1 class="text-2xl font-bold">Order #{{ $order->invoice_number }}</h1>
                    <p class="text-sm text-gray-500">{{ $order->created_at->format('d M Y H:i') }}</p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('orders.invoice', $order->id) }}" class="px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-xl text-sm hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                        Download Invoice
                    </a>
                </div>
            </div>

            <!-- Status Timeline -->
            @php
                $statusLabels = [
                    'pending' => ['label' => 'Menunggu Pembayaran', 'icon' => 'clock'],
                    'paid' => ['label' => 'Pembayaran Dikonfirmasi', 'icon' => 'check'],
                    'process' => ['label' => 'Sedang Diproses', 'icon' => 'package'],
                    'shipped' => ['label' => 'Pesanan Dikirim', 'icon' => 'truck'],
                    'completed' => ['label' => 'Pesanan Selesai', 'icon' => 'check'],
                    'cancelled' => ['label' => 'Pesanan Dibatalkan', 'icon' => 'close'],
                ];
                $trackings = $order->trackings;
                $currentStatusIndex = $trackings->count() - 1;
            @endphp
            <div class="bg-white dark:bg-[#1a1a1a] rounded-xl shadow-sm mb-6 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
                    <h3 class="font-semibold">Status Pesanan</h3>
                    <span class="text-sm font-medium px-3 py-1 rounded-full
                        @if($order->status === 'completed') bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400
                        @elseif($order->status === 'cancelled') bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400
                        @elseif($order->status === 'shipped') bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400
                        @else bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400 @endif">
                        {{ $statusLabels[$order->status]['label'] ?? ucfirst($order->status) }}
                    </span>
                </div>
                <div class="p-6">
                    @if($order->status === 'cancelled')
                    <div class="flex items-center gap-4 p-4 bg-red-50 dark:bg-red-900/10 rounded-xl mb-6">
                        <div class="w-12 h-12 rounded-full bg-red-100 dark:bg-red-900/20 flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </div>
                        <div>
                            <p class="font-semibold text-red-600 dark:text-red-400">Pesanan Dibatalkan</p>
                            <p class="text-sm text-red-500/70">Pesanan ini telah dibatalkan</p>
                        </div>
                    </div>
                    @endif

                    <div class="relative">
                        <div class="absolute left-5 top-2 bottom-2 w-0.5 bg-gray-200 dark:bg-gray-700"></div>
                        <div class="space-y-0">
                            @forelse($trackings as $index => $tracking)
                            @php
                                $isCompleted = $index < $currentStatusIndex || ($index === $currentStatusIndex && $order->status !== 'cancelled');
                                $isCurrent = $index === $currentStatusIndex;
                            @endphp
                            <div class="relative flex gap-5 pb-8 {{ $loop->last ? 'pb-0' : '' }}">
                                <div class="relative z-10 shrink-0">
                                    @if($tracking->status === 'cancelled')
                                    <div class="w-10 h-10 rounded-full bg-red-100 dark:bg-red-900/20 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </div>
                                    @elseif($isCompleted)
                                    <div class="w-10 h-10 rounded-full bg-green-100 dark:bg-green-900/20 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    </div>
                                    @elseif($isCurrent)
                                    <div class="w-10 h-10 rounded-full bg-[#C8A951]/20 flex items-center justify-center animate-pulse">
                                        <svg class="w-5 h-5 text-[#C8A951]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                    @else
                                    <div class="w-10 h-10 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0 pt-1.5">
                                    <p class="font-medium text-sm {{ $isCurrent && $order->status !== 'cancelled' ? 'text-[#C8A951]' : ($isCompleted ? 'text-gray-900 dark:text-gray-100' : 'text-gray-400') }}">
                                        {{ $statusLabels[$tracking->status]['label'] ?? ucfirst($tracking->status) }}
                                    </p>
                                    <p class="text-xs text-gray-500 mt-0.5">{{ $tracking->description }}</p>
                                    <p class="text-xs text-gray-400 mt-0.5">{{ $tracking->created_at->format('d M Y H:i') }}</p>
                                </div>
                            </div>
                            @empty
                            <div class="text-center py-8 text-gray-500 text-sm">No tracking data available yet.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="bg-white dark:bg-[#1a1a1a] rounded-xl p-6 shadow-sm mb-6">
                <h3 class="font-semibold mb-4">Order Items</h3>
                <div class="space-y-4">
                    @foreach($order->items as $item)
                    <div class="flex gap-4">
                        <div class="w-16 h-16 rounded-xl overflow-hidden bg-gray-100 dark:bg-gray-800 shrink-0">
                            @php $img = $item->product && $item->product->thumbnail ? (str_starts_with($item->product->thumbnail, 'http') ? $item->product->thumbnail : Storage::url($item->product->thumbnail) . '?t=' . $item->product->updated_at->timestamp) : 'https://picsum.photos/seed/order-'.$item->id.'/200/200'; @endphp
                            <img src="{{ $img }}" alt="" class="w-full h-full object-cover">
                        </div>
                        <div class="flex-1 flex justify-between">
                            <div>
                                <p class="font-medium">{{ $item->product_name }}</p>
                                <div class="flex gap-3 text-sm text-gray-500 mt-1">
                                    @if($item->size) <span>Size: {{ $item->size }}</span> @endif
                                    @if($item->color) <span>Color: {{ $item->color }}</span> @endif
                                    <span>Qty: {{ $item->quantity }}</span>
                                </div>
                            </div>
                            <p class="font-semibold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Order Summary -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white dark:bg-[#1a1a1a] rounded-xl p-6 shadow-sm">
                    <h3 class="font-semibold mb-4">Shipping Info</h3>
                    <div class="space-y-2 text-sm">
                        <p><span class="text-gray-500">Address:</span> {{ $order->address }}</p>
                        <p><span class="text-gray-500">City:</span> {{ $order->city }}</p>
                        <p><span class="text-gray-500">Phone:</span> {{ $order->phone }}</p>
                        <p><span class="text-gray-500">Courier:</span> {{ $order->shipping_courier }}</p>
                    </div>
                </div>
                <div class="bg-white dark:bg-[#1a1a1a] rounded-xl p-6 shadow-sm">
                    <h3 class="font-semibold mb-4">Payment Summary</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between"><span class="text-gray-500">Subtotal</span><span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span></div>
                        <div class="flex justify-between"><span class="text-gray-500">Shipping</span><span>Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span></div>
                        @if($order->discount > 0)
                        <div class="flex justify-between"><span class="text-gray-500">Discount</span><span class="text-green-500">-Rp {{ number_format($order->discount, 0, ',', '.') }}</span></div>
                        @endif
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-2 flex justify-between font-semibold">
                            <span>Total</span>
                            <span>Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between mt-2">
                            <span class="text-gray-500">Payment</span>
                            <span>{{ $order->payment_method }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Status</span>
                            <span class="font-medium 
                                @if($order->payment_status == 'success') text-green-500
                                @elseif($order->payment_status == 'failed') text-red-500
                                @else text-yellow-500 @endif">
                                {{ ucfirst($order->payment_status) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            @if($order->payment_method != 'COD' && $order->payment_status == 'pending')
            <!-- Payment Instructions & Reference -->
            <div class="mt-6 bg-blue-50 dark:bg-blue-900/10 border border-blue-200 dark:border-blue-900/30 rounded-xl p-6 shadow-sm">
                <h3 class="font-semibold mb-4 text-blue-900 dark:text-blue-400">Payment Instructions</h3>
                @php
                    $paymentAccount = \App\Models\PaymentAccount::where('method', $order->payment_method)->where('active', true)->first();
                @endphp
                @if($paymentAccount)
                <div class="space-y-3 text-sm">
                    @if($order->payment_method == 'Bank Transfer')
                    <div>
                        <p class="text-gray-600 dark:text-gray-400">Transfer to:</p>
                        <p class="font-mono font-bold text-lg text-blue-600 dark:text-blue-400">{{ $paymentAccount->account_number }}</p>
                        <p class="text-gray-700 dark:text-gray-300">{{ $paymentAccount->account_name }}</p>
                    </div>
                    @else
                    <div>
                        <p class="text-gray-600 dark:text-gray-400">{{ $paymentAccount->account_name }}</p>
                        <p class="font-mono font-bold text-lg text-blue-600 dark:text-blue-400">{{ $paymentAccount->account_number }}</p>
                    </div>
                    @endif
                    @if($paymentAccount->instructions)
                    <p class="text-gray-600 dark:text-gray-400 border-t border-blue-200 dark:border-blue-900/30 pt-2">{{ $paymentAccount->instructions }}</p>
                    @endif
                </div>
                @endif
                @if($order->payment->payment_number)
                <div class="mt-4 pt-4 border-t border-blue-200 dark:border-blue-900/30">
                    <p class="text-gray-600 dark:text-gray-400 mb-1">Payment Reference:</p>
                    <p class="font-mono font-bold text-lg text-green-600 dark:text-green-400 break-all">{{ $order->payment->payment_number }}</p>
                    <p class="text-xs text-gray-500 mt-2">📋 Salin nomor referensi ini saat melakukan pembayaran</p>
                </div>
                @endif
            </div>
            @endif

            @if($order->payment_method != 'COD' && $order->payment_status == 'pending')
            <!-- Upload Payment -->
            <div class="mt-6 bg-white dark:bg-[#1a1a1a] rounded-xl p-6 shadow-sm">
                <h3 class="font-semibold mb-4">Upload Payment Proof</h3>
                <form action="{{ route('payment.upload', $order->id) }}" method="POST" enctype="multipart/form-data" class="flex items-center gap-4">
                    @csrf
                    <input type="file" name="proof_image" accept="image/*" required class="input-field">
                    <button type="submit" class="btn-primary">Upload</button>
                </form>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>

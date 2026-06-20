<x-app-layout>
    <div class="pt-28 pb-24 min-h-screen bg-[#0a0a0a]">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-8 reveal">
                <div>
                    <a href="{{ route('orders') }}" class="text-[10px] tracking-widest uppercase font-light text-gray-500 hover:text-[#C8A951] inline-flex items-center gap-2 mb-3 transition-colors">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        Back to Orders
                    </a>
                    <h1 class="text-2xl font-light tracking-tight text-white">Order #{{ $order->invoice_number }}</h1>
                    <p class="text-xs font-light text-gray-500 mt-1">{{ $order->created_at->format('d M Y H:i') }}</p>
                </div>
                <div>
                    <a href="{{ route('orders.invoice', $order->id) }}" class="inline-flex items-center gap-2 px-5 py-2.5 border border-white/10 text-white text-[10px] tracking-widest uppercase font-light hover:bg-white/5 hover:border-[#C8A951]/30 transition-all duration-300">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Invoice
                    </a>
                </div>
            </div>

            <!-- Status Timeline -->
            @php
                $statusLabels = [
                    'pending' => ['label' => 'Menunggu Pembayaran', 'icon' => 'clock'],
                    'processing' => ['label' => 'Sedang Diproses', 'icon' => 'package'],
                    'shipped' => ['label' => 'Pesanan Dikirim', 'icon' => 'truck'],
                    'completed' => ['label' => 'Pesanan Selesai', 'icon' => 'check'],
                    'cancelled' => ['label' => 'Pesanan Dibatalkan', 'icon' => 'close'],
                ];
                $trackings = $order->trackings;
                $currentStatusIndex = $trackings->count() - 1;
            @endphp
            <div class="glass-card mb-6 reveal" data-stagger>
                <div class="px-6 py-4 border-b border-white/5 flex items-center justify-between">
                    <h3 class="text-xs tracking-widest uppercase font-light text-gray-300">Status Timeline</h3>
                    <span class="text-[10px] tracking-wider uppercase font-light px-3 py-1
                        @if($order->status === 'completed') text-green-400 bg-green-500/10 border border-green-500/20
                        @elseif($order->status === 'cancelled') text-red-400 bg-red-500/10 border border-red-500/20
                        @elseif($order->status === 'shipped') text-purple-400 bg-purple-500/10 border border-purple-500/20
                        @elseif($order->status === 'processing') text-blue-400 bg-blue-500/10 border border-blue-500/20
                        @else text-yellow-400 bg-yellow-500/10 border border-yellow-500/20 @endif">
                        {{ $statusLabels[$order->status]['label'] ?? ucfirst($order->status) }}
                    </span>
                </div>
                <div class="p-6">
                    @if($order->status === 'cancelled')
                    <div class="flex items-center gap-4 p-4 bg-red-500/5 border border-red-500/10 mb-6">
                        <div class="w-10 h-10 rounded-full bg-red-500/10 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-light text-red-400">Pesanan Dibatalkan</p>
                            <p class="text-xs font-light text-red-400/60">Pesanan ini telah dibatalkan</p>
                        </div>
                    </div>
                    @endif

                    <div class="relative">
                        <div class="absolute left-5 top-2 bottom-2 w-px bg-white/10"></div>
                        <div class="space-y-0">
                            @forelse($trackings as $index => $tracking)
                            @php
                                $isCompleted = $index < $currentStatusIndex || ($index === $currentStatusIndex && $order->status !== 'cancelled');
                                $isCurrent = $index === $currentStatusIndex;
                            @endphp
                            <div class="relative flex gap-5 pb-8 {{ $loop->last ? 'pb-0' : '' }}">
                                <div class="relative z-10 shrink-0">
                                    @if($tracking->status === 'cancelled')
                                    <div class="w-10 h-10 rounded-full bg-red-500/10 border border-red-500/20 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </div>
                                    @elseif($isCompleted)
                                    <div class="w-10 h-10 rounded-full bg-green-500/10 border border-green-500/20 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    </div>
                                    @elseif($isCurrent)
                                    <div class="w-10 h-10 rounded-full bg-[#C8A951]/10 border border-[#C8A951]/30 flex items-center justify-center animate-pulse">
                                        <svg class="w-5 h-5 text-[#C8A951]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                    @else
                                    <div class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0 pt-1.5">
                                    <p class="text-sm font-light {{ $isCurrent && $order->status !== 'cancelled' ? 'text-[#C8A951]' : ($isCompleted ? 'text-white/90' : 'text-gray-500') }}">
                                        {{ $statusLabels[$tracking->status]['label'] ?? ucfirst($tracking->status) }}
                                    </p>
                                    <p class="text-xs font-light text-gray-500 mt-0.5">{{ $tracking->description }}</p>
                                    <p class="text-[10px] font-light text-gray-600 mt-0.5">{{ $tracking->created_at->format('d M Y H:i') }}</p>
                                </div>
                            </div>
                            @empty
                            <div class="text-center py-8 text-gray-500 text-sm font-light">No tracking data available yet.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="glass-card p-6 mb-6 reveal" data-stagger>
                <h3 class="text-xs tracking-widest uppercase font-light text-gray-300 mb-6">Order Items</h3>
                <div class="space-y-4">
                    @foreach($order->items as $item)
                    <div class="flex gap-4 pb-4 {{ !$loop->last ? 'border-b border-white/5' : '' }}">
                        <div class="w-16 h-16 overflow-hidden bg-[#111] shrink-0 rounded-sm">
                            @php $img = $item->product && $item->product->thumbnail ? (str_starts_with($item->product->thumbnail, 'http') ? $item->product->thumbnail : Storage::url($item->product->thumbnail) . '?t=' . $item->product->updated_at->timestamp) : 'https://picsum.photos/seed/order-'.$item->id.'/200/200'; @endphp
                            <img src="{{ $img }}" alt="" class="w-full h-full object-cover">
                        </div>
                        <div class="flex-1 flex justify-between">
                            <div>
                                <p class="text-sm font-light text-white/90">{{ $item->product_name }}</p>
                                <div class="flex gap-3 text-xs font-light text-gray-500 mt-1">
                                    @if($item->size) <span>Size: {{ $item->size }}</span> @endif
                                    @if($item->color) <span>Color: {{ $item->color }}</span> @endif
                                    <span>Qty: {{ $item->quantity }}</span>
                                </div>
                            </div>
                            <p class="text-sm font-light text-white">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Order Info Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 reveal" data-stagger>
                <div class="glass-card p-6">
                    <h3 class="text-xs tracking-widest uppercase font-light text-gray-300 mb-5 flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#C8A951]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Shipping Info
                    </h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between text-xs font-light">
                            <span class="text-gray-500">Recipient</span>
                            <span class="text-white">{{ $order->recipient_name ?? $order->user->name ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between text-xs font-light">
                            <span class="text-gray-500">Address</span>
                            <span class="text-white text-right max-w-[200px]">{{ $order->address }}</span>
                        </div>
                        <div class="flex justify-between text-xs font-light">
                            <span class="text-gray-500">City</span>
                            <span class="text-white">{{ $order->city }}</span>
                        </div>
                        <div class="flex justify-between text-xs font-light">
                            <span class="text-gray-500">Phone</span>
                            <span class="text-white">{{ $order->phone }}</span>
                        </div>
                        <div class="flex justify-between text-xs font-light">
                            <span class="text-gray-500">Courier</span>
                            <span class="text-white">{{ $order->shipping_courier }}</span>
                        </div>
                    </div>
                </div>
                <div class="glass-card p-6">
                    <h3 class="text-xs tracking-widest uppercase font-light text-gray-300 mb-5 flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#C8A951]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                        Payment Summary
                    </h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between text-xs font-light"><span class="text-gray-500">Subtotal</span><span class="text-white">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span></div>
                        <div class="flex justify-between text-xs font-light"><span class="text-gray-500">Shipping</span><span class="text-white">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span></div>
                        @if($order->discount > 0)
                        <div class="flex justify-between text-xs font-light"><span class="text-gray-500">Discount</span><span class="text-green-400">-Rp {{ number_format($order->discount, 0, ',', '.') }}</span></div>
                        @endif
                        <div class="border-t border-white/5 pt-2 flex justify-between text-sm text-white">
                            <span class="font-light">Total</span>
                            <span class="text-[#C8A951]">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-xs font-light pt-2">
                            <span class="text-gray-500">Method</span>
                            <span class="text-white">{{ $order->payment_method }}</span>
                        </div>
                        <div class="flex justify-between text-xs font-light">
                            <span class="text-gray-500">Status</span>
                            <span class="font-light
                                @if($order->payment_status == 'paid' || $order->payment_status == 'success') text-green-400
                                @elseif($order->payment_status == 'failed') text-red-400
                                @else text-yellow-400 @endif">
                                {{ ucfirst($order->payment_status) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            @if($order->payment_method != 'COD' && $order->payment_status == 'pending')
            <!-- Payment Instructions -->
            <div class="mt-6 glass-card p-6 reveal" data-stagger>
                <h3 class="text-xs tracking-widest uppercase font-light text-gray-300 mb-5 flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#C8A951]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Payment Instructions
                </h3>
                @php
                    $paymentAccount = \App\Models\PaymentAccount::where('method', $order->payment_method)->where('active', true)->first();
                @endphp
                @if($paymentAccount)
                <div class="space-y-4 text-sm">
                    @if($order->payment_method == 'Bank Transfer')
                    <div class="bg-white/5 border border-white/10 p-4">
                        <p class="text-[10px] tracking-widest uppercase font-light text-gray-500 mb-2">Transfer to</p>
                        <p class="font-mono text-lg tracking-wider text-white">{{ $paymentAccount->account_number }}</p>
                        <p class="text-sm font-light text-gray-400">{{ $paymentAccount->account_name }}</p>
                    </div>
                    @else
                    <div class="bg-white/5 border border-white/10 p-4">
                        <p class="text-[10px] tracking-widest uppercase font-light text-gray-500 mb-2">{{ $paymentAccount->account_name }}</p>
                        <p class="font-mono text-lg tracking-wider text-white">{{ $paymentAccount->account_number }}</p>
                    </div>
                    @endif
                    @if($paymentAccount->instructions)
                    <p class="text-xs font-light text-gray-400 leading-relaxed">{{ $paymentAccount->instructions }}</p>
                    @endif
                </div>
                @endif
                @if($order->payment->payment_number)
                <div class="mt-4 pt-4 border-t border-white/5">
                    <p class="text-[10px] tracking-widest uppercase font-light text-gray-500 mb-2">Payment Reference</p>
                    <div class="flex items-center gap-3">
                        <p class="font-mono text-sm tracking-wider text-[#C8A951] break-all">{{ $order->payment->payment_number }}</p>
                        <button onclick="navigator.clipboard.writeText('{{ $order->payment->payment_number }}').then(()=>{this.textContent='Copied!'}).catch(()=>{})" class="text-[10px] font-light text-white/60 hover:text-[#C8A951] transition-colors shrink-0 border border-white/10 hover:border-[#C8A951]/30 px-3 py-1.5">
                            Copy
                        </button>
                    </div>
                    <p class="text-[10px] text-gray-600 mt-2">Include this reference when making payment</p>
                </div>
                @endif
            </div>
            @endif

            @if($order->payment_method != 'COD' && $order->payment_status == 'pending')
            <!-- Upload Payment Proof -->
            <div class="mt-6 glass-card p-6 reveal" data-stagger>
                <h3 class="text-xs tracking-widest uppercase font-light text-gray-300 mb-5 flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#C8A951]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Upload Payment Proof
                </h3>
                <form action="{{ route('payment.upload', $order->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="flex items-center gap-4">
                        <div class="border-2 border-dashed border-white/10 hover:border-[#C8A951]/30 transition-colors duration-300 p-4 text-center cursor-pointer flex-1" onclick="document.getElementById('proof-input-{{ $order->id }}').click()">
                            <p class="text-[10px] font-light text-gray-500">Click to upload proof</p>
                            <input id="proof-input-{{ $order->id }}" type="file" name="proof_image" accept="image/jpeg,image/png,image/webp" required class="hidden" onchange="this.closest('div').querySelector('.file-name').textContent = this.files[0]?.name || ''">
                            <p class="file-name text-[10px] text-[#C8A951] mt-1 font-light"></p>
                        </div>
                        <button type="submit" class="bg-[#C8A951] text-black text-xs tracking-widest uppercase font-medium px-6 py-3 hover:bg-white transition-all duration-500 shrink-0">
                            Upload
                        </button>
                    </div>
                </form>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>

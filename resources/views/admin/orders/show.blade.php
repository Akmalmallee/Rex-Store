@extends('layouts.admin')

@section('title', 'Order #'.$order->invoice_number)

@section('content')
<div class="max-w-5xl mx-auto space-y-8">
    <div class="flex items-start justify-between">
        <div>
            <a href="{{ route('admin.orders.index') }}" class="text-xs tracking-widest uppercase font-light text-gray-500 hover:text-[#C8A951]">&larr; Back to Orders</a>
            <h1 class="text-3xl font-light tracking-tight text-white mt-2">Order #{{ $order->invoice_number }}</h1>
            <p class="text-xs font-light text-gray-500 mt-1">{{ $order->created_at->format('d M Y H:i') }}</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.orders.export-pdf', $order->id) }}" class="btn-luxury-outline px-5 py-2.5 text-xs">
                Export PDF
            </a>
            <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" onsubmit="return confirm('Delete this order?')">
                @csrf @method('DELETE')
                <button class="text-xs tracking-widest uppercase font-light px-5 py-2.5 border border-red-500/20 text-red-400 hover:bg-red-500/10 transition-all duration-500">Delete</button>
            </form>
        </div>
    </div>

    <!-- Status Update -->
    <div class="glass-card p-6">
        <h3 class="text-xs tracking-widest uppercase font-light text-gray-300 mb-4">Update Status</h3>
        <form action="{{ route('admin.orders.status', $order->id) }}" method="POST" class="flex items-center gap-3">
            @csrf @method('PATCH')
            <select name="status" class="bg-transparent border-0 border-b border-white/10 text-white text-xs font-light py-3 w-full max-w-xs focus:border-[#C8A951] focus:ring-0 focus:outline-none">
                @foreach(['pending','paid','process','shipped','completed','cancelled'] as $s)
                <option value="{{ $s }}" {{ $order->status == $s ? 'selected' : '' }} class="bg-[#0a0a0a]">{{ ucfirst($s) }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn-luxury px-5 py-3 text-xs">Update</button>
        </form>
    </div>

    <!-- Tracking Timeline -->
    @if($order->trackings->isNotEmpty())
    <div class="glass-card p-6">
        <h3 class="text-xs tracking-widest uppercase font-light text-gray-300 mb-6">Tracking Timeline</h3>
        <div class="relative">
            <div class="absolute left-[17px] top-2 bottom-2 w-px bg-white/5"></div>
            <div class="space-y-0">
                @foreach($order->trackings->reverse() as $index => $tracking)
                @php
                    $count = $order->trackings->count();
                    $isLast = $index === $count - 1;
                @endphp
                <div class="relative flex gap-4 pb-6 {{ $isLast ? 'pb-0' : '' }}">
                    <div class="relative z-10 shrink-0">
                        @if($tracking->status === 'cancelled')
                        <div class="w-[34px] h-[34px] border border-red-500/30 bg-red-500/5 flex items-center justify-center">
                            <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"/></svg>
                        </div>
                        @elseif($tracking->status === 'completed')
                        <div class="w-[34px] h-[34px] border border-emerald-500/30 bg-emerald-500/5 flex items-center justify-center">
                            <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        @elseif($isLast)
                        <div class="w-[34px] h-[34px] border border-[#C8A951]/30 bg-[#C8A951]/5 flex items-center justify-center">
                            <svg class="w-4 h-4 text-[#C8A951]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        @else
                        <div class="w-[34px] h-[34px] border border-white/10 bg-white/5 flex items-center justify-center">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0 pt-1">
                        <p class="text-sm font-light text-white/80 capitalize">{{ $tracking->status }}</p>
                        <p class="text-xs font-light text-gray-500">{{ $tracking->description }}</p>
                        <p class="text-xs font-light text-gray-600 mt-0.5">{{ $tracking->created_at->format('d M Y H:i') }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Order Items -->
    <div class="glass-card overflow-hidden">
        <div class="p-6 border-b border-white/5">
            <h3 class="text-xs tracking-widest uppercase font-light text-gray-300">Order Items</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-white/[0.02] border-b border-white/5">
                        <th class="text-left py-3 px-4 text-[10px] tracking-widest uppercase font-light text-gray-500">Product</th>
                        <th class="text-left py-3 px-4 text-[10px] tracking-widest uppercase font-light text-gray-500">Size/Color</th>
                        <th class="text-center py-3 px-4 text-[10px] tracking-widest uppercase font-light text-gray-500">Qty</th>
                        <th class="text-right py-3 px-4 text-[10px] tracking-widest uppercase font-light text-gray-500">Price</th>
                        <th class="text-right py-3 px-4 text-[10px] tracking-widest uppercase font-light text-gray-500">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr class="border-b border-white/5">
                        <td class="py-3 px-4 text-xs font-light text-white/80">{{ $item->product_name }}</td>
                        <td class="py-3 px-4 text-xs font-light text-gray-500">{{ $item->size ?? '-' }} / {{ $item->color ?? '-' }}</td>
                        <td class="py-3 px-4 text-xs font-light text-white/60 text-center">{{ $item->quantity }}</td>
                        <td class="py-3 px-4 text-xs font-light text-white/60 text-right">Rp {{ number_format($item->product_price, 0, ',', '.') }}</td>
                        <td class="py-3 px-4 text-xs font-light text-white/80 text-right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr><td colspan="4" class="text-right py-2 px-4 text-xs font-light text-gray-500">Subtotal</td><td class="text-right py-2 px-4 text-xs font-light text-white/60">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</td></tr>
                    <tr><td colspan="4" class="text-right py-2 px-4 text-xs font-light text-gray-500">Shipping</td><td class="text-right py-2 px-4 text-xs font-light text-white/60">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</td></tr>
                    @if($order->discount > 0)
                    <tr><td colspan="4" class="text-right py-2 px-4 text-xs font-light text-gray-500">Discount</td><td class="text-right py-2 px-4 text-xs font-light text-emerald-400">-Rp {{ number_format($order->discount, 0, ',', '.') }}</td></tr>
                    @endif
                    <tr class="border-t border-white/5"><td colspan="4" class="text-right py-3 px-4 text-sm font-light text-white">Total</td><td class="text-right py-3 px-4 text-sm font-light text-[#C8A951]">Rp {{ number_format($order->total, 0, ',', '.') }}</td></tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- Customer Info -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="glass-card p-6">
            <h3 class="text-xs tracking-widest uppercase font-light text-gray-300 mb-4">Customer Info</h3>
            <div class="space-y-2 text-sm">
                <p class="text-xs font-light text-gray-500"><span class="text-gray-600">Name:</span> <span class="text-white/80">{{ $order->user->name ?? 'Guest' }}</span></p>
                <p class="text-xs font-light text-gray-500"><span class="text-gray-600">Email:</span> <span class="text-white/80">{{ $order->user->email ?? '-' }}</span></p>
                <p class="text-xs font-light text-gray-500"><span class="text-gray-600">Phone:</span> <span class="text-white/80">{{ $order->phone }}</span></p>
            </div>
        </div>
        <div class="glass-card p-6">
            <h3 class="text-xs tracking-widest uppercase font-light text-gray-300 mb-4">Shipping Info</h3>
            <div class="space-y-2 text-sm">
                <p class="text-xs font-light text-gray-500"><span class="text-gray-600">Address:</span> <span class="text-white/80">{{ $order->address }}</span></p>
                <p class="text-xs font-light text-gray-500"><span class="text-gray-600">City:</span> <span class="text-white/80">{{ $order->city }}</span></p>
                <p class="text-xs font-light text-gray-500"><span class="text-gray-600">Courier:</span> <span class="text-white/80">{{ $order->shipping_courier }}</span></p>
                <p class="text-xs font-light text-gray-500"><span class="text-gray-600">Notes:</span> <span class="text-white/80">{{ $order->notes ?? '-' }}</span></p>
            </div>
        </div>
        <div class="glass-card p-6">
            <h3 class="text-xs tracking-widest uppercase font-light text-gray-300 mb-4">Payment Info</h3>
            <div class="space-y-2 text-sm">
                <p class="text-xs font-light text-gray-500"><span class="text-gray-600">Method:</span> <span class="text-white/80">{{ $order->payment_method }}</span></p>
                <p class="text-xs font-light text-gray-500"><span class="text-gray-600">Status:</span>
                    <span class="text-[10px] tracking-wider px-2 py-0.5 font-light ml-1 {{ $order->payment_status == 'success' ? 'border border-emerald-500/30 text-emerald-400' : ($order->payment_status == 'failed' ? 'border border-red-500/30 text-red-400' : 'border border-yellow-500/30 text-yellow-400') }}">
                        {{ ucfirst($order->payment_status) }}
                    </span>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

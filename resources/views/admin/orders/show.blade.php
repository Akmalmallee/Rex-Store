@extends('layouts.admin')

@section('title', 'Order #'.$order->invoice_number)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('admin.orders.index') }}" class="text-sm text-gray-500 hover:text-[#C8A951]">&larr; Back to Orders</a>
            <h1 class="text-2xl font-bold mt-2">Order #{{ $order->invoice_number }}</h1>
            <p class="text-gray-500">{{ $order->created_at->format('d M Y H:i') }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.orders.export-pdf', $order->id) }}" class="px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-xl text-sm hover:bg-gray-50 dark:hover:bg-gray-800">
                Export PDF
            </a>
            <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" onsubmit="return confirm('Delete this order?')">
                @csrf @method('DELETE')
                <button class="px-4 py-2 border border-red-200 text-red-500 rounded-xl text-sm hover:bg-red-50">Delete</button>
            </form>
        </div>
    </div>

    <!-- Status Update -->
    <div class="bg-white dark:bg-[#1a1a1a] rounded-xl p-6 shadow-sm">
        <h3 class="font-semibold mb-4">Update Status</h3>
        <form action="{{ route('admin.orders.status', $order->id) }}" method="POST" class="flex items-center gap-3">
            @csrf @method('PATCH')
            <select name="status" class="input-field max-w-xs">
                @foreach(['pending','paid','process','shipped','completed','cancelled'] as $s)
                <option value="{{ $s }}" {{ $order->status == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
            <button type="submit" class="px-4 py-2.5 bg-[#C8A951] hover:bg-[#b8963e] text-white rounded-xl text-sm">Update</button>
        </form>
    </div>

    <!-- Tracking Timeline -->
    @if($order->trackings->isNotEmpty())
    <div class="bg-white dark:bg-[#1a1a1a] rounded-xl p-6 shadow-sm">
        <h3 class="font-semibold mb-4">Tracking Timeline</h3>
        <div class="relative">
            <div class="absolute left-[17px] top-2 bottom-2 w-0.5 bg-gray-200 dark:bg-gray-700"></div>
            <div class="space-y-0">
                @foreach($order->trackings->reverse() as $index => $tracking)
                @php
                    $count = $order->trackings->count();
                    $isLast = $index === $count - 1;
                @endphp
                <div class="relative flex gap-4 pb-6 {{ $isLast ? 'pb-0' : '' }}">
                    <div class="relative z-10 shrink-0">
                        @if($tracking->status === 'cancelled')
                        <div class="w-[34px] h-[34px] rounded-full bg-red-100 dark:bg-red-900/20 flex items-center justify-center">
                            <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </div>
                        @elseif($tracking->status === 'completed')
                        <div class="w-[34px] h-[34px] rounded-full bg-green-100 dark:bg-green-900/20 flex items-center justify-center">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        @elseif($isLast)
                        <div class="w-[34px] h-[34px] rounded-full bg-[#C8A951]/20 flex items-center justify-center">
                            <svg class="w-4 h-4 text-[#C8A951]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        @else
                        <div class="w-[34px] h-[34px] rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0 pt-1">
                        <p class="text-sm font-medium capitalize">{{ $tracking->status }}</p>
                        <p class="text-xs text-gray-500">{{ $tracking->description }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">{{ $tracking->created_at->format('d M Y H:i') }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Order Items -->
    <div class="bg-white dark:bg-[#1a1a1a] rounded-xl p-6 shadow-sm">
        <h3 class="font-semibold mb-4">Order Items</h3>
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-200 dark:border-gray-800">
                    <th class="text-left py-2 font-medium text-gray-500">Product</th>
                    <th class="text-left py-2 font-medium text-gray-500">Size/Color</th>
                    <th class="text-center py-2 font-medium text-gray-500">Qty</th>
                    <th class="text-right py-2 font-medium text-gray-500">Price</th>
                    <th class="text-right py-2 font-medium text-gray-500">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr class="border-b border-gray-100 dark:border-gray-800">
                    <td class="py-3">{{ $item->product_name }}</td>
                    <td class="py-3 text-gray-500">{{ $item->size ?? '-' }} / {{ $item->color ?? '-' }}</td>
                    <td class="py-3 text-center">{{ $item->quantity }}</td>
                    <td class="py-3 text-right">Rp {{ number_format($item->product_price, 0, ',', '.') }}</td>
                    <td class="py-3 text-right font-medium">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr><td colspan="4" class="text-right py-2 text-gray-500">Subtotal</td><td class="text-right py-2">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</td></tr>
                <tr><td colspan="4" class="text-right py-2 text-gray-500">Shipping</td><td class="text-right py-2">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</td></tr>
                @if($order->discount > 0)
                <tr><td colspan="4" class="text-right py-2 text-gray-500">Discount</td><td class="text-right py-2 text-green-500">-Rp {{ number_format($order->discount, 0, ',', '.') }}</td></tr>
                @endif
                <tr class="font-bold text-base"><td colspan="4" class="text-right py-2">Total</td><td class="text-right py-2">Rp {{ number_format($order->total, 0, ',', '.') }}</td></tr>
            </tfoot>
        </table>
    </div>

    <!-- Customer Info -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white dark:bg-[#1a1a1a] rounded-xl p-6 shadow-sm">
            <h3 class="font-semibold mb-4">Customer Info</h3>
            <div class="space-y-2 text-sm">
                <p><span class="text-gray-500">Name:</span> {{ $order->user->name ?? 'Guest' }}</p>
                <p><span class="text-gray-500">Email:</span> {{ $order->user->email ?? '-' }}</p>
                <p><span class="text-gray-500">Phone:</span> {{ $order->phone }}</p>
            </div>
        </div>
        <div class="bg-white dark:bg-[#1a1a1a] rounded-xl p-6 shadow-sm">
            <h3 class="font-semibold mb-4">Shipping Info</h3>
            <div class="space-y-2 text-sm">
                <p><span class="text-gray-500">Address:</span> {{ $order->address }}</p>
                <p><span class="text-gray-500">City:</span> {{ $order->city }}</p>
                <p><span class="text-gray-500">Courier:</span> {{ $order->shipping_courier }}</p>
                <p><span class="text-gray-500">Notes:</span> {{ $order->notes ?? '-' }}</p>
            </div>
        </div>
        <div class="bg-white dark:bg-[#1a1a1a] rounded-xl p-6 shadow-sm">
            <h3 class="font-semibold mb-4">Payment Info</h3>
            <div class="space-y-2 text-sm">
                <p><span class="text-gray-500">Method:</span> {{ $order->payment_method }}</p>
                <p><span class="text-gray-500">Status:</span> 
                    <span class="px-2 py-1 rounded-full text-xs font-medium {{ $order->payment_status == 'success' ? 'bg-green-100 text-green-700' : ($order->payment_status == 'failed' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                        {{ ucfirst($order->payment_status) }}
                    </span>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.admin')

@section('title', 'Orders')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-3xl font-light tracking-tight text-white">Orders</h1>
        <p class="text-xs tracking-[0.3em] uppercase text-gray-500 font-light mt-1">Manage customer orders</p>
    </div>

    <div class="glass-card overflow-hidden">
        <div class="p-4 border-b border-white/5">
            <form method="GET" class="flex flex-wrap gap-3">
                <select name="status" class="bg-white/5 border border-white/10 text-white text-xs font-light rounded-none px-4 py-2.5 focus:outline-none focus:border-[#C8A951]">
                    <option value="" class="bg-[#0a0a0a]">All Status</option>
                    @foreach(['pending','paid','process','shipped','completed','cancelled'] as $s)
                    <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }} class="bg-[#0a0a0a]">{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
                <button type="submit" class="text-xs tracking-widest uppercase px-5 py-2.5 bg-white/5 text-white/80 border border-white/10 hover:border-[#C8A951] hover:text-[#C8A951] transition-all duration-500">Filter</button>
            </form>
        </div>
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-white/[0.02] border-b border-white/5">
                    <th class="text-left py-3 px-4 text-[10px] tracking-widest uppercase font-light text-gray-500">Invoice</th>
                    <th class="text-left py-3 px-4 text-[10px] tracking-widest uppercase font-light text-gray-500">Customer</th>
                    <th class="text-left py-3 px-4 text-[10px] tracking-widest uppercase font-light text-gray-500">Total</th>
                    <th class="text-left py-3 px-4 text-[10px] tracking-widest uppercase font-light text-gray-500">Payment</th>
                    <th class="text-left py-3 px-4 text-[10px] tracking-widest uppercase font-light text-gray-500">Status</th>
                    <th class="text-left py-3 px-4 text-[10px] tracking-widest uppercase font-light text-gray-500">Date</th>
                    <th class="text-right py-3 px-4 text-[10px] tracking-widest uppercase font-light text-gray-500">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr class="border-b border-white/5 hover:bg-white/[0.02]">
                    <td class="py-3 px-4 text-xs font-light text-white/90">#{{ $order->invoice_number }}</td>
                    <td class="py-3 px-4 text-xs font-light text-white/60">{{ $order->user->name ?? 'Guest' }}</td>
                    <td class="py-3 px-4 text-xs font-light text-white/80">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                    <td class="py-3 px-4">
                        <span class="text-[10px] tracking-wider px-2 py-0.5 font-light {{ $order->payment_status == 'success' ? 'border border-emerald-500/30 text-emerald-400' : ($order->payment_status == 'failed' ? 'border border-red-500/30 text-red-400' : 'border border-yellow-500/30 text-yellow-400') }}">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </td>
                    <td class="py-3 px-4">
                        <span class="text-[10px] tracking-wider px-2 py-0.5 font-light
                            @switch($order->status)
                                @case('pending') border border-yellow-500/30 text-yellow-400 @break
                                @case('paid') border border-blue-500/30 text-blue-400 @break
                                @case('process') border border-indigo-500/30 text-indigo-400 @break
                                @case('shipped') border border-purple-500/30 text-purple-400 @break
                                @case('completed') border border-emerald-500/30 text-emerald-400 @break
                                @case('cancelled') border border-red-500/30 text-red-400 @break
                            @endswitch">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                    <td class="py-3 px-4 text-xs font-light text-gray-500">{{ $order->created_at->format('d M Y') }}</td>
                    <td class="py-3 px-4 text-right">
                        <div class="flex items-center justify-end gap-3">
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="text-[10px] tracking-wider uppercase font-light text-gray-500 hover:text-[#C8A951] transition-colors">
                                View
                            </a>
                            <a href="{{ route('admin.orders.export-pdf', $order->id) }}" class="text-[10px] tracking-wider uppercase font-light text-gray-500 hover:text-white transition-colors">
                                PDF
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="py-12 text-center text-xs font-light text-gray-500">No orders found</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4 border-t border-white/5">{{ $orders->links() }}</div>
    </div>
</div>
@endsection

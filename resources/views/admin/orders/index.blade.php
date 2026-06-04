@extends('layouts.admin')

@section('title', 'Orders')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold">Orders</h1>
        <p class="text-gray-500 dark:text-gray-400">Manage customer orders</p>
    </div>

    <div class="bg-white dark:bg-[#1a1a1a] rounded-xl shadow-sm overflow-hidden">
        <div class="p-4 border-b border-gray-200 dark:border-gray-800">
            <form method="GET" class="flex flex-wrap gap-3">
                <select name="status" class="px-4 py-2 rounded-xl border border-gray-200 dark:border-gray-700 dark:bg-gray-800 text-sm">
                    <option value="">All Status</option>
                    @foreach(['pending','paid','process','shipped','completed','cancelled'] as $s)
                    <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
                <button type="submit" class="px-4 py-2 bg-gray-900 dark:bg-white dark:text-gray-900 text-white rounded-xl text-sm">Filter</button>
            </form>
        </div>
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-900">
                    <th class="text-left py-3 px-4 font-medium">Invoice</th>
                    <th class="text-left py-3 px-4 font-medium">Customer</th>
                    <th class="text-left py-3 px-4 font-medium">Total</th>
                    <th class="text-left py-3 px-4 font-medium">Payment</th>
                    <th class="text-left py-3 px-4 font-medium">Status</th>
                    <th class="text-left py-3 px-4 font-medium">Date</th>
                    <th class="text-right py-3 px-4 font-medium">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr class="border-b border-gray-100 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800/50">
                    <td class="py-3 px-4 font-medium">#{{ $order->invoice_number }}</td>
                    <td class="py-3 px-4">{{ $order->user->name ?? 'Guest' }}</td>
                    <td class="py-3 px-4">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                    <td class="py-3 px-4 text-xs">
                        <span class="px-2 py-1 rounded-full font-medium {{ $order->payment_status == 'success' ? 'bg-green-100 text-green-700' : ($order->payment_status == 'failed' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </td>
                    <td class="py-3 px-4">
                        <span class="px-2 py-1 rounded-full text-xs font-medium
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
                    </td>
                    <td class="py-3 px-4 text-gray-500 text-xs">{{ $order->created_at->format('d M Y') }}</td>
                    <td class="py-3 px-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="p-2 text-blue-500 hover:bg-blue-50 rounded-lg transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </a>
                            <a href="{{ route('admin.orders.export-pdf', $order->id) }}" class="p-2 text-gray-500 hover:bg-gray-50 rounded-lg transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="py-12 text-center text-gray-500">No orders found</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4 border-t border-gray-200 dark:border-gray-800">{{ $orders->links() }}</div>
    </div>
</div>
@endsection

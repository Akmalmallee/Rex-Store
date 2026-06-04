@extends('layouts.admin')
@section('title', 'Reports')
@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Sales Reports</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">View your store's sales performance</p>
    </div>

    <div class="bg-white dark:bg-gray-800/50 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700/50 p-4">
        <form method="GET" class="flex flex-col sm:flex-row items-end gap-4">
            <div>
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Start Date</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}"
                    class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:border-[#C8A951] focus:ring-[#C8A951]">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">End Date</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}"
                    class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:border-[#C8A951] focus:ring-[#C8A951]">
            </div>
            <button type="submit" class="px-4 py-2 bg-[#C8A951] hover:bg-[#b89a42] text-white text-sm font-medium rounded-lg transition-colors">
                Filter
            </button>
            @if(request('start_date') || request('end_date'))
                <a href="{{ route('admin.reports') }}" class="px-4 py-2 text-sm text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 transition-colors">Clear</a>
            @endif
        </form>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="bg-white dark:bg-gray-800/50 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700/50 p-6">
            <div class="flex items-center gap-3">
                <div class="p-3 rounded-lg bg-emerald-100 dark:bg-emerald-900/30">
                    <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Revenue</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800/50 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700/50 p-6">
            <div class="flex items-center gap-3">
                <div class="p-3 rounded-lg bg-blue-100 dark:bg-blue-900/30">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Orders</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalOrders }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800/50 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700/50 p-6">
            <div class="flex items-center gap-3">
                <div class="p-3 rounded-lg bg-[#C8A951]/10">
                    <svg class="w-6 h-6 text-[#C8A951]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Average Order Value</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">Rp {{ number_format($averageOrder, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800/50 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700/50">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700/50">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Order Data</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-800/80">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Invoice</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Revenue</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-transparent divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($orders as $order)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-500 dark:text-gray-400">{{ $order->created_at->format('d M Y') }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $order->invoice_number }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-500 dark:text-gray-400">{{ $order->user->name ?? 'N/A' }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium capitalize
                                @if($order->status === 'paid' || $order->status === 'completed') bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400
                                @elseif($order->status === 'processing') bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400
                                @elseif($order->status === 'shipped') bg-[#C8A951]/10 text-[#C8A951]
                                @else bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400
                                @endif">
                                {{ $order->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <span class="text-sm font-medium text-gray-900 dark:text-white">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center gap-2">
                                <svg class="w-12 h-12 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                                <p class="text-gray-500 dark:text-gray-400 text-sm">No orders found</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($orders->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700/50">
            {{ $orders->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

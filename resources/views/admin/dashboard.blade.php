@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold">Dashboard</h1>
        <p class="text-gray-500 dark:text-gray-400">Overview of your store</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white dark:bg-[#1a1a1a] rounded-xl p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <span class="text-xs font-medium text-green-500 bg-green-100 dark:bg-green-900/30 px-2 py-1 rounded-full">+{{ $monthlyGrowth ?? 12 }}%</span>
            </div>
            <p class="text-3xl font-bold">Rp {{ number_format($totalSales ?? 0, 0, ',', '.') }}</p>
            <p class="text-sm text-gray-500 mt-1">Total Sales</p>
        </div>

        <div class="bg-white dark:bg-[#1a1a1a] rounded-xl p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                </div>
            </div>
            <p class="text-3xl font-bold">{{ $totalOrders ?? 0 }}</p>
            <p class="text-sm text-gray-500 mt-1">Total Orders</p>
        </div>

        <div class="bg-white dark:bg-[#1a1a1a] rounded-xl p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
            </div>
            <p class="text-3xl font-bold">{{ $totalProducts ?? 0 }}</p>
            <p class="text-sm text-gray-500 mt-1">Total Products</p>
        </div>

        <div class="bg-white dark:bg-[#1a1a1a] rounded-xl p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-orange-100 dark:bg-orange-900/30 flex items-center justify-center">
                    <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/></svg>
                </div>
            </div>
            <p class="text-3xl font-bold">{{ $totalUsers ?? 0 }}</p>
            <p class="text-sm text-gray-500 mt-1">Total Users</p>
        </div>
    </div>

    <!-- Charts & Recent Orders -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Revenue Chart -->
        <div class="lg:col-span-2 bg-white dark:bg-[#1a1a1a] rounded-xl p-6 shadow-sm">
            <h3 class="font-semibold mb-4">Monthly Revenue</h3>
            <canvas id="revenueChart" height="250"></canvas>
        </div>

        <!-- Recent Orders -->
        <div class="bg-white dark:bg-[#1a1a1a] rounded-xl p-6 shadow-sm">
            <h3 class="font-semibold mb-4">Recent Orders</h3>
            <div class="space-y-3">
                @forelse(($recentOrders ?? []) as $order)
                <a href="{{ route('admin.orders.show', $order->id) }}" class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                    <div>
                        <p class="text-sm font-medium">#{{ $order->invoice_number }}</p>
                        <p class="text-xs text-gray-500">Rp {{ number_format($order->total, 0, ',', '.') }}</p>
                    </div>
                    <span class="text-xs px-2 py-1 rounded-full font-medium
                        @switch($order->status)
                            @case('pending') bg-yellow-100 text-yellow-700 @break
                            @case('completed') bg-green-100 text-green-700 @break
                            @case('cancelled') bg-red-100 text-red-700 @break
                            @default bg-blue-100 text-blue-700 @endswitch">
                        {{ ucfirst($order->status) }}
                    </span>
                </a>
                @empty
                <p class="text-sm text-gray-500 text-center py-4">No orders yet</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Best Selling Products -->
    <div class="bg-white dark:bg-[#1a1a1a] rounded-xl p-6 shadow-sm">
        <h3 class="font-semibold mb-4">Best Selling Products</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-gray-700">
                        <th class="text-left py-3 px-4 font-medium text-gray-500">Product</th>
                        <th class="text-left py-3 px-4 font-medium text-gray-500">Category</th>
                        <th class="text-left py-3 px-4 font-medium text-gray-500">Price</th>
                        <th class="text-left py-3 px-4 font-medium text-gray-500">Sold</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse(($bestSelling ?? []) as $item)
                    <tr class="border-b border-gray-100 dark:border-gray-800">
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-gray-100 dark:bg-gray-800 overflow-hidden">
                                    @php $p = $item->product; @endphp
                                    <img src="{{ $p->thumbnail ?? 'https://picsum.photos/seed/'.$p->id.'/100/100' }}" alt="" class="w-full h-full object-cover">
                                </div>
                                <span class="font-medium">{{ $p->name ?? '-' }}</span>
                            </div>
                        </td>
                        <td class="py-3 px-4 text-gray-500">{{ $p->category->name ?? '-' }}</td>
                        <td class="py-3 px-4">Rp {{ number_format($p->price ?? 0, 0, ',', '.') }}</td>
                        <td class="py-3 px-4">{{ $item->total_qty ?? 0 }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="py-8 text-center text-gray-500">No sales data yet</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('revenueChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
                datasets: [{
                    label: 'Revenue',
                    data: [@foreach($revenueChart ?? [] as $r){{ $r['total'] }}, @endforeach],
                    borderColor: '#C8A951',
                    backgroundColor: 'rgba(200, 169, 81, 0.1)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 2,
                    pointBackgroundColor: '#C8A951',
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { callback: v => 'Rp ' + (v/1000000).toFixed(1) + 'M' }
                    }
                }
            }
        });
    }
</script>
@endpush
@endsection

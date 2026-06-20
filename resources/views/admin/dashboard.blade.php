@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-8">
    <div>
        <h1 class="text-3xl font-light tracking-tight text-white">Dashboard</h1>
        <p class="text-xs tracking-[0.3em] uppercase text-gray-500 font-light mt-1">Overview of your store</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <x-admin.stat-card
            icon='<svg class="w-6 h-6 text-[#C8A951]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
            value="Rp {{ number_format($totalSales ?? 0, 0, ',', '.') }}"
            label="Total Sales"
            trend="+{{ $monthlyGrowth ?? 12 }}%"
        />

        <x-admin.stat-card
            icon='<svg class="w-6 h-6 text-[#C8A951]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>'
            value="{{ $totalOrders ?? 0 }}"
            label="Total Orders"
        />

        <x-admin.stat-card
            icon='<svg class="w-6 h-6 text-[#C8A951]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>'
            value="{{ $totalProducts ?? 0 }}"
            label="Total Products"
        />

        <x-admin.stat-card
            icon='<svg class="w-6 h-6 text-[#C8A951]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/></svg>'
            value="{{ $totalUsers ?? 0 }}"
            label="Total Users"
        />
    </div>

    <!-- Charts & Recent Orders -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Revenue Chart -->
        <div class="lg:col-span-2 glass-card p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xs tracking-widest uppercase font-light text-gray-300">Monthly Revenue</h3>
                <span class="text-[10px] tracking-wider text-gray-500">Last 12 months</span>
            </div>
            <canvas id="revenueChart" height="250"></canvas>
        </div>

        <!-- Recent Orders -->
        <div class="glass-card p-6">
            <h3 class="text-xs tracking-widest uppercase font-light text-gray-300 mb-6">Recent Orders</h3>
            <div class="space-y-2">
                @forelse(($recentOrders ?? []) as $order)
                <a href="{{ route('admin.orders.show', $order->id) }}" class="flex items-center justify-between p-3 hover:bg-white/[0.02] transition-colors border-b border-white/5 last:border-0">
                    <div>
                        <p class="text-sm font-light text-white">#{{ $order->invoice_number }}</p>
                        <p class="text-xs font-light text-gray-500">Rp {{ number_format($order->total, 0, ',', '.') }}</p>
                    </div>
                    <span class="text-[10px] tracking-wider px-2 py-0.5 font-light
                        @switch($order->status)
                            @case('pending') border border-yellow-500/30 text-yellow-400 @break
                            @case('completed') border border-emerald-500/30 text-emerald-400 @break
                            @case('cancelled') border border-red-500/30 text-red-400 @break
                            @default border border-blue-500/30 text-blue-400 @endswitch">
                        {{ ucfirst($order->status) }}
                    </span>
                </a>
                @empty
                <p class="text-sm font-light text-gray-500 text-center py-4">No orders yet</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Best Selling Products -->
    <div class="glass-card overflow-hidden">
        <div class="p-6 border-b border-white/5">
            <h3 class="text-xs tracking-widest uppercase font-light text-gray-300">Best Selling Products</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-white/[0.02] border-b border-white/5">
                        <th class="text-left py-3 px-4 text-[10px] tracking-widest uppercase font-light text-gray-500">Product</th>
                        <th class="text-left py-3 px-4 text-[10px] tracking-widest uppercase font-light text-gray-500">Category</th>
                        <th class="text-left py-3 px-4 text-[10px] tracking-widest uppercase font-light text-gray-500">Price</th>
                        <th class="text-left py-3 px-4 text-[10px] tracking-widest uppercase font-light text-gray-500">Sold</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse(($bestSelling ?? []) as $item)
                    <tr class="border-b border-white/5 hover:bg-white/[0.02]">
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-white/5 overflow-hidden">
                                    @php $p = $item->product; @endphp
                                    <img src="{{ $p->thumbnail ?? 'https://picsum.photos/seed/'.$p->id.'/100/100' }}" alt="" class="w-full h-full object-cover">
                                </div>
                                <span class="text-xs font-light text-white/80">{{ $p->name ?? '-' }}</span>
                            </div>
                        </td>
                        <td class="py-3 px-4 text-xs font-light text-gray-500">{{ $p->category->name ?? '-' }}</td>
                        <td class="py-3 px-4 text-xs font-light text-white/80">Rp {{ number_format($p->price ?? 0, 0, ',', '.') }}</td>
                        <td class="py-3 px-4 text-xs font-light text-white/80">{{ $item->total_qty ?? 0 }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="py-8 text-center text-xs font-light text-gray-500">No sales data yet</td></tr>
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
                    backgroundColor: 'rgba(200, 169, 81, 0.05)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 1.5,
                    pointBackgroundColor: '#C8A951',
                    pointRadius: 2,
                    pointHoverRadius: 4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: {
                        grid: { color: 'rgba(255,255,255,0.03)' },
                        ticks: { color: 'rgba(255,255,255,0.3)', font: { size: 10 } }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(255,255,255,0.03)' },
                        ticks: {
                            color: 'rgba(255,255,255,0.3)',
                            font: { size: 10 },
                            callback: v => 'Rp ' + (v/1000000).toFixed(1) + 'M'
                        }
                    }
                }
            }
        });
    }
</script>
@endpush
@endsection

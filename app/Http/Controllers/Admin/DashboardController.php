<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalSales = Order::whereNotIn('status', ['cancelled'])->sum('total');
        $totalProducts = Product::count();
        $totalUsers = User::count();
        $totalOrders = Order::count();

        $monthlyRevenue = Order::whereNotIn('status', ['cancelled'])
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('YEAR(created_at) as year'),
                DB::raw('SUM(total) as total')
            )
            ->where('created_at', '>=', Carbon::now()->subYear())
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        $revenueChart = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $month = $date->month;
            $year = $date->year;
            $found = $monthlyRevenue->firstWhere(function ($item) use ($month, $year) {
                return $item->month == $month && $item->year == $year;
            });
            $revenueChart[] = [
                'month' => $date->format('M Y'),
                'total' => $found ? (float) $found->total : 0,
            ];
        }

        $recentOrders = Order::with('user')
            ->latest()
            ->take(5)
            ->get();

        $bestSelling = OrderItem::select(
                'product_id',
                DB::raw('SUM(quantity) as total_qty')
            )
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->with('product')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalSales',
            'totalProducts',
            'totalUsers',
            'totalOrders',
            'revenueChart',
            'recentOrders',
            'bestSelling'
        ));
    }
}

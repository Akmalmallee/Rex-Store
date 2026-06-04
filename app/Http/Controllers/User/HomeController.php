<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use App\Models\Promo;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::where('is_active', true)->inRandomOrder()->take(8)->get();
        $newArrivals = Product::where('is_active', true)->latest()->take(4)->get();
        $bestSellers = Product::where('is_active', true)->orderByDesc('rating')->take(4)->get();
        $categories = Category::where('is_active', true)->withCount('products')->get();
        $banners = Banner::where('is_active', true)->get();
        $promos = Promo::where('is_active', true)
            ->where('start_at', '<=', now())
            ->where('end_at', '>=', now())
            ->get();

        return view('user.home', compact('featuredProducts', 'newArrivals', 'bestSellers', 'categories', 'banners', 'promos'));
    }
}

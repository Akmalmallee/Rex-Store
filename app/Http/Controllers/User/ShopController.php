<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::where('is_active', true);

        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        if ($request->filled('sizes')) {
            $sizes = (array) $request->sizes;
            $query->whereHas('sizes', function ($q) use ($sizes) {
                $q->whereIn('size', $sizes);
            });
        }

        if ($request->filled('color')) {
            $query->whereHas('colors', function ($q) use ($request) {
                $q->where('color', $request->color);
            });
        }

        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'cheapest':
                $query->orderBy('price', 'asc');
                break;
            case 'expensive':
                $query->orderBy('price', 'desc');
                break;
            case 'popular':
                $query->orderBy('rating', 'desc');
                break;
            default:
                $query->latest();
                break;
        }

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::where('is_active', true)->get();
        $brands = Brand::where('is_active', true)->get();

        return view('user.shop', compact('products', 'categories', 'brands'));
    }

    public function category($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        $products = Product::where('is_active', true)
            ->where('category_id', $category->id)
            ->paginate(12);
        $categories = Category::where('is_active', true)->get();
        $brands = Brand::where('is_active', true)->get();

        return view('user.shop', compact('products', 'categories', 'brands', 'category'));
    }
}

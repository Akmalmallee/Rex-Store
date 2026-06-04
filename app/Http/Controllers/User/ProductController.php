<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReviewRequest;
use App\Models\Product;
use App\Models\Review;

class ProductController extends Controller
{
    public function show($slug)
    {
        $product = Product::where('slug', $slug)
            ->with(['images', 'sizes', 'colors', 'reviews.user'])
            ->firstOrFail();

        $related = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->take(4)
            ->get();

        return view('user.product-detail', compact('product', 'related'));
    }

    public function reviewStore(StoreReviewRequest $request)
    {
        $product = Product::findOrFail($request->product_id);

        Review::create([
            'user_id' => auth()->id(),
            'product_id' => $product->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        $product->rating = $product->reviews()->avg('rating');
        $product->save();

        return redirect()->back()->with('success', 'Review submitted successfully.');
    }
}

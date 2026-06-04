<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlists = Wishlist::where('user_id', auth()->id())
            ->with('product.images')
            ->latest()
            ->get();

        return view('user.wishlist', compact('wishlists'));
    }

    public function toggle(Request $request)
    {
        $request->validate(['product_id' => 'required|exists:products,id']);

        $existing = Wishlist::where('user_id', auth()->id())
            ->where('product_id', $request->product_id)
            ->first();

        if ($existing) {
            $existing->delete();
            $wishlisted = false;
        } else {
            Wishlist::create([
                'user_id' => auth()->id(),
                'product_id' => $request->product_id,
            ]);
            $wishlisted = true;
        }

        return response()->json([
            'success' => true,
            'wishlisted' => $wishlisted,
        ]);
    }
}

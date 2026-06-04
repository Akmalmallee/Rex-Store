<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCartItemRequest;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
        $cart->load('items.product.images');

        return view('user.cart', compact('cart'));
    }

    public function add(StoreCartItemRequest $request)
    {
        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);

        $existing = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $request->product_id)
            ->where('size', $request->size)
            ->where('color', $request->color)
            ->first();

        if ($existing) {
            $existing->increment('quantity', $request->quantity);
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $request->product_id,
                'size' => $request->size,
                'color' => $request->color,
                'quantity' => $request->quantity,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Item added to cart.',
            'cart_count' => $cart->items()->count(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate(['quantity' => 'required|integer|min:1']);

        $item = CartItem::findOrFail($id);
        $item->update(['quantity' => $request->quantity]);

        $cart = $item->cart;
        $cart->load('items.product');

        $subtotal = $cart->items->sum(function ($item) {
            return $item->product->final_price * $item->quantity;
        });

        return response()->json([
            'success' => true,
            'message' => 'Cart updated.',
            'subtotal' => $subtotal,
        ]);
    }

    public function remove($id)
    {
        $item = CartItem::findOrFail($id);
        $item->delete();

        $cartCount = CartItem::whereHas('cart', fn($q) => $q->where('user_id', Auth::id()))->count();

        return response()->json([
            'success' => true,
            'message' => 'Item removed from cart.',
            'cart_count' => $cartCount,
        ]);
    }

    public function sidebar()
    {
        $cart = Cart::where('user_id', Auth::id())->with('items.product.images')->first();
        return view('components.cart-sidebar', compact('cart'));
    }
}

<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCheckoutRequest;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\PaymentAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = Cart::where('user_id', Auth::id())
            ->with('items.product.images')
            ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Your cart is empty.');
        }

        $subtotal = $cart->items->sum(function ($item) {
            return $item->product->final_price * $item->quantity;
        });

        return view('user.checkout', compact('cart', 'subtotal'));
    }

    public function store(StoreCheckoutRequest $request)
    {
        $cart = Cart::where('user_id', Auth::id())
            ->with('items.product')
            ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Your cart is empty.');
        }

        $subtotal = $cart->items->sum(function ($item) {
            return $item->product->final_price * $item->quantity;
        });

        $order = DB::transaction(function () use ($cart, $request, $subtotal) {
            $order = Order::create([
                'user_id' => Auth::id(),
                'status' => 'pending',
                'subtotal' => $subtotal,
                'shipping_cost' => 0,
                'discount' => 0,
                'total' => $subtotal,
                'address' => $request->address,
                'city' => $request->city,
                'phone' => $request->phone,
                'notes' => $request->notes,
                'shipping_courier' => $request->shipping_courier,
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending',
            ]);

            foreach ($cart->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name,
                    'product_price' => $item->product->final_price,
                    'size' => $item->size,
                    'color' => $item->color,
                    'quantity' => $item->quantity,
                    'subtotal' => $item->product->final_price * $item->quantity,
                ]);
            }

            Payment::create([
                'order_id' => $order->id,
                'method' => $request->payment_method,
                'payment_number' => $this->generatePaymentNumber($request->payment_method),
                'status' => 'pending',
            ]);

            $cart->items()->delete();

            return $order;
        });

        return redirect()->route('checkout.payment', $order->id)
            ->with('success', 'Order created successfully.');
    }

    public function payment($orderId)
    {
        $order = Order::with('payment')->findOrFail($orderId);

        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $paymentAccount = PaymentAccount::where('method', $order->payment_method)
            ->where('active', true)
            ->first();

        return view('user.payment', compact('order', 'paymentAccount'));
    }

    public function uploadPayment(Request $request, $orderId)
    {
        $request->validate([
            'proof_image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $order = Order::findOrFail($orderId);

        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $proofPath = $request->file('proof_image')->store('payments', 'public');

        $order->payment->update([
            'proof_image' => $proofPath,
            'status' => 'pending',
        ]);

        $order->update(['payment_status' => 'pending']);

        return redirect()->route('orders.show', $order->id)
            ->with('success', 'Payment proof uploaded successfully.');
    }

    private function generatePaymentNumber($method)
    {
        if ($method === 'Bank Transfer') {
            return 'BCA' . strtoupper(Str::random(3)) . date('YmdHis') . mt_rand(100, 999);
        } elseif ($method === 'GCash') {
            return 'GCASH' . strtoupper(Str::random(2)) . date('YmdHis') . mt_rand(100, 999);
        } elseif ($method === 'PayMaya') {
            return 'PMAYA' . strtoupper(Str::random(2)) . date('YmdHis') . mt_rand(100, 999);
        }
        return 'PAY' . strtoupper(Str::random(4)) . date('YmdHis');
    }
}

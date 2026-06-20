<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCheckoutRequest;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\PaymentAccount;
use App\Services\Payment\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function __construct(
        private PaymentService $paymentService
    ) {}

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
                'recipient_name' => $request->recipient_name,
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

            $this->paymentService->createPayment($order, $request->payment_method);

            $order->trackings()->create([
                'status' => 'pending',
                'description' => 'Pesanan dibuat. Menunggu pembayaran.',
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

        $clientKey = $this->paymentService->getClientKey();

        return view('user.payment', compact('order', 'paymentAccount', 'clientKey'));
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

    public function success($orderId)
    {
        $order = Order::with('payment')->findOrFail($orderId);

        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $payment = $order->payment;

        return view('user.payment-success', compact('order', 'payment'));
    }

    public function failure($orderId, Request $request)
    {
        $order = Order::with('payment')->findOrFail($orderId);

        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $reason = $request->get('reason', 'Your payment could not be processed.');

        return view('user.payment-failure', compact('order', 'reason'));
    }

    public function callback(Request $request)
    {
        $result = $this->paymentService->handleCallback($request->all());

        return response()->json($result);
    }
}

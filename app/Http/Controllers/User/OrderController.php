<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('user.orders', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with(['items.product', 'payment', 'trackings'])
            ->where('user_id', auth()->id())
            ->findOrFail($id);

        return view('user.order-detail', compact('order'));
    }

    public function invoice($id)
    {
        $order = Order::with('items', 'payment')
            ->where('user_id', auth()->id())
            ->findOrFail($id);

        $pdf = Pdf::loadView('user.invoice', compact('order'));

        return $pdf->download('invoice-' . $order->invoice_number . '.pdf');
    }
}

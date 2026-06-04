<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderTracking;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('user')->latest()->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with(['items.product', 'payment', 'user', 'trackings'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,paid,process,shipped,completed,cancelled',
        ]);

        $order = Order::findOrFail($id);
        $order->update(['status' => $request->status]);

        if ($request->status === 'completed') {
            $order->update(['payment_status' => 'success']);
        }

        $descriptions = [
            'paid' => 'Pembayaran berhasil dikonfirmasi',
            'process' => 'Pesanan sedang diproses oleh penjual',
            'shipped' => 'Pesanan telah dikirim',
            'completed' => 'Pesanan telah selesai',
            'cancelled' => 'Pesanan dibatalkan',
        ];

        if (isset($descriptions[$request->status])) {
            OrderTracking::create([
                'order_id' => $order->id,
                'status' => $request->status,
                'description' => $descriptions[$request->status],
            ]);
        }

        return redirect()->back()->with('success', 'Order status updated successfully.');
    }

    public function exportPdf($id)
    {
        $order = Order::with(['items', 'payment', 'user'])->findOrFail($id);
        $pdf = Pdf::loadView('admin.orders.invoice', compact('order'));
        return $pdf->download('invoice-' . $order->invoice_number . '.pdf');
    }

    public function destroy($id)
    {
        Order::findOrFail($id)->delete();
        return redirect()->route('admin.orders.index')
            ->with('success', 'Order deleted successfully.');
    }
}

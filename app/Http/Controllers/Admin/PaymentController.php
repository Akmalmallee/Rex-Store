<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with('order.user')->latest()->paginate(10);
        return view('admin.payments.index', compact('payments'));
    }

    public function approve($id)
    {
        $payment = Payment::findOrFail($id);
        $payment->update([
            'status' => 'success',
            'paid_at' => now(),
        ]);
        $payment->order->update([
            'payment_status' => 'success',
            'status' => 'paid',
        ]);

        return redirect()->back()->with('success', 'Payment approved successfully.');
    }

    public function reject($id)
    {
        $payment = Payment::findOrFail($id);
        $payment->update(['status' => 'failed']);
        $payment->order->update(['payment_status' => 'failed']);

        return redirect()->back()->with('success', 'Payment rejected.');
    }
}

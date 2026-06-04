<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentAccount;
use Illuminate\Http\Request;

class PaymentAccountController extends Controller
{
    public function index()
    {
        $accounts = PaymentAccount::all();
        return view('admin.payment-accounts.index', compact('accounts'));
    }

    public function create()
    {
        return view('admin.payment-accounts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'method' => 'required|string|max:255',
            'account_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'instructions' => 'nullable|string',
            'active' => 'boolean',
        ]);

        PaymentAccount::create($validated);

        return redirect()->route('admin.payment-accounts.index')
            ->with('success', 'Payment account created successfully.');
    }

    public function edit(PaymentAccount $paymentAccount)
    {
        return view('admin.payment-accounts.edit', compact('paymentAccount'));
    }

    public function update(Request $request, PaymentAccount $paymentAccount)
    {
        $validated = $request->validate([
            'method' => 'required|string|max:255',
            'account_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'instructions' => 'nullable|string',
            'active' => 'boolean',
        ]);

        $paymentAccount->update($validated);

        return redirect()->route('admin.payment-accounts.index')
            ->with('success', 'Payment account updated successfully.');
    }

    public function destroy(PaymentAccount $paymentAccount)
    {
        $paymentAccount->delete();

        return redirect()->route('admin.payment-accounts.index')
            ->with('success', 'Payment account deleted successfully.');
    }
}

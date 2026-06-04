@extends('layouts.admin')
@section('title', 'Payment Detail')
@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Payment Detail</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Invoice: {{ $payment->order->invoice_number ?? 'N/A' }}</p>
        </div>
        <a href="{{ route('admin.payments.index') }}" class="text-sm text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 transition-colors">
            Back to Payments
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white dark:bg-gray-800/50 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700/50 p-6 space-y-4">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Order Information</h2>
            <div class="space-y-3">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500 dark:text-gray-400">Invoice</span>
                    <span class="font-medium text-gray-900 dark:text-white">{{ $payment->order->invoice_number ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500 dark:text-gray-400">Customer</span>
                    <span class="font-medium text-gray-900 dark:text-white">{{ $payment->order->user->name ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500 dark:text-gray-400">Email</span>
                    <span class="font-medium text-gray-900 dark:text-white">{{ $payment->order->user->email ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500 dark:text-gray-400">Order Status</span>
                    <span class="font-medium capitalize">{{ $payment->order->status ?? 'N/A' }}</span>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800/50 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700/50 p-6 space-y-4">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Payment Information</h2>
            <div class="space-y-3">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500 dark:text-gray-400">Method</span>
                    <span class="font-medium text-gray-900 dark:text-white capitalize">{{ $payment->method ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500 dark:text-gray-400">Amount</span>
                    <span class="font-medium text-gray-900 dark:text-white">Rp {{ number_format($payment->order->total ?? 0, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500 dark:text-gray-400">Status</span>
                    @if($payment->status === 'success')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400">Success</span>
                    @elseif($payment->status === 'pending')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400">Pending</span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400">Failed</span>
                    @endif
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500 dark:text-gray-400">Payment Date</span>
                    <span class="font-medium text-gray-900 dark:text-white">{{ $payment->paid_at ? $payment->paid_at->format('d M Y H:i') : '-' }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500 dark:text-gray-400">Created At</span>
                    <span class="font-medium text-gray-900 dark:text-white">{{ $payment->created_at->format('d M Y H:i') }}</span>
                </div>
            </div>
        </div>
    </div>

    @if($payment->proof_image)
    <div class="bg-white dark:bg-gray-800/50 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700/50 p-6 space-y-4">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Payment Proof</h2>
        <div class="flex justify-center">
            <img src="{{ asset('storage/' . $payment->proof_image) }}" alt="Payment Proof" class="max-w-full max-h-96 rounded-lg object-contain border border-gray-200 dark:border-gray-700">
        </div>
    </div>
    @endif

    @if($payment->status === 'pending')
    <div class="flex items-center gap-3 pt-2">
        <form action="{{ route('admin.payments.approve', $payment) }}" method="POST">
            @csrf
            <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-xl transition-colors duration-200 shadow-sm inline-flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                Approve Payment
            </button>
        </form>
        <form action="{{ route('admin.payments.reject', $payment) }}" method="POST" onsubmit="return confirm('Reject this payment?')">
            @csrf
            <button type="submit" class="px-6 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-xl transition-colors duration-200 shadow-sm inline-flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                Reject Payment
            </button>
        </form>
    </div>
    @endif
</div>
@endsection

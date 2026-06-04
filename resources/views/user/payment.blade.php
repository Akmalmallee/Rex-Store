<x-app-layout>
    <div class="pt-24 pb-16 min-h-screen bg-gray-50 dark:bg-[#111]">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="bg-white dark:bg-[#1a1a1a] rounded-2xl p-12 shadow-sm">
                <div class="w-20 h-20 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
                <h2 class="text-2xl font-bold mb-2">Order Placed Successfully!</h2>
                <p class="text-gray-500 mb-2">Order #{{ $order->invoice_number }}</p>
                <p class="text-gray-400 text-sm mb-8">Thank you for your order. We will process it shortly.</p>
                @if($order->payment_method != 'COD')
                <div class="bg-gray-50 dark:bg-gray-800 rounded-xl p-6 mb-8 text-left">
                    <h3 class="font-semibold mb-3">Payment Instructions</h3>
                    @if($paymentAccount)
                    <div class="space-y-2 text-sm">
                        @if($order->payment_method == 'Bank Transfer')
                        <p>Transfer to:</p>
                        <p class="font-mono font-bold text-lg">{{ $paymentAccount->account_number }}</p>
                        <p>{{ $paymentAccount->account_name }}</p>
                        @else
                        <p>{{ $paymentAccount->account_name }}</p>
                        <p class="font-mono font-bold text-lg">{{ $paymentAccount->account_number }}</p>
                        @endif
                        <p class="text-gray-500">Total: Rp {{ number_format($order->total, 0, ',', '.') }}</p>
                        @if($paymentAccount->instructions)
                        <p class="text-gray-500 border-t pt-2 mt-2">{{ $paymentAccount->instructions }}</p>
                        @endif
                        @if($order->payment->payment_number)
                        <p class="text-gray-500 border-t pt-2 mt-2">Payment Ref: <span class="font-mono font-bold text-green-600">{{ $order->payment->payment_number }}</span></p>
                        @endif
                    </div>
                    @else
                    <p class="text-sm text-gray-500">Please complete payment through the {{ $order->payment_method }} app.</p>
                    @endif
                </div>
                <form action="{{ route('payment.upload', $order->id) }}" method="POST" enctype="multipart/form-data" class="mb-8">
                    @csrf
                    <label class="block text-sm font-medium mb-2">Upload Payment Proof</label>
                    <input type="file" name="proof_image" required class="input-field mb-4">
                    <button type="submit" class="btn-primary w-full">Upload Proof</button>
                </form>
                @endif
                <div class="flex gap-4 justify-center">
                    <a href="{{ route('orders.show', $order->id) }}" class="btn-outline">View Order</a>
                    <a href="{{ route('shop') }}" class="btn-primary">Continue Shopping</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

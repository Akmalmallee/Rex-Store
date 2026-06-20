<x-app-layout>
    <div class="pt-28 pb-24 min-h-screen bg-[#0a0a0a]">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center reveal">
                <div class="w-16 h-16 mx-auto mb-6 rounded-full bg-green-500/10 border border-green-500/20 flex items-center justify-center">
                    <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <h1 class="text-2xl font-light tracking-tight text-white mb-2">Order Placed!</h1>
                <p class="text-sm font-light text-gray-400 mb-1">Order <span class="text-white font-mono">#{{ $order->invoice_number }}</span></p>
                <p class="text-xs font-light text-gray-500 mb-8">Thank you for your order. Complete your payment below.</p>
            </div>

            @if($order->payment_method != 'COD')
            <!-- Payment Instructions -->
            <div class="glass-card p-8 mb-6 reveal" data-stagger>
                <p class="text-xs tracking-widest uppercase font-light text-gray-300 mb-6 flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#C8A951]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Payment Instructions
                </p>
                @if($paymentAccount)
                <div class="space-y-4 text-sm">
                    @if($order->payment_method == 'Bank Transfer')
                    <div class="bg-white/5 border border-white/10 p-5">
                        <p class="text-[10px] tracking-widest uppercase font-light text-gray-500 mb-2">Transfer to</p>
                        <p class="font-mono text-xl tracking-wider text-white">{{ $paymentAccount->account_number }}</p>
                        <p class="text-sm font-light text-gray-400 mt-1">{{ $paymentAccount->account_name }}</p>
                    </div>
                    @else
                    <div class="bg-white/5 border border-white/10 p-5">
                        <p class="text-[10px] tracking-widest uppercase font-light text-gray-500 mb-2">{{ $paymentAccount->account_name }}</p>
                        <p class="font-mono text-xl tracking-wider text-white">{{ $paymentAccount->account_number }}</p>
                    </div>
                    @endif

                    <div class="flex items-center justify-between py-3 border-b border-white/5">
                        <span class="text-gray-500 font-light">Total Payment</span>
                        <span class="text-[#C8A951] font-light text-lg">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                    </div>

                    @if($paymentAccount->instructions)
                    <div class="bg-white/5 border border-white/10 p-4">
                        <p class="text-xs font-light text-gray-400 leading-relaxed">{{ $paymentAccount->instructions }}</p>
                    </div>
                    @endif

                    @if($order->payment->payment_number)
                    <div class="bg-[#C8A951]/5 border border-[#C8A951]/20 p-4">
                        <p class="text-[10px] tracking-widest uppercase font-light text-gray-500 mb-2">Payment Reference</p>
                        <div class="flex items-center justify-between">
                            <p class="font-mono text-sm tracking-wider text-[#C8A951] break-all">{{ $order->payment->payment_number }}</p>
                            <button onclick="navigator.clipboard.writeText('{{ $order->payment->payment_number }}').then(()=>{this.textContent='Copied!'}).catch(()=>{})" class="text-[10px] font-light text-white/60 hover:text-[#C8A951] transition-colors shrink-0 ml-3 border border-white/10 hover:border-[#C8A951]/30 px-3 py-1.5">
                                Copy
                            </button>
                        </div>
                        <p class="text-[10px] text-gray-600 mt-2">Include this reference when making payment</p>
                    </div>
                    @endif
                </div>
                @else
                <p class="text-sm font-light text-gray-500">Please complete payment through the {{ $order->payment_method }} app.</p>
                @endif
            </div>

            <!-- Upload Payment Proof -->
            <div class="glass-card p-8 mb-6 reveal" data-stagger>
                <p class="text-xs tracking-widest uppercase font-light text-gray-300 mb-6 flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#C8A951]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Upload Payment Proof
                </p>
                <form action="{{ route('payment.upload', $order->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="border-2 border-dashed border-white/10 hover:border-[#C8A951]/30 transition-colors duration-300 p-8 text-center cursor-pointer" onclick="document.getElementById('proof-input').click()">
                        <svg class="w-8 h-8 mx-auto text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                        <p class="text-xs font-light text-gray-500 mb-1">Click to upload proof of payment</p>
                        <p class="text-[10px] font-light text-gray-600">JPG, PNG, WEBP (max 2MB)</p>
                        <input id="proof-input" type="file" name="proof_image" accept="image/jpeg,image/png,image/webp" required class="hidden" onchange="this.closest('form').querySelector('.file-name').textContent = this.files[0]?.name || ''">
                        <p class="file-name text-[10px] text-[#C8A951] mt-2 font-light"></p>
                    </div>
                    @error('proof_image') <p class="text-red-400 text-[10px] font-light mt-2">{{ $message }}</p> @enderror
                    <button type="submit" class="w-full mt-5 bg-[#C8A951] text-black text-xs tracking-widest uppercase font-medium py-3.5 hover:bg-white transition-all duration-500">
                        Submit Payment Proof
                    </button>
                </form>
            </div>

            <div class="text-center reveal">
                <p class="text-[10px] font-light text-gray-600 mb-4">Complete payment within 24 hours to keep your order</p>
            </div>
            @endif

            <div class="flex flex-col sm:flex-row gap-3 justify-center reveal">
                <a href="{{ route('orders.show', $order->id) }}" class="block text-center px-8 py-3.5 border border-white/20 text-white text-xs tracking-widest uppercase font-light hover:bg-white/5 transition-all duration-500">
                    View Order
                </a>
                <a href="{{ route('shop') }}" class="block text-center px-8 py-3.5 bg-[#C8A951] text-black text-xs tracking-widest uppercase font-medium hover:bg-white transition-all duration-500">
                    Continue Shopping
                </a>
            </div>
        </div>
    </div>
</x-app-layout>

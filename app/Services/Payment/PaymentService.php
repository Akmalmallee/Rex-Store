<?php

namespace App\Services\Payment;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    public function __construct(
        private PaymentGatewayInterface $gateway
    ) {}

    public function createPayment(Order $order, string $method, array $options = []): Payment
    {
        return DB::transaction(function () use ($order, $method, $options) {
            $gatewayResult = $this->gateway->createPayment($order, $method, $options);

            $payment = Payment::create([
                'order_id' => $order->id,
                'payment_number' => $gatewayResult['payment_number'],
                'method' => $method,
                'snap_token' => $gatewayResult['snap_token'],
                'status' => 'pending',
            ]);

            Transaction::create([
                'order_id' => $order->id,
                'gateway' => config('payment.driver'),
                'transaction_id' => $gatewayResult['transaction_id'] ?? null,
                'type' => 'payment',
                'amount' => $order->total,
                'status' => 'pending',
                'raw_response' => $gatewayResult,
            ]);

            if ($gatewayResult['type'] === 'instant' && $gatewayResult['redirect_url']) {
                $payment->redirect_url = $gatewayResult['redirect_url'];
            }

            $payment->save();

            return $payment;
        });
    }

    public function handleCallback(array $payload): array
    {
        $result = $this->gateway->handleCallback($payload);

        $orderId = $payload['order_id'] ?? null;

        if ($orderId) {
            $order = Order::where('invoice_number', $orderId)->first();

            if ($order) {
                $payment = $order->payment;

                Transaction::create([
                    'order_id' => $order->id,
                    'gateway' => config('payment.driver'),
                    'transaction_id' => $result['transaction_id'],
                    'type' => 'payment',
                    'amount' => $order->total,
                    'status' => $result['status'],
                    'raw_response' => $payload,
                ]);

                if ($result['status'] === 'success') {
                    $this->markSuccess($payment);
                } elseif ($result['status'] === 'failed') {
                    $this->markFailed($payment, 'Gateway reported failure');
                }

                if ($payment && isset($result['payment_channel'])) {
                    $payment->payment_channel = $result['payment_channel'];
                    $payment->save();
                }
            }
        }

        return $result;
    }

    public function checkStatus(Payment $payment): array
    {
        $result = $this->gateway->checkStatus($payment);

        $payment->transaction_id = $result['transaction_id'] ?? $payment->transaction_id;

        if ($result['status'] === 'success' && $payment->status !== 'success') {
            $this->markSuccess($payment);
        } elseif ($result['status'] === 'failed' && $payment->status !== 'failed') {
            $this->markFailed($payment, 'Status check reported failure');
        }

        $payment->save();

        return $result;
    }

    public function markSuccess(Payment $payment): void
    {
        $payment->update([
            'status' => 'success',
            'paid_at' => now(),
        ]);

        $order = $payment->order;
        $order->update([
            'status' => 'process',
            'payment_status' => 'success',
        ]);

        $order->trackings()->create([
            'status' => 'process',
            'description' => 'Pembayaran berhasil dikonfirmasi. Pesanan sedang diproses.',
        ]);
    }

    public function markFailed(Payment $payment, string $reason): void
    {
        $payment->update([
            'status' => 'failed',
        ]);

        if ($payment->order) {
            $payment->order->trackings()->create([
                'status' => 'cancelled',
                'description' => 'Pembayaran gagal: ' . $reason,
            ]);
        }
    }

    public function getClientKey(): ?string
    {
        return $this->gateway->getClientKey();
    }
}

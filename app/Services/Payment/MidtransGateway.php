<?php

namespace App\Services\Payment;

use App\Models\Order;
use App\Models\Payment;

class MidtransGateway implements PaymentGatewayInterface
{
    private ?string $serverKey;
    private ?string $clientKey;
    private bool $isProduction;

    public function __construct()
    {
        $this->serverKey = config('payment.midtrans.server_key');
        $this->clientKey = config('payment.midtrans.client_key');
        $this->isProduction = config('payment.midtrans.is_production', false);
    }

    public function createPayment(Order $order, string $method, array $options = []): array
    {
        $this->ensureConfigured();

        // TODO: Implement Midtrans Snap API call
        // \Midtrans\Config::$serverKey = $this->serverKey;
        // \Midtrans\Config::$isProduction = $this->isProduction;
        // \Midtrans\Config::$isSanitized = config('payment.midtrans.is_sanitized', true);
        // \Midtrans\Config::$is3ds = config('payment.midtrans.is_3ds', true);
        //
        // $params = [
        //     'transaction_details' => [
        //         'order_id' => $order->invoice_number,
        //         'gross_amount' => (int) $order->total,
        //     ],
        //     'customer_details' => [
        //         'first_name' => $order->user->name,
        //         'email' => $order->user->email,
        //         'phone' => $order->phone,
        //     ],
        // ];
        //
        // $snapToken = \Midtrans\Snap::getSnapToken($params);

        throw new \RuntimeException(
            'Midtrans integration not yet implemented. Install midtrans/midtrans-php and configure MIDTRANS_SERVER_KEY in .env'
        );
    }

    public function checkStatus(Payment $payment): array
    {
        $this->ensureConfigured();

        // TODO: Implement status check via Midtrans API
        // $status = \Midtrans\Transaction::status($payment->order->invoice_number);

        throw new \RuntimeException('Midtrans status check not yet implemented.');
    }

    public function handleCallback(array $payload): array
    {
        $this->ensureConfigured();

        // TODO: Implement callback verification
        // $orderId = $payload['order_id'];
        // $transactionStatus = $payload['transaction_status'];
        // $fraudStatus = $payload['fraud_status'];
        //
        // return [
        //     'status' => $this->mapStatus($transactionStatus, $fraudStatus),
        //     'transaction_id' => $payload['transaction_id'] ?? null,
        //     'payment_channel' => $payload['payment_type'] ?? null,
        // ];

        throw new \RuntimeException('Midtrans callback handling not yet implemented.');
    }

    public function getClientKey(): ?string
    {
        return $this->clientKey;
    }

    private function ensureConfigured(): void
    {
        if (!$this->serverKey || !$this->clientKey) {
            throw new \RuntimeException(
                'Midtrans not configured. Set MIDTRANS_SERVER_KEY and MIDTRANS_CLIENT_KEY in .env'
            );
        }
    }

    private function mapStatus(string $transactionStatus, ?string $fraudStatus): string
    {
        if ($transactionStatus === 'capture' || $transactionStatus === 'settlement') {
            return $fraudStatus === 'deny' ? 'failed' : 'success';
        }

        if ($transactionStatus === 'pending') return 'pending';
        if ($transactionStatus === 'deny' || $transactionStatus === 'cancel' || $transactionStatus === 'expire') return 'failed';

        return 'pending';
    }
}

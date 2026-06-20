<?php

namespace App\Services\Payment;

use App\Models\Order;
use App\Models\Payment;

class DummyGateway implements PaymentGatewayInterface
{
    public function createPayment(Order $order, string $method, array $options = []): array
    {
        return [
            'snap_token' => null,
            'redirect_url' => null,
            'payment_number' => $this->generatePaymentNumber($method),
            'type' => 'manual',
        ];
    }

    public function checkStatus(Payment $payment): array
    {
        return [
            'status' => $payment->status,
            'transaction_id' => $payment->transaction_id,
            'paid_at' => $payment->paid_at,
        ];
    }

    public function handleCallback(array $payload): array
    {
        return [
            'status' => 'pending',
            'transaction_id' => null,
            'message' => 'Manual payment does not support callbacks.',
        ];
    }

    public function getClientKey(): ?string
    {
        return null;
    }

    private function generatePaymentNumber(string $method): string
    {
        $prefixes = [
            'Bank Transfer' => 'BCA',
            'Dana' => 'DANA',
            'OVO' => 'OVO',
            'GoPay' => 'GOPY',
            'COD' => 'COD',
        ];

        $prefix = $prefixes[$method] ?? 'PAY';

        return $prefix . strtoupper(substr(uniqid(), -8)) . mt_rand(100, 999);
    }
}

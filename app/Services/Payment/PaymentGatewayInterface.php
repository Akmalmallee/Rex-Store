<?php

namespace App\Services\Payment;

use App\Models\Order;
use App\Models\Payment;

interface PaymentGatewayInterface
{
    public function createPayment(Order $order, string $method, array $options = []): array;

    public function checkStatus(Payment $payment): array;

    public function handleCallback(array $payload): array;

    public function getClientKey(): ?string;
}

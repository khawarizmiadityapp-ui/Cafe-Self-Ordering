<?php

namespace App\Services\Contracts;

use App\Models\Order;
use App\Models\Payment;

interface PaymentGatewayInterface
{
    /**
     * Create a payment record / transaction request for an order.
     */
    public function createPayment(Order $order): Payment;

    /**
     * Verify or process payment confirmation.
     */
    public function verifyPayment(Payment $payment, array $payload = []): bool;
}

<?php

namespace App\Services\Payments;

use App\Models\Order;
use App\Models\Payment;
use App\Services\Contracts\PaymentGatewayInterface;
use Illuminate\Support\Str;

class QrisPaymentService implements PaymentGatewayInterface
{
    public function createPayment(Order $order): Payment
    {
        $referenceNumber = 'QRIS-' . strtoupper(Str::random(10));

        // Format standard QRIS dummy payload string adhering to total order amount
        $qrPayload = sprintf(
            "00020101021226580016ID.CO.QRIS.WWW01189360091400000000005204581253033605408%0.2f5802ID5912KAFE_DIGITAL6007JAKARTA6304",
            $order->total_amount
        );

        return Payment::create([
            'order_id' => $order->id,
            'payment_gateway' => 'qris_simulated',
            'transaction_id' => 'TX-' . Str::uuid(),
            'reference_number' => $referenceNumber,
            'amount' => $order->total_amount,
            'status' => 'PENDING',
            'payload' => [
                'qr_string' => $qrPayload,
                'merchant_name' => config('app.name', 'Cafe Self-Ordering'),
                'expires_at' => now()->addMinutes(15)->toDateTimeString(),
            ],
        ]);
    }

    public function verifyPayment(Payment $payment, array $payload = []): bool
    {
        if ($payment->status === 'PAID') {
            return true;
        }

        // Process payment status update
        $payment->update([
            'status' => 'PAID',
            'payload' => array_merge($payment->payload ?? [], [
                'paid_at' => now()->toDateTimeString(),
                'gateway_response' => $payload,
            ]),
        ]);

        $order = $payment->order;
        $order->update([
            'payment_status' => 'PAID',
            'paid_at' => now(),
        ]);

        return true;
    }
}

<?php

namespace App\Services\Payments;

use App\Models\Order;
use App\Models\Payment;
use App\Services\Contracts\PaymentGatewayInterface;
use Illuminate\Support\Str;

class CashPaymentService implements PaymentGatewayInterface
{
    public function createPayment(Order $order): Payment
    {
        return Payment::create([
            'order_id' => $order->id,
            'payment_gateway' => 'cash',
            'transaction_id' => 'CASH-' . Str::upper(Str::random(8)),
            'reference_number' => 'CASH-' . $order->order_number,
            'amount' => $order->total_amount,
            'status' => 'PENDING',
            'payload' => [
                'cashier_note' => 'Menunggu Pembayaran Tunai di Kasir',
            ],
        ]);
    }

    public function verifyPayment(Payment $payment, array $payload = []): bool
    {
        $payment->update([
            'status' => 'PAID',
            'payload' => array_merge($payment->payload ?? [], [
                'confirmed_by' => auth()->id() ?? 'kasir',
                'cashier_name' => auth()->user()?->name ?? 'Kasir',
                'confirmed_at' => now()->toDateTimeString(),
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

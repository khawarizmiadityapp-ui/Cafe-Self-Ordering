<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\Payments\CashPaymentService;
use Illuminate\Http\Request;

class CashierController extends Controller
{
    public function dashboard(Request $request)
    {
        $today = now()->startOfDay();

        $stats = [
            'order_baru' => Order::where('created_at', '>=', $today)
                ->where('order_status', 'PENDING')
                ->count(),
            'menunggu_pembayaran' => Order::where('created_at', '>=', $today)
                ->where('payment_status', 'UNPAID')
                ->count(),
            'diproses' => Order::where('created_at', '>=', $today)
                ->whereIn('order_status', ['WAITING_KITCHEN', 'PROCESSING'])
                ->count(),
            'selesai' => Order::where('created_at', '>=', $today)
                ->where('order_status', 'COMPLETED')
                ->count(),
            'total_pendapatan' => Order::where('created_at', '>=', $today)
                ->where('payment_status', 'PAID')
                ->sum('total_amount'),
        ];

        $filterStatus = $request->query('status', 'all');

        $query = Order::with(['table', 'items.product', 'payment'])
            ->orderBy('created_at', 'desc');

        if ($filterStatus === 'unpaid') {
            $query->where('payment_status', 'UNPAID');
        } elseif ($filterStatus === 'paid') {
            $query->where('payment_status', 'PAID')->where('order_status', 'PENDING');
        } elseif ($filterStatus === 'processing') {
            $query->whereIn('order_status', ['WAITING_KITCHEN', 'PROCESSING']);
        } elseif ($filterStatus === 'completed') {
            $query->where('order_status', 'COMPLETED');
        }

        $orders = $query->paginate(15);

        if ($request->wantsJson()) {
            return response()->json([
                'stats' => $stats,
                'orders' => $orders,
            ]);
        }

        return view('cashier.dashboard', [
            'stats' => $stats,
            'orders' => $orders,
            'currentFilter' => $filterStatus,
        ]);
    }

    public function confirmPayment($id)
    {
        $order = Order::with('payment')->findOrFail($id);

        if ($order->payment_status === 'PAID') {
            return back()->with('info', 'Pembayaran untuk pesanan ini sudah LUNAS.');
        }

        if ($order->payment) {
            $cashService = new CashPaymentService();
            $cashService->verifyPayment($order->payment);
        } else {
            $order->update([
                'payment_status' => 'PAID',
                'paid_at' => now(),
            ]);
        }

        return back()->with('success', "Pembayaran untuk Order #{$order->order_number} berhasil dikonfirmasi (PAID)!");
    }

    public function sendToKitchen($id)
    {
        $order = Order::findOrFail($id);

        if ($order->payment_status !== 'PAID') {
            return back()->with('error', 'Pesanan harus LUNAS sebelum dikirim ke Dapur/Barista.');
        }

        $order->update([
            'order_status' => 'WAITING_KITCHEN',
        ]);

        return back()->with('success', "Order #{$order->order_number} berhasil dikirim ke Dapur/Barista!");
    }

    public function cancelOrder($id)
    {
        $order = Order::findOrFail($id);
        $order->update([
            'order_status' => 'CANCELLED',
        ]);

        return back()->with('success', "Order #{$order->order_number} telah dibatalkan.");
    }
}

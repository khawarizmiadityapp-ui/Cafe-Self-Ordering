<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Table;
use App\Services\OrderNumberGenerator;
use App\Services\Payments\CashPaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CashierController extends Controller
{
    public function dashboard(Request $request)
    {
        $today = now()->startOfDay();

        $stats = [
            'order_baru' => Order::where('created_at', '>=', $today)
                ->where('is_archived', false)
                ->where('order_status', 'PENDING')
                ->count(),
            'menunggu_pembayaran' => Order::where('created_at', '>=', $today)
                ->where('is_archived', false)
                ->where('payment_status', 'UNPAID')
                ->count(),
            'diproses' => Order::where('created_at', '>=', $today)
                ->where('is_archived', false)
                ->whereIn('order_status', ['WAITING_KITCHEN', 'PROCESSING'])
                ->count(),
            'selesai' => Order::where('created_at', '>=', $today)
                ->where('is_archived', false)
                ->where('order_status', 'COMPLETED')
                ->count(),
            'total_pendapatan' => Order::where('created_at', '>=', $today)
                ->where('payment_status', 'PAID')
                ->sum('total_amount'),
        ];

        // Active unarchived orders for cashier view
        $orders = Order::with(['table', 'items.product', 'payment'])
            ->where('is_archived', false)
            ->orderBy('created_at', 'desc')
            ->get();

        // All paid raw orders today for real turnover calculation
        $allOrdersToday = Order::with(['table', 'items.product', 'payment'])
            ->where('created_at', '>=', $today)
            ->get();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'stats' => $stats,
                'orders' => $orders,
                'all_orders' => $allOrdersToday,
            ]);
        }

        return view('cashier.dashboard', [
            'stats' => $stats,
            'orders' => $orders,
            'allOrdersToday' => $allOrdersToday,
            'currentFilter' => 'all',
        ]);
    }

    /**
     * Point of Sale (POS) Interface for Cashier
     */
    public function pos(Request $request)
    {
        $categories = Category::where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->with(['products' => function ($q) {
                $q->where('is_available', true)->orderBy('name', 'asc');
            }])
            ->get();

        $products = Product::with('category')
            ->where('is_available', true)
            ->orderBy('name', 'asc')
            ->get();

        $tables = Table::where('status', 'active')
            ->orderBy('table_number', 'asc')
            ->get();

        return view('cashier.pos', [
            'categories' => $categories,
            'products' => $products,
            'tables' => $tables,
        ]);
    }

    /**
     * Store POS Direct Order & Payment
     */
    public function posCheckout(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:100',
            'order_type' => 'required|in:dine_in,takeaway',
            'table_id' => 'nullable|required_if:order_type,dine_in|exists:tables,id',
            'cart_items' => 'required',
            'payment_method' => 'required|in:cash,qris',
            'cash_received' => 'nullable|numeric|min:0',
            'order_action' => 'nullable|in:send_kitchen,direct_complete',
        ]);

        $rawItems = is_array($validated['cart_items']) 
            ? $validated['cart_items'] 
            : json_decode($validated['cart_items'], true);

        if (empty($rawItems) || !is_array($rawItems)) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Keranjang pesanan masih kosong.'], 422);
            }
            return back()->with('error', 'Keranjang pesanan masih kosong.');
        }

        try {
            $order = DB::transaction(function () use ($validated, $rawItems) {
                $totalAmount = 0;
                $processedItems = [];

                foreach ($rawItems as $item) {
                    $productId = $item['id'] ?? null;
                    $qty = max(1, intval($item['qty'] ?? 1));
                    $notes = isset($item['notes']) ? trim($item['notes']) : null;

                    $product = Product::findOrFail($productId);
                    if (!$product->is_available) {
                        throw new \Exception("Produk '{$product->name}' saat ini tidak tersedia.");
                    }

                    $unitPrice = $product->price;
                    $subtotal = $unitPrice * $qty;
                    $totalAmount += $subtotal;

                    $processedItems[] = [
                        'product_id' => $product->id,
                        'price' => $unitPrice,
                        'quantity' => $qty,
                        'subtotal' => $subtotal,
                        'notes' => $notes,
                    ];
                }

                $paymentMethod = $validated['payment_method'];
                $cashReceived = floatval($validated['cash_received'] ?? $totalAmount);

                if ($paymentMethod === 'cash' && $cashReceived < $totalAmount) {
                    throw new \Exception('Nominal uang diterima kurang dari total tagihan (Rp ' . number_format($totalAmount, 0, ',', '.') . ').');
                }

                $cashChange = $paymentMethod === 'cash' ? max(0, $cashReceived - $totalAmount) : 0;
                $tableId = ($validated['order_type'] === 'dine_in') ? $validated['table_id'] : null;
                $orderStatus = ($validated['order_action'] ?? 'send_kitchen') === 'direct_complete' ? 'COMPLETED' : 'WAITING_KITCHEN';

                $orderNumber = OrderNumberGenerator::generate();

                $order = Order::create([
                    'order_number' => $orderNumber,
                    'table_id' => $tableId,
                    'customer_name' => strip_tags($validated['customer_name']),
                    'total_amount' => $totalAmount,
                    'payment_method' => $paymentMethod,
                    'payment_status' => 'PAID',
                    'order_status' => $orderStatus,
                    'paid_at' => now(),
                ]);

                foreach ($processedItems as $pItem) {
                    $pItem['order_id'] = $order->id;
                    OrderItem::create($pItem);
                }

                Payment::create([
                    'order_id' => $order->id,
                    'payment_gateway' => $paymentMethod,
                    'transaction_id' => strtoupper($paymentMethod) . '-' . Str::upper(Str::random(8)),
                    'reference_number' => strtoupper($paymentMethod) . '-' . $order->order_number,
                    'amount' => $totalAmount,
                    'status' => 'PAID',
                    'payload' => [
                        'cash_received' => $cashReceived,
                        'cash_change' => $cashChange,
                        'order_type' => $validated['order_type'],
                        'card_reference' => $validated['card_reference'] ?? null,
                        'cashier_id' => auth()->id(),
                        'cashier_name' => auth()->user()->name ?? 'Kasir',
                        'confirmed_at' => now()->toDateTimeString(),
                    ],
                ]);

                return $order;
            });

            $order->load(['table', 'items.product', 'payment']);

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => "Order #{$order->order_number} berhasil diproses dan dicatat LUNAS!",
                    'order' => $order,
                    'receipt_url' => route('cashier.orders.receipt', $order->id),
                ]);
            }

            return redirect()->route('cashier.pos')->with('success', "Order #{$order->order_number} berhasil diproses!");

        } catch (\Exception $e) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
            }
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * Get Receipt Data / View for Thermal Printing
     */
    public function getReceipt($id)
    {
        $order = Order::with(['table', 'items.product', 'payment'])->findOrFail($id);

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'order' => $order,
                'cashier_name' => $order->payment->payload['cashier_name'] ?? (auth()->user()->name ?? 'Kasir'),
                'cash_received' => $order->payment->payload['cash_received'] ?? $order->total_amount,
                'cash_change' => $order->payment->payload['cash_change'] ?? 0,
            ]);
        }

        return view('cashier.receipt', [
            'order' => $order,
        ]);
    }

    /**
     * Confirm Cash Payment with Change Calculation (for QR Self-orders)
     */
    public function confirmCashWithChange(Request $request, $id)
    {
        $order = Order::with('payment')->findOrFail($id);

        if ($order->payment_status === 'PAID') {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Pesanan ini sudah LUNAS.'], 422);
            }
            return back()->with('info', 'Pembayaran untuk pesanan ini sudah LUNAS.');
        }

        $minAmount = floatval($order->total_amount);

        $validated = $request->validate([
            'cash_received' => 'required|numeric|min:' . $minAmount,
        ], [
            'cash_received.required' => 'Nominal uang tunai harus diisi.',
            'cash_received.numeric' => 'Nominal uang tunai harus berupa angka.',
            'cash_received.min' => 'Nominal uang tunai kurang dari total tagihan (Rp ' . number_format($minAmount, 0, ',', '.') . ').',
        ]);

        $cashReceived = floatval($validated['cash_received']);
        $cashChange = max(0, $cashReceived - $minAmount);

        DB::transaction(function () use ($order, $cashReceived, $cashChange) {
            if ($order->payment) {
                $order->payment->update([
                    'status' => 'PAID',
                    'payload' => array_merge($order->payment->payload ?? [], [
                        'cash_received' => $cashReceived,
                        'cash_change' => $cashChange,
                        'confirmed_by' => auth()->id(),
                        'cashier_name' => auth()->user()->name ?? 'Kasir',
                        'confirmed_at' => now()->toDateTimeString(),
                    ]),
                ]);
            } else {
                Payment::create([
                    'order_id' => $order->id,
                    'payment_gateway' => 'cash',
                    'transaction_id' => 'CASH-' . Str::upper(Str::random(8)),
                    'reference_number' => 'CASH-' . $order->order_number,
                    'amount' => $order->total_amount,
                    'status' => 'PAID',
                    'payload' => [
                        'cash_received' => $cashReceived,
                        'cash_change' => $cashChange,
                        'confirmed_by' => auth()->id(),
                        'cashier_name' => auth()->user()->name ?? 'Kasir',
                        'confirmed_at' => now()->toDateTimeString(),
                    ],
                ]);
            }

            $order->update([
                'payment_status' => 'PAID',
                'paid_at' => now(),
            ]);
        });

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Pembayaran Order #{$order->order_number} berhasil dikonfirmasi LUNAS! Kembalian: Rp " . number_format($cashChange, 0, ',', '.'),
                'order' => $order->load(['table', 'items.product', 'payment']),
                'cash_received' => $cashReceived,
                'cash_change' => $cashChange,
            ]);
        }

        return back()->with('success', "Pembayaran Order #{$order->order_number} berhasil dikonfirmasi! Kembalian: Rp " . number_format($cashChange, 0, ',', '.'));
    }

    public function confirmPayment($id)
    {
        $order = Order::with('payment')->findOrFail($id);

        if ($order->payment_status === 'PAID') {
            return back()->with('info', 'Pembayaran untuk pesanan ini sudah LUNAS.');
        }

        DB::transaction(function () use ($order) {
            if ($order->payment) {
                $cashService = new CashPaymentService();
                $cashService->verifyPayment($order->payment);
            } else {
                Payment::create([
                    'order_id' => $order->id,
                    'payment_gateway' => $order->payment_method ?? 'cash',
                    'transaction_id' => strtoupper($order->payment_method ?? 'CASH') . '-' . Str::upper(Str::random(8)),
                    'reference_number' => strtoupper($order->payment_method ?? 'CASH') . '-' . $order->order_number,
                    'amount' => $order->total_amount,
                    'status' => 'PAID',
                    'payload' => [
                        'cash_received' => $order->total_amount,
                        'cash_change' => 0,
                        'confirmed_by' => auth()->id(),
                        'cashier_name' => auth()->user()->name ?? 'Kasir',
                        'confirmed_at' => now()->toDateTimeString(),
                    ],
                ]);

                $order->update([
                    'payment_status' => 'PAID',
                    'paid_at' => now(),
                ]);
            }
        });

        return back()->with('success', "Pembayaran untuk Order #{$order->order_number} berhasil dikonfirmasi (PAID)!");
    }

    public function sendToKitchen(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $order->update([
            'order_status' => 'WAITING_KITCHEN',
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Order #{$order->order_number} berhasil dikirim ke Dapur!",
                'order' => $order->load(['table', 'items.product', 'payment']),
            ]);
        }

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

    public function destroy(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $orderNumber = $order->order_number;
        $order->update(['is_archived' => true]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Order #{$orderNumber} diselesaikan & dihapus dari daftar aktif.",
            ]);
        }

        return back()->with('success', "Order #{$orderNumber} diselesaikan & dihapus dari daftar aktif.");
    }

    public function clearCompleted(Request $request)
    {
        $count = Order::where('order_status', 'COMPLETED')
            ->where('payment_status', 'PAID')
            ->where('is_archived', false)
            ->update(['is_archived' => true]);

        if ($count === 0) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada pesanan Selesai & Lunas yang perlu dihapus.',
                ]);
            }
            return back()->with('info', 'Tidak ada pesanan Selesai & Lunas yang perlu dihapus.');
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'count' => $count,
                'message' => "Berhasil menghapus {$count} pesanan Selesai & Lunas dari daftar aktif.",
            ]);
        }

        return back()->with('success', "Berhasil menghapus {$count} pesanan Selesai & Lunas dari daftar aktif.");
    }
}


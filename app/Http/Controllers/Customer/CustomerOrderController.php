<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Table;
use App\Services\OrderNumberGenerator;
use App\Services\Payments\CashPaymentService;
use App\Services\Payments\QrisPaymentService;
use App\Services\QrCodeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerOrderController extends Controller
{
    /**
     * Display public digital menu for customer table.
     * Route: GET /order?table=07
     */
    public function index(Request $request)
    {
        $tableNum = $request->query('table', '01'); // default to 01 if not passed in query

        $table = Table::where(function ($query) use ($tableNum) {
            $query->where('table_number', $tableNum)
                  ->orWhere('code', $tableNum);
        })->where('status', 'active')->first();

        if (!$table) {
            // Fallback to first active table if invalid parameter provided
            $table = Table::where('status', 'active')->first();
        }

        $categories = Category::where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->with(['products' => function ($query) {
                $query->orderBy('name', 'asc');
            }])
            ->get();

        return view('customer.menu', [
            'table' => $table,
            'categories' => $categories,
        ]);
    }

    /**
     * Store order securely with server-side recalculated prices.
     * Route: POST /order/checkout
     */
    public function storeOrder(Request $request)
    {
        $validated = $request->validate([
            'table_id' => 'required|exists:tables,id',
            'customer_name' => 'required|string|max:100',
            'payment_method' => 'required|in:cash,qris',
            'cart_items' => 'required|string', // JSON string from frontend cart
        ]);

        $rawItems = json_decode($validated['cart_items'], true);

        if (empty($rawItems) || !is_array($rawItems)) {
            return back()->with('error', 'Keranjang belanja Anda kosong. Silakan pilih menu terlebih dahulu.')->withInput();
        }

        $table = Table::findOrFail($validated['table_id']);

        try {
            $order = DB::transaction(function () use ($validated, $rawItems, $table) {
                $totalAmount = 0;
                $processedItems = [];

                foreach ($rawItems as $item) {
                    $productId = $item['id'] ?? null;
                    $qty = max(1, intval($item['qty'] ?? 1));
                    $notes = isset($item['notes']) ? trim($item['notes']) : null;

                    $product = Product::find($productId);

                    if (!$product || !$product->is_available) {
                        throw new \Exception("Produk '{$item['name']}' saat ini tidak tersedia.");
                    }

                    // Security enforcement: always use DB price
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

                $orderNumber = OrderNumberGenerator::generate();

                $order = Order::create([
                    'order_number' => $orderNumber,
                    'table_id' => $table->id,
                    'customer_name' => strip_tags($validated['customer_name']),
                    'total_amount' => $totalAmount,
                    'payment_method' => $validated['payment_method'],
                    'payment_status' => 'UNPAID',
                    'order_status' => 'PENDING',
                ]);

                foreach ($processedItems as $pItem) {
                    $pItem['order_id'] = $order->id;
                    OrderItem::create($pItem);
                }

                // Payment service initialization
                if ($validated['payment_method'] === 'qris') {
                    $qrisService = new QrisPaymentService();
                    $qrisService->createPayment($order);
                } else {
                    $cashService = new CashPaymentService();
                    $cashService->createPayment($order);
                }

                return $order;
            });

            if ($validated['payment_method'] === 'qris') {
                return redirect()->route('customer.payment.qris', ['order_number' => $order->order_number])
                    ->with('success', 'Pesanan berhasil dibuat. Silakan lakukan pembayaran QRIS.');
            }

            return redirect()->route('customer.order.status', ['order_number' => $order->order_number])
                ->with('success', 'Pesanan Anda telah diterima oleh Kasir! Silakan bayar di kasir.');

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * QRIS Payment View.
     * Route: GET /order/payment/{order_number}
     */
    public function qrisPayment($orderNumber)
    {
        $order = Order::with(['table', 'items.product', 'payment'])
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        $qrCodeUrl = QrCodeService::getQrImageUrl($order->payment->payload['qr_string'] ?? $order->order_number, 280);

        return view('customer.qris_payment', [
            'order' => $order,
            'qrCodeUrl' => $qrCodeUrl,
        ]);
    }

    /**
     * Simulate QRIS Payment Success (Webhook / Gateway Callback).
     * Route: POST /order/payment/{order_number}/simulate
     */
    public function simulateQrisPayment($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)->firstOrFail();

        if ($order->payment) {
            $qrisService = new QrisPaymentService();
            $qrisService->verifyPayment($order->payment, ['simulated_at' => now()->toDateTimeString()]);
        }

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Pembayaran QRIS Berhasil!',
                'redirect_url' => route('customer.order.status', ['order_number' => $order->order_number]),
            ]);
        }

        return redirect()->route('customer.order.status', ['order_number' => $order->order_number])
            ->with('success', 'Pembayaran QRIS berhasil dikonfirmasi!');
    }

    /**
     * Real-time Customer Order Status Tracker.
     * Route: GET /order/status/{order_number}
     */
    public function orderStatus($orderNumber)
    {
        $order = Order::with(['table', 'items.product', 'payment'])
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        if (request()->wantsJson()) {
            return response()->json([
                'payment_status' => $order->payment_status,
                'order_status' => $order->order_status,
                'updated_at' => $order->updated_at->diffForHumans(),
            ]);
        }

        return view('customer.status', [
            'order' => $order,
        ]);
    }
}

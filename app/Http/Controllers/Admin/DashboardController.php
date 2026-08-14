<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Table;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = now()->startOfDay();

        $stats = [
            'total_penjualan_hari_ini' => Order::where('created_at', '>=', $today)
                ->where('payment_status', 'PAID')
                ->sum('total_amount'),
            'total_order_hari_ini' => Order::where('created_at', '>=', $today)->count(),
            'order_diproses' => Order::whereIn('order_status', ['WAITING_KITCHEN', 'PROCESSING'])->count(),
            'order_selesai' => Order::where('created_at', '>=', $today)
                ->where('order_status', 'COMPLETED')
                ->count(),
            'total_produk' => Product::count(),
            'total_meja' => Table::count(),
        ];

        // Top selling products query
        $topProducts = OrderItem::select('product_id', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(subtotal) as total_sales'))
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.payment_status', 'PAID')
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->with('product')
            ->take(5)
            ->get();

        // Recent transactions
        $recentOrders = Order::with(['table', 'payment'])
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();

        return view('admin.dashboard', [
            'stats' => $stats,
            'topProducts' => $topProducts,
            'recentOrders' => $recentOrders,
        ]);
    }
}

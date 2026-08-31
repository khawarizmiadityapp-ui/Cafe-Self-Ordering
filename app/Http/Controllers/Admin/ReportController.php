<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $preset = $request->query('preset');
        $todayStr = now()->toDateString();

        if ($preset === 'today') {
            $startDate = $todayStr;
            $endDate = $todayStr;
        } elseif ($preset === 'yesterday') {
            $startDate = now()->subDay()->toDateString();
            $endDate = now()->subDay()->toDateString();
        } elseif ($preset === '7days') {
            $startDate = now()->subDays(6)->toDateString();
            $endDate = $todayStr;
        } elseif ($preset === 'month') {
            $startDate = now()->startOfMonth()->toDateString();
            $endDate = $todayStr;
        } else {
            $startDate = $request->query('start_date', $todayStr);
            $endDate = $request->query('end_date', $todayStr);
        }

        $ordersQuery = Order::with(['table', 'items.product', 'payment'])
            ->where('payment_status', 'PAID')
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate);

        $totalRevenue = (clone $ordersQuery)->sum('total_amount');
        $totalOrders = (clone $ordersQuery)->count();

        $cashRevenue = (clone $ordersQuery)->where('payment_method', 'cash')->sum('total_amount');
        $qrisRevenue = (clone $ordersQuery)->where('payment_method', 'qris')->sum('total_amount');

        $itemReport = OrderItem::select('product_id', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(subtotal) as total_amount'))
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.payment_status', 'PAID')
            ->whereDate('orders.created_at', '>=', $startDate)
            ->whereDate('orders.created_at', '<=', $endDate)
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->with('product')
            ->take(8)
            ->get();

        $avgOrderValue = $totalOrders > 0 ? ($totalRevenue / $totalOrders) : 0;
        $orders = $ordersQuery->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.reports.index', [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'totalRevenue' => $totalRevenue,
            'totalOrders' => $totalOrders,
            'cashRevenue' => $cashRevenue,
            'qrisRevenue' => $qrisRevenue,
            'avgOrderValue' => $avgOrderValue,
            'itemReport' => $itemReport,
            'orders' => $orders,
        ]);
    }

    /**
     * Export financial sales report to Excel (.csv format with UTF-8 BOM).
     */
    public function exportExcel(Request $request): StreamedResponse
    {
        $startDate = $request->query('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->query('end_date', now()->toDateString());

        $orders = Order::with(['table', 'items.product'])
            ->where('payment_status', 'PAID')
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->orderBy('created_at', 'desc')
            ->get();

        $totalRevenue = $orders->sum('total_amount');
        $totalOrders = $orders->count();
        $cashRevenue = $orders->where('payment_method', 'cash')->sum('total_amount');
        $qrisRevenue = $orders->where('payment_method', 'qris')->sum('total_amount');

        $itemReport = OrderItem::select('product_id', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(subtotal) as total_amount'))
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.payment_status', 'PAID')
            ->whereDate('orders.created_at', '>=', $startDate)
            ->whereDate('orders.created_at', '<=', $endDate)
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->with('product.category')
            ->get();

        $filename = "Laporan_Penjualan_Cafe_{$startDate}_sd_{$endDate}.csv";

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream(function () use ($startDate, $endDate, $orders, $totalRevenue, $totalOrders, $cashRevenue, $qrisRevenue, $itemReport) {
            $handle = fopen('php://output', 'w');

            // UTF-8 BOM for Microsoft Excel auto-encoding
            fputs($handle, "\xEF\xBB\xBF");

            // Header Section
            fputcsv($handle, ['LAPORAN PENJUALAN KAFE DIGITAL']);
            fputcsv($handle, ['Periode Tanggal', "{$startDate} s/d {$endDate}"]);
            fputcsv($handle, ['Tanggal Dibuat', now()->format('d M Y H:i:s')]);
            fputcsv($handle, []);

            // Summary Section
            fputcsv($handle, ['RINGKASAN EKSEKUTIF']);
            fputcsv($handle, ['Total Omset Lunas', 'Rp ' . number_format($totalRevenue, 0, ',', '.')]);
            fputcsv($handle, ['Total Transaksi', $totalOrders . ' Order']);
            fputcsv($handle, ['Pendapatan Cash', 'Rp ' . number_format($cashRevenue, 0, ',', '.')]);
            fputcsv($handle, ['Pendapatan QRIS', 'Rp ' . number_format($qrisRevenue, 0, ',', '.')]);
            fputcsv($handle, []);

            // Product Breakdown Section
            fputcsv($handle, ['REKAP PENJUALAN PER MENU']);
            fputcsv($handle, ['No', 'Nama Menu', 'Kategori', 'Porsi Terjual', 'Total Omset (Rp)']);
            $no = 1;
            foreach ($itemReport as $item) {
                fputcsv($handle, [
                    $no++,
                    $item->product->name ?? 'Menu',
                    $item->product->category->name ?? '-',
                    $item->total_qty,
                    $item->total_amount,
                ]);
            }
            fputcsv($handle, []);

            // Detailed Transactions Section
            fputcsv($handle, ['LOG TRANSAKSI LUNAS']);
            fputcsv($handle, ['Waktu Order', 'No Order', 'Meja', 'Nama Pelanggan', 'Metode Bayar', 'Status Bayar', 'Total Pembayaran (Rp)', 'Rincian Menu']);
            foreach ($orders as $order) {
                $itemList = $order->items->map(function ($it) {
                    return $it->product->name . ' (' . $it->quantity . 'x)';
                })->join(', ');

                fputcsv($handle, [
                    $order->created_at->format('Y-m-d H:i:s'),
                    $order->order_number,
                    'Meja ' . $order->table->table_number,
                    $order->customer_name,
                    strtoupper($order->payment_method),
                    $order->payment_status,
                    $order->total_amount,
                    $itemList,
                ]);
            }

            fclose($handle);
        }, 200, $headers);
    }
}

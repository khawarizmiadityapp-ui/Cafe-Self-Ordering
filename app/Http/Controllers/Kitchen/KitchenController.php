<?php

namespace App\Http\Controllers\Kitchen;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class KitchenController extends Controller
{
    public function dashboard(Request $request)
    {
        $orders = Order::with(['table', 'items.product'])
            ->whereIn('order_status', ['WAITING_KITCHEN', 'PROCESSING', 'READY'])
            ->orderBy('created_at', 'asc')
            ->get();

        if ($request->wantsJson()) {
            return response()->json([
                'orders' => $orders,
            ]);
        }

        return view('kitchen.dashboard', [
            'orders' => $orders,
        ]);
    }

    public function startProcess(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $order->update([
            'order_status' => 'PROCESSING',
        ]);

        $tableLabel = $order->table ? "Meja {$order->table->table_number}" : "Takeaway";

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Order #{$order->order_number} ({$tableLabel}) mulai diproses!",
                'order' => $order->load(['table', 'items.product']),
            ]);
        }

        return back()->with('success', "Order #{$order->order_number} ({$tableLabel}) mulai diproses!");
    }

    public function completeOrder(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $order->update([
            'order_status' => 'COMPLETED',
        ]);

        $tableLabel = $order->table ? "Meja {$order->table->table_number}" : "Takeaway";

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Order #{$order->order_number} ({$tableLabel}) SELESAI & SAJIKAN!",
                'order' => $order,
            ]);
        }

        return back()->with('success', "Order #{$order->order_number} ({$tableLabel}) SELESAI!");
    }
}

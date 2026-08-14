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

    public function startProcess($id)
    {
        $order = Order::findOrFail($id);
        $order->update([
            'order_status' => 'PROCESSING',
        ]);

        return back()->with('success', "Order #{$order->order_number} (Meja {$order->table->table_number}) mulai diproses!");
    }

    public function completeOrder($id)
    {
        $order = Order::findOrFail($id);
        $order->update([
            'order_status' => 'COMPLETED',
        ]);

        return back()->with('success', "Order #{$order->order_number} (Meja {$order->table->table_number}) SELESAI!");
    }
}

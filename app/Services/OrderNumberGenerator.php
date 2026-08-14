<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Carbon;

class OrderNumberGenerator
{
    public static function generate(): string
    {
        $todayStr = Carbon::now()->format('Ymd');
        $prefix = "ORD-{$todayStr}-";

        $latestOrder = Order::where('order_number', 'like', "{$prefix}%")
            ->orderBy('id', 'desc')
            ->first();

        if (!$latestOrder) {
            $nextSequence = 1;
        } else {
            $lastNumStr = substr($latestOrder->order_number, -4);
            $nextSequence = intval($lastNumStr) + 1;
        }

        return $prefix . sprintf('%04d', $nextSequence);
    }
}

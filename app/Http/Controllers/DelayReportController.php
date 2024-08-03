<?php

namespace App\Http\Controllers;

use App\Models\DelayReport;
use App\Models\Order;
use Carbon\Carbon as CarbonCarbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DelayReportController extends Controller
{
    public function store(Order $order): JsonResponse
    {
        if (!$order->isLate())
        {
            return response()->json(['message' => 'order is not late'], 400);
        }

        if ($order->hasOpenDelayReport())
        {
            return response()->json(['message' => 'order has open delay report'], 400);
        }

        $delayReport = new DelayReport([
            'delay_minute' => $order->delivery_time->diffInMinutes(Carbon::now()),
            'old_delivery_time' => $order->delivery_time
        ]);

        if ($order->trip?->isOngoing())
        {
            // $response = Http::get(env('CALC_NEW_DELIVERY_URL'));

            $order->delivery_time = Carbon::now()->addMinutes(random_int(1, 50));
            $order->save();
            
            $delayReport->state = 'new_delivery_time';
            $order->delayReports()->save($delayReport);

            return response()->json([
                'message' => 'order has new delivery time',
                'data' => ['new_delivery_time' => $order->delivery_time]
            ]);
        }

        $order->delayReports()->save($delayReport);

        return response()->json($order);
    }

    public function analyse(): JsonResponse
    {
        $reports = DelayReport::selectRaw('vendors.id AS vendor_id, SUM(delay_reports.delay_minute) AS delay_minute_sum')
            ->join('orders', 'delay_reports.order_id', 'orders.id')
            ->join('vendors', 'orders.vendor_id', 'vendors.id')
            ->where('delay_reports.created_at', '>=', now()->subWeek())
            ->groupBy('vendor_id')
            ->orderBy('delay_minute_sum', 'desc')
            ->get();
        
        return response()->json($reports);
    }
}

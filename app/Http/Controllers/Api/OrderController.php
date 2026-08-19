<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SearchOrdersRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use App\Models\Order;

class OrderController extends Controller
{   
    // Search for orders containing a medication with the specified batch, within a date range (default: last 30 days).
    public function index(SearchOrdersRequest $request): JsonResponse
    {
        $range = $request->dateRange(); 

        $orders = Order::query()
            ->whereHas('orderItems.medication', function ($query) use ($request){
                $query->where('lot_number', $request->input('lot'));
            })
            ->whereBetween('purchase_date', [$range['start'], $range['end']])
            ->with(['customer', 'orderItems.medication'])
            ->orderByDesc('purchase_date')
            ->paginate(15);

        return response()->json($orders);
    }

    // Full order Details
    public function show(Order $order): JsonResponse
    {
        $order->load(['customer', 'orderItems.meedication']);
        return response()->json($order);
    }
}

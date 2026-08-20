<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SearchOrdersRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use App\Models\Order;
use Symfony\Component\HttpFoundation\StreamedResponse;

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
        $order->load(['customer', 'orderItems.medication']);
        return response()->json($order);
    }

    public function export(SearchOrdersRequest $request): StreamedResponse
    {
        $range = $request->dateRange();

        $orders = Order::query()
            ->whereHas('orderItems.medication', function ($query) use ($request){
                $query->where('lot_number', $request->input('lot'));
            })
            ->whereBetween('purchase_date', [$range['start'], $range['end']])
            ->with(['customer', 'orderItems.medication'])
            ->orderByDesc('purchase_date')
            ->get();
        
        $fileName = 'orders_lot_'. $request->input('lot'). '_' . now()->format('Y-m-d_His') . '.csv';

        return response()->streamDownload(function () use ($orders){
            $handle = fopen('php://output', 'w');

            fputcsv($handle, ['Order ID', 'Customer Name', 'Customer Email', 'Customer Phone', 'Purchase Date', 'Medication', 'Lot Number']);

            foreach($orders as $order){
                foreach($order->orderItems as $item){
                    fputcsv($handle, [
                        $order->id,
                        $order->customer->name,
                        $order->customer->email,
                        $order->customer->phone,
                        $order->purchase_date->format('Y-m-d'),
                        $item->medication->name,
                        $item->medication->lot_number,
                    ]);
                }
            }

            fclose($handle);
        },$fileName, [
            'Content-Type' => 'text/csv',
        ]);
    }

}

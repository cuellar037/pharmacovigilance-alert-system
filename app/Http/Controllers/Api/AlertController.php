<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SendAlertRequest;
use App\Jobs\SendMedicationAlertJob;
use App\Models\Alert;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AlertController extends Controller
{   
    //Accepts an array of { customer_id, order_id } — supports individual or bulk submission.
    public function send(SendAlertRequest $request): JsonResponse
    {
        $createdAlerts = collect($request->validated('alerts'))
            ->values()
            ->map(function (array $item, int $index) use ($request) {
                $alert = Alert::create([
                    'customer_id' => $item['customer_id'],
                    'order_id' => $item['order_id'],
                    'user_id' => $request->user()->id,
                    'status' => 'pending',
                ]);

                SendMedicationAlertJob::dispatch($alert)
                    ->delay(now()->addSeconds($index * 2));

                return $alert;
            });

        return response()->json([
            'message' => count($createdAlerts) . 'alert(s) queued for sending', 
            'alerts' => $createdAlerts,
        ], 202);
    }
}

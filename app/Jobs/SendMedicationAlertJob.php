<?php

namespace App\Jobs;

use App\Mail\MedicationAlertMail;
use App\Models\Alert;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class SendMedicationAlertJob implements ShouldQueue
{
    use Queueable;

    // Numbeere of attemps before marking as failed
    public int $tries = 3;

    // Progresive wait between retire (in seconds)
    public array $backoff = [10,30,60];

    /**
     * Create a new job instance.
     */
    public function __construct(public Alert $alert)
    {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->alert->loadMissing('customer', 'order.orderItems.medication'); 

        Mail::to($this->alert->customer->email)
            ->send(new MedicationAlertMail($this->alert));
        
        $this->alert->update([
            'status' => 'sent', 
            'sent_at' => now(), 
            'error_message' => null,
        ]);
    }

    public function failed(Throwable $exception): void
    {
        $this->alert->update([
            'status' => 'failed', 
            'error_message' => $exception->getMessage(),
        ]);

        Log::error('Faileed to send medication alert email', [
            'alert_id' => $this->alert->id, 
            'customer_id' => $this->alert->customer->id, 
            'order_id' => $this->alert->order->id, 
            'error' => $exception->getMessage(), 
        ]);
    }
}

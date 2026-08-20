<?php

namespace App\Mail;

use App\Models\Alert;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MedicationAlertMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public Alert $alert)
    {}

    public function build()
    {
        $order = $this->alert->order()->with('orderItems.medication')->first();
        $medication = $order->orderItems->first()->medication;

        return $this->subject('Important Recall Notice - Medication Alert')
            ->markdown('emails.alerts.medication-alert', [
                'customerName' => $this->alert->customer->name, 
                'medicationName' => $medication->name, 
                'lotNumber' => $medication->lot_number, 
                'purchaseDate' => $order->purchase_date->format('d/m/Y')
            ]);
    }
    
   

    
}

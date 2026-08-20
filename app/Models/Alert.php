<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Alert extends Model
{
    /** @use HasFactory<\Database\Factories\AlertFactory> */
    use HasFactory;

    protected $fillable = [
        'customer_id', 
        'order_id',
        'user_id',
        'status', 
        'error_message',
        'set_at'
    ];

    protected $casts = [
        'set_at' => 'datetime',
    ];

    function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class); 
    } 

    function order(): BelongsTo
    {
        return $this->belongsTo(Order::class); 
    } 

    function user(): BelongsTo
    {
        return $this->belongsTo(User::class); 
    } 

}

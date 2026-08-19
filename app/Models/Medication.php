<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Medication extends Model
{
    /** @use HasFactory<\Database\Factories\MedicationFactory> */
    use HasFactory;

    protected $fillable = [
        'name', 
        'lot_number'
    ];

    public function orderItems(): HasMany
    {
        return $this-> hasMany(OrderItem::class);
    }

}

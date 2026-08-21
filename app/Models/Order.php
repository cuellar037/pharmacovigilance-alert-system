<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
  /** @use HasFactory<\Database\Factories\OrderFactory> */
  use HasFactory;

  protected $fillable = [
    'customer_id',
    'purchase_date'
  ];

  protected $casts = [
    'purchase_date' => 'date',
  ];

  public function customer(): BelongsTo
  {
    return $this->belongsTo(Customer::class);
  }

  public function orderItems(): HasMany
  {
    return $this->hasMany(OrderItem::class);
  }

  public function alerts(): HasMany
  {
    return $this->hasMany(Alert::class);
  }
}

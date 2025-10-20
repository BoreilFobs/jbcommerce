<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'offer_id',
        'product_name',
        'quantity',
        'unit_price',
        'discount_percentage',
        'discount_amount',
        'subtotal',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    /**
     * Relationship: OrderItem belongs to an Order
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Relationship: OrderItem belongs to an Offer (Product)
     */
    public function offer(): BelongsTo
    {
        return $this->belongsTo(offers::class, 'offer_id');
    }

    /**
     * Get the total price (unit_price * quantity)
     */
    public function getTotalPriceAttribute(): float
    {
        return $this->unit_price * $this->quantity;
    }

    /**
     * Get the discount amount calculated
     */
    public function getCalculatedDiscountAttribute(): float
    {
        if ($this->discount_percentage > 0) {
            return ($this->unit_price * $this->quantity) * ($this->discount_percentage / 100);
        }
        return $this->discount_amount;
    }
}

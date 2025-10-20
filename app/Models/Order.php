<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'order_number',
        'subtotal',
        'shipping_cost',
        'discount_amount',
        'total_amount',
        'status',
        'payment_method',
        'payment_status',
        'payment_reference',
        'payment_phone',
        'paid_at',
        'shipping_name',
        'shipping_phone',
        'shipping_email',
        'shipping_address',
        'shipping_city',
        'shipping_region',
        'shipping_postal_code',
        'customer_notes',
        'admin_notes',
        'tracking_number',
        'shipped_at',
        'delivered_at',
        'cancelled_at',
        'cancelled_reason',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    /**
     * Relationship: Order belongs to a User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship: Order has many OrderItems
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get status badge HTML for display
     */
    public function getStatusBadgeAttribute(): string
    {
        $badges = [
            'pending' => '<span class="badge bg-warning">En attente</span>',
            'confirmed' => '<span class="badge bg-info">Confirmé</span>',
            'processing' => '<span class="badge bg-primary">En traitement</span>',
            'shipped' => '<span class="badge bg-secondary">Expédié</span>',
            'delivered' => '<span class="badge bg-success">Livré</span>',
            'cancelled' => '<span class="badge bg-danger">Annulé</span>',
        ];

        return $badges[$this->status] ?? '<span class="badge bg-secondary">' . $this->status . '</span>';
    }

    /**
     * Get payment status badge HTML for display
     */
    public function getPaymentStatusBadgeAttribute(): string
    {
        $badges = [
            'pending' => '<span class="badge bg-warning">En attente</span>',
            'paid' => '<span class="badge bg-success">Payé</span>',
            'failed' => '<span class="badge bg-danger">Échoué</span>',
            'refunded' => '<span class="badge bg-info">Remboursé</span>',
        ];

        return $badges[$this->payment_status] ?? '<span class="badge bg-secondary">' . $this->payment_status . '</span>';
    }

    /**
     * Get payment method display name
     */
    public function getPaymentMethodNameAttribute(): string
    {
        $methods = [
            'mobile_money_mtn' => 'Mobile Money MTN',
            'mobile_money_orange' => 'Mobile Money Orange',
            'cash_on_delivery' => 'Paiement à la livraison',
            'bank_transfer' => 'Virement bancaire',
        ];

        return $methods[$this->payment_method] ?? $this->payment_method;
    }

    /**
     * Get status display name
     */
    public function getStatusNameAttribute(): string
    {
        $statuses = [
            'pending' => 'En attente',
            'confirmed' => 'Confirmé',
            'processing' => 'En traitement',
            'shipped' => 'Expédié',
            'delivered' => 'Livré',
            'cancelled' => 'Annulé',
        ];

        return $statuses[$this->status] ?? $this->status;
    }

    /**
     * Generate unique order number
     */
    public static function generateOrderNumber(): string
    {
        do {
            $orderNumber = 'ORD-' . date('Ymd') . '-' . strtoupper(substr(md5(uniqid()), 0, 6));
        } while (self::where('order_number', $orderNumber)->exists());

        return $orderNumber;
    }

    /**
     * Check if order can be cancelled
     */
    public function canBeCancelled(): bool
    {
        return in_array($this->status, ['pending', 'confirmed']);
    }

    /**
     * Check if order can be edited
     */
    public function canBeEdited(): bool
    {
        return in_array($this->status, ['pending', 'confirmed']);
    }

    /**
     * Scope: Get orders by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope: Get recent orders
     */
    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Scope: Get paid orders
     */
    public function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Vanilo\Order\Models\Order;

class OrderReturn extends Model
{
    protected $table = 'order_returns';

    protected $fillable = [
        'order_id',
        'return_number',
        'status',
        'reason',
        'customer_note',
        'refund_amount',
        'requested_at',
        'approved_at',
        'received_at',
        'refunded_at',
    ];

    protected $casts = [
        'refund_amount' => 'decimal:2',

        'requested_at' => 'datetime',
        'approved_at' => 'datetime',
        'received_at' => 'datetime',
        'refunded_at' => 'datetime',
    ];

    /**
     * Return belongs to an order.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(
            Order::class,
            'order_id'
        );
    }

    /**
     * Return has many return items.
     */
    public function orderReturnItems(): HasMany
    {
        return $this->hasMany(
            OrderReturnItem::class,
            'order_return_id'
        );
    }
}
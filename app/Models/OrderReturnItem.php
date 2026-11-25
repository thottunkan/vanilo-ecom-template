<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Vanilo\Order\Models\OrderItem;

class OrderReturnItem extends Model
{
    protected $table = 'order_return_items';

    protected $fillable = [
        'order_return_id',
        'order_item_id',
        'quantity',
        'reason',
        'condition',
        'refund_amount',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'refund_amount' => 'decimal:2',
    ];

    /**
     * Return item belongs to a return.
     */
    public function orderReturn(): BelongsTo
    {
        return $this->belongsTo(
            OrderReturn::class,
            'order_return_id'
        );
    }

    /**
     * Return item belongs to an order item.
     */
    public function orderItem(): BelongsTo
    {
        return $this->belongsTo(
            OrderItem::class,
            'order_item_id'
        );
    }
}
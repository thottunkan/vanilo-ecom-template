<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Vanilo\Foundation\Models\OrderItem;

class OrderCancelItem extends Model
{
    protected $fillable = [
        'order_cancel_id',
        'order_item_id',
        'quantity',
        'reason',
        'refund_amount',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'refund_amount' => 'decimal:2',
    ];

    public function orderCancel(): BelongsTo
    {
        return $this->belongsTo(
            OrderCancel::class,
            'order_cancel_id'
        );
    }

    public function orderItem(): BelongsTo
    {
        return $this->belongsTo(
            OrderItem::class,
            'order_item_id'
        );
    }
}

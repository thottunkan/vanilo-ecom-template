<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Vanilo\Order\Models\Order;

class OrderCancel extends Model
{
    protected $fillable = [
        'order_id',
        'cancel_number',
        'status',
        'reason',
        'customer_note',
        'admin_comment',
        'refund_amount',
        'requested_at',
        'approved_at',
        'rejected_at',
        'completed_at',
    ];

    protected $casts = [
        'refund_amount' => 'decimal:2',

        'requested_at' => 'datetime',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(
            Order::class,
            'order_id'
        );
    }

    public function orderCancelItems(): HasMany
    {
        return $this->hasMany(
            OrderCancelItem::class,
            'order_cancel_id'
        );
    }
}

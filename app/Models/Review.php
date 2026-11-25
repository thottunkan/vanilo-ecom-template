<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Vanilo\Foundation\Models\MasterProduct;

class Review extends Model
{
    protected $fillable = [
        'user_id',
        'product_id',
        'rating',
        'comments',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(MasterProduct::class, 'product_id', 'id');
    }
}
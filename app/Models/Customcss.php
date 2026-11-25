<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customcss extends Model
{
    protected $fillable = [
        'style_name',
        'slug',
        'css',
        'is_active',
        'locked_by',
        'locked_at',
    ];

    protected $casts = [
        'locked_at' => 'datetime',
    ];
}

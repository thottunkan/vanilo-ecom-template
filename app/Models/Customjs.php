<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customjs extends Model
{
    protected $fillable = [
        'script_name',
        'slug',
        'script',
        'is_active',
        'locked_by',
        'locked_at',
    ];

    protected $casts = [
        'locked_at' => 'datetime',
    ];
}

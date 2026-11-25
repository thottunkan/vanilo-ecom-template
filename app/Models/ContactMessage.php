<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'first_name',
        'last_name',
        'status',
        'email',
        'phone',
        'message',
        'user_agent',
    ];
}

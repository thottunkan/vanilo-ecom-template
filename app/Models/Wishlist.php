<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Vanilo\Foundation\Models\MasterProduct;

class Wishlist extends Model
{
    protected $fillable = [
        'master_product_id',
        'user_id'
    ];


    public function product()
    {
        return $this->belongsTo(MasterProduct::class, 'master_product_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }



    public static function itemCount()
    {
        return static::where('user_id', auth()->id())->count();
    }

    public static function clear()
    {
        return static::where('user_id', auth()->id())->delete();
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FAQ extends Model
{
    protected $table = 'faq_s';

    protected $fillable = [
        'faq_qn',
        'faq_ans',
        'banner_image_path',
        'status',
        'category_id'
    ];
    public function category()
    {
        return $this->belongsTo(FaqCategory::class, 'category_id');
    }
}

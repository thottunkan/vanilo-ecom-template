<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteInfo extends Model
{
    protected $fillable = [
        'company_name',
        'company_email',
        'company_address',
        'company_phone_number',
        'footer_text',
        'copyright',
        'logo_path',
        'favicon_path',
    ];
}

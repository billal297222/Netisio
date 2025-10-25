<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    use HasFactory;
     protected $fillable = [
        'system_title',
        'system_short_title',
        'logo',
        'minilogo',
        'favicon',
        'company_name',
        'tag_line',
        'phone_code',
        'phone_number',
        'whatsapp',
        'email',
        'time_zone',
        'language',
        'copyright_text',

        'admin_title',
        'admin_short_title',
        'admin_logo',
        'admin_mini_logo',
        'admin_favicon',
        'admin_copyright_text',
    ];

     protected $casts = [
        'system_title'       => 'string',
        'system_short_title' => 'string',
        'logo'               => 'string',
        'minilogo'           => 'string',
        'favicon'            => 'string',
        'company_name'       => 'string',
        'tag_line'           => 'string',
        'phone_number'       => 'string',
        'whatsapp'           => 'string',
        'email'              => 'string',
        'time_zone'          => 'string',
        'language'           => 'string',
        'copyright_text'     => 'string',

        'admin_title'       => 'string',
        'admin_short_title' => 'string',
        'admin_logo'        => 'string',
        'admin_mini_logo'   => 'string',
        'admin_favicon'     => 'string',
        'admin_copyright_text' => 'string',
        'phone_code' => 'string',
    ];
    
}

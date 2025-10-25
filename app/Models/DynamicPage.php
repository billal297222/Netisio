<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DynamicPage extends Model
{
    // use HasFactory;
     protected $fillable = [
        'page_title',
        'page_content',
        'status',
    ];

    protected $casts = [
        'page_title' => 'string',
        'page_content' => 'string',
        'status' => 'string',
    ];
}

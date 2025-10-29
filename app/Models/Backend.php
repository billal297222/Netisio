<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Backend extends Model
{
    use HasFactory;

    protected $table = 'backend'; 

    protected $fillable = [
        'monthly_limit',
    ];
}

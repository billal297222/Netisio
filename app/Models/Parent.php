<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class ParentModel extends Authenticatable implements JWTSubject
{
    use JwtAuthenticatable;
    use HasFactory, Notifiable;

    protected $table = 'parents';

    protected $fillable = [
        'full_name',
        'email',
        'password',
        'is_verified',
        'balance',
        'email_otp',
        'otp_expires_at',
    ];

    protected $hidden = [
        'password',
        'email_otp',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'otp_expires_at' => 'datetime',
        'balance' => 'decimal:2',
    ];
}

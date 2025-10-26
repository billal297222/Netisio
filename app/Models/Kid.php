<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Kid extends Authenticatable implements JWTSubject
{
    use HasFactory;

    protected $table = 'kids';

    protected $fillable = [
        'family_id',
        'parent_id',
        'username',
        'password',
        'pin',
        'full_name',
        'email',
        'kavatar',
        'balance',
        'today_can_spend',
    ];

    protected $hidden = [
        'password',
        'pin',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
        'today_can_spend' => 'decimal:2',
    ];

    // JWT methods
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}

<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Support\Facades\Hash;

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
        'k_unique_id',
    ];

    protected $hidden = [
        'password',
        'pin',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
        'today_can_spend' => 'decimal:2',
    ];


    public function family()
    {
        return $this->belongsTo(Family::class, 'family_id');
    }


    public function parent()
    {
        return $this->belongsTo(ParentModel::class, 'parent_id');
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}

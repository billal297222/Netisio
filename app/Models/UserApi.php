<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class UserApi extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    protected $table = 'users'; 

    protected $fillable = [
        'avatar',
        'name',
        'email',
        'username',
        'password',
        'status',
        'admin',
        'phone_number',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'status' => 'string',
    ];

    public function getIsActiveAttribute()
    {
        return strtolower($this->status) === 'active';
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

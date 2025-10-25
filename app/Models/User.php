<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use LdapRecord\Laravel\Auth\LdapAuthenticatable;
use LdapRecord\Laravel\Auth\AuthenticatesWithLdap;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements LdapAuthenticatable, JWTSubject
{
    use AuthenticatesWithLdap;

    protected $fillable = [
        'name',
        'email',
        'uid',
        'password'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Required by JWTSubject
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}

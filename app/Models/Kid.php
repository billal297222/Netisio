<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kid extends Model implements JWTSubject
{
    use JwtAuthenticatable;
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
    ];

    protected $hidden = [
        'password',
        'pin',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
    ];

    public function parent()
    {
        return $this->belongsTo(ParentModel::class, 'parent_id');
    }

    public function family()
    {
        return $this->belongsTo(Family::class, 'family_id');
    }
}

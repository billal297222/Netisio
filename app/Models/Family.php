<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Family extends Model
{
    use HasFactory;

    protected $table = 'families';

    protected $fillable = [
        'name',
        'favatar',
        'created_by_parent',
    ];

    public function parent()
    {
        return $this->belongsTo(ParentModel::class, 'created_by_parent');
    }

    public function kids()
    {
        return $this->hasMany(Kid::class, 'family_id');
    }
}

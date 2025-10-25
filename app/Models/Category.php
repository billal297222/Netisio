<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class Category extends Model
{
    // use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'image',
        'priority',
        'status',
    ];

    protected $casts = [
        'name' => 'string',
        'slug' => 'string',
        'image' => 'string',
        'priority' => 'integer',
        'status' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        // Ignore default category with id=1
        static::addGlobalScope('ignoreDefaultCategory', function (Builder $builder) {
            $builder->where('id', '!=', 1)->orderBy('priority', 'asc');
        });
    }
}

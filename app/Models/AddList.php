<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddList extends Model
{
    use HasFactory;

    protected $table = 'add_list';

    protected $fillable = [
        'kid_id',
        'member_type',
        'member_unique_id',
        'member_name',
        'member_avatar',
    ];

    public function kid()
    {
        return $this->belongsTo(Kid::class, 'kid_id');
    }

    public function getMemberAvatarUrlAttribute()
    {
        return $this->member_avatar ? asset($this->member_avatar) : null;
    }
}

<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KidNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'kid_id',
        'title',
        'message',
        'data',
        'is_read',
    ];

    protected $casts = [
        'data' => 'array', 
        'is_read' => 'boolean',
    ];

    public function kid()
    {
        return $this->belongsTo(Kid::class);
    }
}


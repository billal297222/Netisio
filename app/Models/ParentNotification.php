<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParentNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'title',
        'message',
        'data',
        'is_read',
    ];

    protected $casts = [
        'data' => 'array',
        'is_read' => 'boolean',
    ];

    public function parent()
    {
        return $this->belongsTo(ParentModel::class);
    }
}


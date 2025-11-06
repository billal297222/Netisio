<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Saving extends Model
{
    use HasFactory;

    protected $table = 'saving_goals';

    protected $fillable = [
        'kid_id',
        'title',
        'target_amount',
        'saved_amount',
        'status',
        'created_by_parent_id',

    ];

    protected $casts = [
        'target_amount' => 'decimal:2',
        'saved_amount' => 'decimal:2',
        'progress_percentage' => 'decimal:2',
    ];

    public function kid()
    {
        return $this->belongsTo(Kid::class, 'kid_id');
    }

    public function createdByParent()
    {
        return $this->belongsTo(ParentModel::class, 'created_by_parent_id');
    }
}

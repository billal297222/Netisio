<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'kid_id',
        'title',
        'description',
        'reward_amount',
        'status',
        'due_date',
        'created_by_parent_id',
    ];

    protected $casts = [
        'reward_amount' => 'decimal:2',
        'due_date' => 'date',
    ];

    public function kid()
    {
        return $this->belongsTo(Kid::class, 'kid_id');
    }

    public function parent()
    {
        return $this->belongsTo(ParentModel::class, 'created_by_parent_id');
    }

    public function getIsTodayAttribute()
    {
        return $this->due_date->isToday();
    }

    public function getIsCompletedAttribute()
    {
        return $this->status === 'completed';
    }

    public function scopeToday($query)
    {
        return $query->whereDate('due_date', Carbon::today());
    }
}

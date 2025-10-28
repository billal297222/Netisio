<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class WeeklyPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'kid_id',
        'title',
        'amount',
        'due_in_days',
        'status',
        'created_by_parent_id',
    ];

    public function kid()
    {
        return $this->belongsTo(Kid::class);
    }

    public function parent()
    {
        return $this->belongsTo(ParentModel::class, 'created_by_parent_id');
    }

    public function getDueDateAttribute()
    {
        if (!$this->due_in_days) {
        return null; 
    }

    $dueDate = Carbon::today()->addDays($this->due_in_days);
    $diff = Carbon::today()->diffInDays($dueDate, false);

    return $diff >= 0 ? $diff : 0;
    }
}

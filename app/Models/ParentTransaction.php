<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParentTransaction extends Model
{
    use HasFactory;

    protected $table = 'parent_transactions';

    protected $fillable = [
        'parent_id',
        'kid_id',
        'type',
        'amount',
        'max_deposit',
        'message',
        'transaction_datetime',
    ];
    protected $casts = [
    'transaction_datetime' => 'datetime',
];


    // Relation to parent
    public function parent()
    {
        return $this->belongsTo(ParentModel::class, 'parent_id');
    }

    // Relation to kid (optional, if transaction is for a kid)
    public function kid()
    {
        return $this->belongsTo(Kid::class, 'kid_id');
    }
}

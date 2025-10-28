<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KidTransaction extends Model
{
    use HasFactory;

    protected $table = 'kid_transactions';

    protected $fillable = [
        'kid_id',
        'receiver_kid_id',
        'sender_parent_id',
        'type',
        'amount',
        'status',
        'transaction_date',
        'note',
    ];

    public function senderKid()
    {
        return $this->belongsTo(Kid::class, 'kid_id');
    }

    public function receiverKid()
    {
        return $this->belongsTo(Kid::class, 'receiver_kid_id');
    }

    public function receiverParent()
    {
        return $this->belongsTo(ParentModel::class, 'sender_parent_id');
    }
}

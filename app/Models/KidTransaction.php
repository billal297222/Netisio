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
        'receiver_parent_id',
        'sender_parent_id',
        'saving_goal_id',
        'type',
        'amount',
        'status',
        'transaction_date',
        'note',
    ];

    // Sender kid
    public function senderKid()
    {
        return $this->belongsTo(Kid::class, 'kid_id');
    }
    public function kid()
{
    return $this->belongsTo(Kid::class, 'kid_id');
}


    // Receiver kid
    public function receiverKid()
    {
        return $this->belongsTo(Kid::class, 'receiver_kid_id');
    }

    // Parent who sent money
    public function senderParent()
    {
        return $this->belongsTo(ParentModel::class, 'sender_parent_id');
    }

    // Related saving goal
    public function goal()
    {
        return $this->belongsTo(Saving::class, 'saving_goal_id');
    }
    public function receiverParent()
{
    return $this->belongsTo(ParentModel::class, 'receiver_parent_id');
}

}

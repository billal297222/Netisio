<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pdf extends Model
{
    protected $fillable = ['date_id', 'title', 'short_desc', 'file_path'];

    public function date()
    {
        return $this->belongsTo(Date::class);
    }
}

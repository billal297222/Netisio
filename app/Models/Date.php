<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Date extends Model
{
    protected $fillable = ['date_value'];

    public function pdfs()
    {
        return $this->hasMany(Pdf::class);
    }
}

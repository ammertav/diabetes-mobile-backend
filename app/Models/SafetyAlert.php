<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SafetyAlert extends Model
{
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function fgbRecord()
    {
        return $this->belongsTo(FgbRecord::class);
    }
}

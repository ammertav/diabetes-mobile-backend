<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FgbRecord extends Model
{
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function userProtocol()
    {
        return $this->belongsTo(UserProtocol::class);
    }

    public function fastingLog()
    {
        return $this->belongsTo(FastingLog::class);
    }

    public function alerts()
    {
        return $this->hasMany(SafetyAlert::class);
    }
}

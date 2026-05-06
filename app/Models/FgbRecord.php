<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class FgbRecord extends Model
{
    use HasUuids;
    protected $guarded = ['id'];
    protected $keyType = 'string';
    public $incrementing = false;

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

    public function alert()
    {
        return $this->hasOne(SafetyAlert::class, 'fgb_record_id');
    }
}

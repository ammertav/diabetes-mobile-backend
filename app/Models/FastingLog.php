<?php

namespace App\Models;

use App\Enums\FastingLogMood;
use App\Enums\FastingLogStatus;
use Illuminate\Database\Eloquent\Model;

class FastingLog extends Model
{
    protected $guarded = ['id'];
    protected $casts = [
        'status' => FastingLogStatus::class,
        'mood' => FastingLogMood::class,
        'planned_date' => 'date',
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'confirmed_at' => 'datetime',
    ];

    public function userProtocol()
    {
        return $this->belongsTo(UserProtocol::class);
    }
}

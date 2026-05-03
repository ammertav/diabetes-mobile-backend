<?php

namespace App\Models;

use App\Enums\UserProtocolStatus;
use Illuminate\Database\Eloquent\Model;

class UserProtocol extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'status' => UserProtocolStatus::class,
        'start_date' => 'date',
        'end_date' => 'date'
    ];

    public function protocol()
    {
        return $this->belongsTo(FastingProtocol::class, 'fasting_protocol_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function logs()
    {
        return $this->hasMany(FastingLog::class);
    }

    public function nowFastingLog(): ?FastingLog
    {
        $today = now()->toDateString();

        return $this->logs()
            ->whereDate('start_time', '<=', $today)
            ->whereDate('end_time', '>=', $today)
            ->first();
    }
}

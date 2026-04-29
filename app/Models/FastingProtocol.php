<?php

namespace App\Models;

use App\Enums\ProtocolType;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class FastingProtocol extends Model
{
    use HasUuids;

    protected $guarded = ['id'];

    protected $casts = [
        'type' => ProtocolType::class,
    ];

    public function days()
    {
        return $this->hasMany(FastingProtocolDay::class);
    }
}

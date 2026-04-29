<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FastingProtocolDay extends Model
{
    public $timestamps = false;

    protected $guarded = ['id'];

    public function protocol()
    {
        return $this->belongsTo(FastingProtocol::class);
    }
}

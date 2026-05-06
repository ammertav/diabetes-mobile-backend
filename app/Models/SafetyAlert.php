<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class SafetyAlert extends Model
{
    use HasUuids;
    protected $guarded = ['id'];
    protected $keyType = 'string';
    public $incrementing = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function fgbRecord()
    {
        return $this->belongsTo(FgbRecord::class);
    }
}

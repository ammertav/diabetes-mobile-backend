<?php

namespace App\Models;

use App\Enums\DiabetesStatus;
use App\Enums\Gender;
use Illuminate\Database\Eloquent\Model;

class MobileProfile extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'gender' => Gender::class,
        'diabetes_status' => DiabetesStatus::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

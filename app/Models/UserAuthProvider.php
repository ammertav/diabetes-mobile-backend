<?php

namespace App\Models;

use App\Enums\AuthProvider;
use Illuminate\Database\Eloquent\Model;

class UserAuthProvider extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'provider' => AuthProvider::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

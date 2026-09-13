<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class RefreshToken extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'is_revoked' => 'boolean',
    ];
}

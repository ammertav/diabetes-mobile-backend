<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Illuminate\Database\Eloquent\Builder
 * @method bool update(array $attributes = [], array $options = [])
 */
class UserNotificationSetting extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'niat_puasa_enabled' => 'boolean',
        'sahur_enabled' => 'boolean',
        'fbg_reminder_enabled' => 'boolean',
        'motivation_enabled' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

<?php

namespace App\Models;

use App\Enums\CmsContentType;
use App\Enums\CmsDayContext;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class CmsContent extends Model
{
    use HasUuids;

    protected $guarded = ['id'];
    protected $keyType = 'string';
    public $incrementing = false;

    protected $casts = [
        'content_type' => CmsContentType::class,
        'day_context' => CmsDayContext::class,
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];
}

<?php

namespace App\DTO\Content;

use App\Enums\CmsContentType;
use App\Enums\CmsDayContext;

class ContentFilterData
{
    public function __construct(
        public ?CmsContentType $contentType,
        public ?CmsDayContext $dayContext,
        public int $limit
    ) {}

    public static function fromRequest(array $validated): self
    {
        return new self(
            contentType: isset($validated['content_type']) ? CmsContentType::from($validated['content_type']) : null,
            dayContext: isset($validated['day_context']) ? CmsDayContext::from($validated['day_context']) : null,
            limit: (int) ($validated['limit'] ?? 20)
        );
    }
}

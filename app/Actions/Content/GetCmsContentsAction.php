<?php

namespace App\Actions\Content;

use App\DTO\Content\ContentFilterData;
use App\Models\CmsContent;
use Illuminate\Contracts\Pagination\CursorPaginator;

class GetCmsContentsAction
{
    public function execute(ContentFilterData $filterData): CursorPaginator
    {
        $query = CmsContent::query()
            ->where('is_published', true)
            ->when($filterData->contentType, function ($q, $type) {
                $q->where('content_type', $type->value);
            })
            ->when($filterData->dayContext, function ($q, $context) {
                $q->where('day_context', $context->value);
            })
            ->orderBy('published_at', 'desc')
            ->orderBy('id', 'desc');

        return $query->cursorPaginate($filterData->limit);
    }
}

<?php

namespace App\Http\Controllers\API\V1;

use App\Actions\Content\GetCmsContentsAction;
use App\DTO\Content\ContentFilterData;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Content\GetContentRequest;
use App\Http\Resources\CmsContentResource;

class ContentController extends Controller
{
    public function index(GetContentRequest $request, GetCmsContentsAction $action)
    {
        $filterData = ContentFilterData::fromRequest($request->validated());

        $contents = $action->execute($filterData);

        return CmsContentResource::collection($contents);
    }
}

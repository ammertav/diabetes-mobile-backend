<?php

namespace App\Http\Requests\API\V1\Content;

use App\Enums\CmsContentType;
use App\Enums\CmsDayContext;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class GetContentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'content_type' => ['nullable', new Enum(CmsContentType::class)],
            'day_context' => ['nullable', new Enum(CmsDayContext::class)],
            'limit' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }
}

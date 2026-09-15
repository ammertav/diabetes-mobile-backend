<?php

namespace App\Http\Requests;

use App\Enums\CmsMediaType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateCmsContentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    protected function prepareForValidation(): void
    {
        if (! $this->has('media_type') || empty($this->media_type)) {
            $this->merge(['media_type' => CmsMediaType::None->value]);
        }
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'max:100'],
            'day_context' => ['nullable', 'string', 'max:100'],
            'body' => ['required', 'string'],
            'is_published' => ['required', 'in:0,1'],
            'media_type' => ['required', new Enum(CmsMediaType::class)],
            'image_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
            'video_file' => ['nullable', 'mimes:mp4,webm,quicktime', 'max:51200'],
            'video_url' => ['nullable', 'required_if:media_type,youtube', 'url'],
            'thumbnail_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ];
    }
}

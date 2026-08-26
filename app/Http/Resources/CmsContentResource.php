<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CmsContentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'content_type' => $this->content_type->value,
            'day_context' => $this->day_context?->value,
            'title' => $this->title,
            'body' => $this->body,
        ];
    }
}

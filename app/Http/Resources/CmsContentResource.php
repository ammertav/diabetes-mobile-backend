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
        $mediaUrl = null;
        if ($this->media_url) {
            $mediaUrl = str_starts_with($this->media_url, 'http')
                ? $this->media_url
                : asset('storage/'.$this->media_url);
        }

        $thumbnailUrl = null;
        if ($this->thumbnail_url) {
            $thumbnailUrl = str_starts_with($this->thumbnail_url, 'http')
                ? $this->thumbnail_url
                : asset('storage/'.$this->thumbnail_url);
        }

        return [
            'id' => $this->id,
            'content_type' => $this->content_type->value,
            'day_context' => $this->day_context?->value,
            'title' => $this->title,
            'body' => $this->body,
            'media' => [
                'type' => $this->media_type?->value ?? 'image',
                'url' => $mediaUrl,
                'youtube_id' => $this->youtube_id,
                'thumbnail_url' => $thumbnailUrl,
            ],
        ];
    }
}

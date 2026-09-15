<?php

namespace App\Actions\Content;

use App\Enums\CmsMediaType;
use App\Models\CmsContent;
use Illuminate\Http\UploadedFile;

class CreateCmsContentAction
{
    public function execute(
        array $data,
        ?UploadedFile $imageFile = null,
        ?UploadedFile $videoFile = null,
        ?UploadedFile $thumbnailFile = null
    ): CmsContent {
        $mediaType = CmsMediaType::tryFrom($data['media_type'] ?? 'image') ?? CmsMediaType::Image;
        [$mediaUrl, $youtubeId, $thumbnailUrl] = $this->resolveMedia(
            $mediaType,
            $data['video_url'] ?? null,
            $imageFile,
            $videoFile,
            $thumbnailFile
        );

        return CmsContent::create([
            'content_type' => $data['type'],
            'day_context' => $data['day_context'] ?? null,
            'title' => $data['title'],
            'body' => $data['body'],
            'media_type' => $mediaType,
            'media_url' => $mediaUrl,
            'youtube_id' => $youtubeId,
            'thumbnail_url' => $thumbnailUrl,
            'is_published' => (bool) $data['is_published'],
            'published_at' => (bool) $data['is_published'] ? now() : null,
        ]);
    }

    private function resolveMedia(
        CmsMediaType $type,
        ?string $videoUrl,
        ?UploadedFile $image,
        ?UploadedFile $video,
        ?UploadedFile $thumb
    ): array {
        if ($type === CmsMediaType::Image && $image) {
            return [$image->store('cms/images', 'public'), null, null];
        }

        if ($type === CmsMediaType::Video && $video) {
            $thumbPath = $thumb ? $thumb->store('cms/thumbnails', 'public') : null;

            return [$video->store('cms/videos', 'public'), null, $thumbPath];
        }

        if ($type === CmsMediaType::Youtube && $videoUrl) {
            $ytId = CmsMediaType::parseYoutubeId($videoUrl);
            $thumbPath = $thumb ? $thumb->store('cms/thumbnails', 'public') : CmsMediaType::getYoutubeThumbnailUrl($ytId);

            return [$videoUrl, $ytId, $thumbPath];
        }

        return [null, null, null];
    }
}

<?php

namespace App\Actions\Content;

use App\Enums\CmsMediaType;
use App\Models\CmsContent;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UpdateCmsContentAction
{
    public function execute(
        CmsContent $content,
        array $data,
        ?UploadedFile $imageFile = null,
        ?UploadedFile $videoFile = null,
        ?UploadedFile $thumbnailFile = null
    ): CmsContent {
        $mediaType = CmsMediaType::tryFrom($data['media_type'] ?? 'image') ?? CmsMediaType::Image;
        $updates = $this->resolveMediaUpdates(
            $content,
            $mediaType,
            $data['video_url'] ?? null,
            $imageFile,
            $videoFile,
            $thumbnailFile
        );

        $isPublished = (bool) $data['is_published'];
        $publishedAt = $isPublished ? ($content->published_at ?? now()) : null;

        $content->update(array_merge($updates, [
            'content_type' => $data['type'],
            'day_context' => $data['day_context'] ?? null,
            'title' => $data['title'],
            'body' => $data['body'],
            'media_type' => $mediaType,
            'is_published' => $isPublished,
            'published_at' => $publishedAt,
        ]));

        return $content;
    }

    private function resolveMediaUpdates(
        CmsContent $content,
        CmsMediaType $type,
        ?string $videoUrl,
        ?UploadedFile $image,
        ?UploadedFile $video,
        ?UploadedFile $thumb
    ): array {
        if ($type === CmsMediaType::Image) {
            return $this->handleImageUpdate($content, $image);
        }

        if ($type === CmsMediaType::Video) {
            return $this->handleVideoUpdate($content, $video, $thumb);
        }

        if ($type === CmsMediaType::Youtube) {
            return $this->handleYoutubeUpdate($content, $videoUrl, $thumb);
        }

        $this->deleteLocalFile($content->media_url);
        $this->deleteLocalFile($content->thumbnail_url);

        return ['media_url' => null, 'youtube_id' => null, 'thumbnail_url' => null];
    }

    private function handleImageUpdate(CmsContent $content, ?UploadedFile $image): array
    {
        $url = $content->media_url;
        if ($image) {
            $this->deleteLocalFile($content->media_url);
            $url = $image->store('cms/images', 'public');
        }
        $this->deleteLocalFile($content->thumbnail_url);

        return ['media_url' => $url, 'youtube_id' => null, 'thumbnail_url' => null];
    }

    private function handleVideoUpdate(CmsContent $content, ?UploadedFile $video, ?UploadedFile $thumb): array
    {
        $mediaUrl = $content->media_url;
        if ($video) {
            $this->deleteLocalFile($content->media_url);
            $mediaUrl = $video->store('cms/videos', 'public');
        }

        $thumbUrl = $content->thumbnail_url;
        if ($thumb) {
            $this->deleteLocalFile($content->thumbnail_url);
            $thumbUrl = $thumb->store('cms/thumbnails', 'public');
        }

        return ['media_url' => $mediaUrl, 'youtube_id' => null, 'thumbnail_url' => $thumbUrl];
    }

    private function handleYoutubeUpdate(CmsContent $content, ?string $videoUrl, ?UploadedFile $thumb): array
    {
        $url = $videoUrl ?? $content->media_url;
        $ytId = CmsMediaType::parseYoutubeId($url);
        $thumbUrl = $content->thumbnail_url;

        if ($thumb) {
            $this->deleteLocalFile($content->thumbnail_url);
            $thumbUrl = $thumb->store('cms/thumbnails', 'public');
        } elseif (! $thumbUrl && $ytId) {
            $thumbUrl = CmsMediaType::getYoutubeThumbnailUrl($ytId);
        }

        return ['media_url' => $url, 'youtube_id' => $ytId, 'thumbnail_url' => $thumbUrl];
    }

    private function deleteLocalFile(?string $path): void
    {
        if ($path && ! str_starts_with($path, 'http') && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}

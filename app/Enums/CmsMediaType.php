<?php

namespace App\Enums;

enum CmsMediaType: string
{
    case None = 'none';
    case Image = 'image';
    case Video = 'video';
    case Youtube = 'youtube';

    public function label(): string
    {
        return match ($this) {
            self::None => 'Tanpa Media',
            self::Image => 'Gambar (Foto)',
            self::Video => 'Video Berkas (Maks 50MB)',
            self::Youtube => 'YouTube Video Link',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::None => 'block',
            self::Image => 'image',
            self::Video => 'videocam',
            self::Youtube => 'smart_display',
        };
    }

    public static function parseYoutubeId(?string $url): ?string
    {
        if (! $url) {
            return null;
        }

        preg_match('/(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=|shorts\/))([\w-]{11})/', $url, $matches);

        return $matches[1] ?? null;
    }

    public static function getYoutubeThumbnailUrl(?string $youtubeId): ?string
    {
        if (! $youtubeId) {
            return null;
        }

        return "https://img.youtube.com/vi/{$youtubeId}/hqdefault.jpg";
    }
}

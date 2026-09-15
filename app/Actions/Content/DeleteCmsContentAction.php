<?php

namespace App\Actions\Content;

use App\Models\CmsContent;
use Illuminate\Support\Facades\Storage;

class DeleteCmsContentAction
{
    public function execute(CmsContent $content): void
    {
        $this->deleteLocalFile($content->media_url);
        $this->deleteLocalFile($content->thumbnail_url);

        $content->delete();
    }

    private function deleteLocalFile(?string $path): void
    {
        if ($path && ! str_starts_with($path, 'http') && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}

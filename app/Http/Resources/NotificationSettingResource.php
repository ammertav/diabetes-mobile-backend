<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class NotificationSettingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'niat_puasa_enabled' => (bool) $this->niat_puasa_enabled,
            'niat_puasa_time' => $this->formatTime($this->niat_puasa_time),
            'sahur_enabled' => (bool) $this->sahur_enabled,
            'sahur_time' => $this->formatTime($this->sahur_time),
            'fbg_reminder_enabled' => (bool) $this->fbg_reminder_enabled,
            'fbg_reminder_time' => $this->formatTime($this->fbg_reminder_time),
            'motivation_enabled' => (bool) $this->motivation_enabled,
        ];
    }

    private function formatTime(?string $time): ?string
    {
        if (!$time) {
            return null;
        }
        return Carbon::parse($time)->format('H:i');
    }
}

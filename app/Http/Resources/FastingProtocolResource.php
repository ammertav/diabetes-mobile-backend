<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FastingProtocolResource extends JsonResource
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
            'name' => $this->name,
            'type' => $this->type->value,
            'start_time' => $this->start_time ?? '18:00',
            'end_time' => $this->end_time ?? '10:00',
            'fasting_days' => $this->days->pluck('day')->values(),
            'duration_hours' => $this->duration_hours,
            'description' => $this->description,
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StreakResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'current_streak' => $this['current_streak'],
            'best_streak' => $this['best_streak'],
            'total_fasting_days' => $this['total_fasting_days'],
            'last_fasting_date' => $this['last_fasting_date'] 
                ? \Illuminate\Support\Carbon::parse($this['last_fasting_date'])->toDateString() 
                : null,
        ];
    }
}

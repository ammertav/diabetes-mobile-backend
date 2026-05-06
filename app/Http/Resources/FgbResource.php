<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FgbResource extends JsonResource
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
            'value_mg_dl' => $this->value_mg_dl,
            'context_tag' => $this->context_tag,
            'is_fasting_day' => $this->is_fasting_day,
            'server_timestamp' => $this->server_timestamp,
            'alert' => new \App\Http\Resources\FgbAlert($this->alert)
        ];
    }
}

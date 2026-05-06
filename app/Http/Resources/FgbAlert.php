<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FgbAlert extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $triggered = !empty($this->type);

        return [
            "triggered" => $triggered,
            "type" => $this->type,
            "message" => $this->message,
            "action" => $this->action
        ];
    }
}

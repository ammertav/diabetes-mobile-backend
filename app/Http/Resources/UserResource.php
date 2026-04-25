<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'email' => $this->email,
            'name' => $this->name,
            'age' => $this->age,
            'gender' => $this->gender,
            'diabetes_status' => $this->diabetes_status,
            'bmi' => $this->bmi,
            'auth_provider' => $this->authProviders->map->provider,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

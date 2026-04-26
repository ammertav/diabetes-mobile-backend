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
        $data = [
            'id' => $this->id,
            'email' => $this->email,
            'auth_provider' => $this->authProviders->map->provider,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];

        if ($this->type === \App\Enums\UserType::MOBILE && $this->mobileProfile) {
            $data = array_merge($data, [
                'name' => $this->mobileProfile->name,
                'age' => $this->mobileProfile->age,
                'gender' => $this->mobileProfile->gender,
                'diabetes_status' => $this->mobileProfile->diabetes_status,
                'bmi' => $this->mobileProfile->bmi,
            ]);
        }

        return $data;
    }
}

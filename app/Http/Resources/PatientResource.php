<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class PatientResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->mobileProfile?->name ?? 'Patient',
            'email' => $this->email,
            'photo' => 'https://ui-avatars.com/api/?name=' . urlencode($this->mobileProfile?->name ?? 'Patient') . '&background=random&color=fff&size=128',
            'patient_code' => 'DB-' . strtoupper(substr($this->id, 0, 6)),
            'diabetes_type' => $this->mobileProfile?->diabetes_status ?? 'healthy',
            'protocol' => $this->activeProtocol?->protocol?->name,
            'risk_status' => $this->risk_status,
            'last_checkin' => $this->latestCheckin
                ? Carbon::parse($this->latestCheckin->server_timestamp)->diffForHumans()
                : 'Belum check-in',
        ];
    }
}

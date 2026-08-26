<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FastingLogWebResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $user = $this->userProtocol?->user;
        $profile = $user?->mobileProfile;
        $protocol = $this->userProtocol?->protocol;

        $patientName = $profile?->name ?? 'Patient';

        return [
            'id' => $this->id,
            'planned_date' => $this->planned_date?->format('Y-m-d'),
            'planned_date_formatted' => $this->planned_date?->format('d M Y'),
            'patient_id' => $user?->id,
            'patient_name' => $patientName,
            'patient_email' => $user?->email ?? '',
            'patient_photo' => 'https://ui-avatars.com/api/?name=' . urlencode($patientName) . '&background=0284c7&color=fff&size=128',
            'protocol_id' => $protocol?->id,
            'protocol_name' => $protocol?->name ?? 'Protocol Puasa',
            'protocol_type' => is_object($protocol?->type) ? $protocol->type->value : ($protocol?->type ?? 'custom'),
            'target_duration_hours' => $protocol?->duration_hours ?? 16,
            'actual_duration_min' => $this->actual_duration_min ?? 0,
            'actual_duration_hours' => $this->actual_duration_min ? round($this->actual_duration_min / 60, 1) : 0,
            'status' => is_object($this->status) ? $this->status->value : ($this->status ?? 'planned'),
            'mood' => is_object($this->mood) ? $this->mood->value : $this->mood,
            'started_at' => $this->started_at ? $this->started_at->format('H:i, d M Y') : '-',
            'ended_at' => $this->ended_at ? $this->ended_at->format('H:i, d M Y') : '-',
            'confirmed_at' => $this->confirmed_at ? $this->confirmed_at->format('H:i, d M Y') : '-',
            'skip_reason' => $this->skip_reason ?? '-',
            'notes' => $this->notes ?? '-',
        ];
    }
}

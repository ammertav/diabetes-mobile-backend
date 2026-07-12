<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class FgbLogResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $value = $this->value_mg_dl;
        
        // Determine status and style
        if ($value < 70) {
            $status = 'Low';
            $badgeClass = 'bg-blue-100 text-blue-700 dark:bg-blue-950/40 dark:text-blue-300';
        } elseif ($value >= 70 && $value < 100) {
            $status = 'Normal';
            $badgeClass = 'bg-secondary-container text-on-secondary-container';
        } elseif ($value >= 100 && $value <= 140) {
            $status = 'Elevated';
            $badgeClass = 'bg-orange-100 text-orange-700 dark:bg-orange-950/40 dark:text-orange-300';
        } else {
            $status = 'High Reading';
            $badgeClass = 'bg-tertiary-container text-on-tertiary-container';
        }

        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'patient_id' => 'PX-' . strtoupper(substr($this->user_id, 0, 4)),
            'patient_name' => $this->user?->mobileProfile?->name ?? 'Patient',
            'patient_photo' => 'https://ui-avatars.com/api/?name=' . urlencode($this->user?->mobileProfile?->name ?? 'Patient') . '&background=random&color=fff&size=128',
            'value_mg_dl' => $value,
            'status' => $status,
            'badge_class' => $badgeClass,
            'timestamp' => Carbon::parse($this->server_timestamp)->format('M d, h:i A'),
        ];
    }
}

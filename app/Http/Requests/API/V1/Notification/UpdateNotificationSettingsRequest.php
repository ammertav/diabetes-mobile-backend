<?php

namespace App\Http\Requests\API\V1\Notification;

use Illuminate\Foundation\Http\FormRequest;

class UpdateNotificationSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'niat_puasa_enabled' => ['nullable', 'boolean'],
            'niat_puasa_time' => ['nullable', 'date_format:H:i'],
            'sahur_enabled' => ['nullable', 'boolean'],
            'sahur_time' => ['nullable', 'date_format:H:i'],
            'fbg_reminder_enabled' => ['nullable', 'boolean'],
            'fbg_reminder_time' => ['nullable', 'date_format:H:i'],
            'motivation_enabled' => ['nullable', 'boolean'],
        ];
    }
}

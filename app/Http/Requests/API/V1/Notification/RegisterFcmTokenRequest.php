<?php

namespace App\Http\Requests\API\V1\Notification;

use Illuminate\Foundation\Http\FormRequest;

class RegisterFcmTokenRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'fcm_token' => ['required', 'string'],
            'platform' => ['nullable', 'string', 'max:50'],
        ];
    }
}

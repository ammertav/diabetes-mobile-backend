<?php

namespace App\Http\Requests\API\V1\Notification;

use Illuminate\Foundation\Http\FormRequest;

class GetNotificationHistoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => ['nullable', 'string', 'in:niat,sahur,fbg_reminder,safety,motivation'],
            'read' => ['nullable', 'boolean'],
            'limit' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->has('read')) {
            $this->merge([
                'read' => filter_var($this->input('read'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE),
            ]);
        }
    }
}

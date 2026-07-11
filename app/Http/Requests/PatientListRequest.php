<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PatientListRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'max:255'],
            'risk' => ['nullable', 'string', 'in:all,high,medium,low'],
            'protocol' => ['nullable', 'string'],
            'date' => ['nullable', 'date'],
            'page' => ['nullable', 'integer', 'min:1'],
        ];
    }
}

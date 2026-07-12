<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FgbLogListRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'string', 'in:all,normal,elevated,high,low'],
            'page' => ['nullable', 'integer', 'min:1'],
        ];
    }
}

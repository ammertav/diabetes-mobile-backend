<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'min:2',
                'max:100',
            ],
            'age' => [
                'required',
                'integer',
                'between:18,120',
            ],
            'bmi' => [
                'nullable',
                'numeric',
                'between:10,70',
            ],
            'diabetes_status' => [
                'required',
                new Enum(\App\Enums\DiabetesStatus::class),
            ],
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateAlertSettingRequest extends FormRequest
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
            'hypo_severe' => 'nullable|integer|min:30|max:80',
            'hypo_mild' => 'nullable|integer|min:50|max:100|gt:hypo_severe|lt:hyper_mild',
            'hyper_mild' => 'nullable|integer|min:140|max:300|gt:hypo_mild|lt:hyper_severe',
            'hyper_severe' => 'nullable|integer|min:200|max:500|gt:hyper_mild',
        ];
    }

    protected function prepareForValidation(): void
    {
        $setting = $this->user()->alertSetting;

        $this->merge([
            'hypo_severe' => $this->hypo_severe ?? $setting?->hypo_severe,
            'hypo_mild' => $this->hypo_mild ?? $setting?->hypo_mild,
            'hyper_mild' => $this->hyper_mild ?? $setting?->hyper_mild,
            'hyper_severe' => $this->hyper_severe ?? $setting?->hyper_severe,
        ]);
    }
}

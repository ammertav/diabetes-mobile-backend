<?php

namespace App\Http\Requests;

use App\Enums\FgbContextTag;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class FgbStoreRequest extends FormRequest
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
            'value_mg_dl' => ['required', 'numeric', 'between:40,600'],
            'context_tag' => ['required', new Enum(FgbContextTag::class)],
            'fasting_log_id' => ['nullable', 'uuid', 'exists:fasting_logs,id'],
            'client_timestamp' => ['required', 'date'],
        ];
    }
}

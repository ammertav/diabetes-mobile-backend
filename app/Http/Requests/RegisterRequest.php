<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email',
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/^(?=.*[A-Za-z])(?=.*\d).+$/', // huruf + angka
            ],
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
            'gender' => [
                'required',
                Rule::in(['male', 'female']),
            ],
            'diabetes_status' => [
                'required',
                Rule::in(['t2dm', 'prediabetes', 'healthy']),
            ],
            'bmi' => [
                'nullable',
                'numeric',
                'between:10,70',
            ],
            'disclaimer_accepted' => [
                'required',
                'boolean',
                'accepted', // harus true
            ],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();

        $statusCode = 422;

        // cek apakah email kena unique validation
        if ($errors->has('email')) {
            $statusCode = 409;
        }

        throw new HttpResponseException(
            response()->json([
                'message' => $errors->first(),
                'errors' => $errors,
            ], $statusCode)
        );
    }
}

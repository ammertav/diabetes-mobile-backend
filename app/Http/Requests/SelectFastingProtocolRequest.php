<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SelectFastingProtocolRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'protocol_id' => [
                'required',
                'uuid',
                'exists:fasting_protocols,id',
            ],
            'start_date' => [
                'required',
                'date_format:Y-m-d',
                'after_or_equal:today',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'protocol_id.required' => 'ID protokol wajib diisi.',
            'protocol_id.uuid' => 'Format ID protokol harus berupa UUID yang valid.',
            'protocol_id.exists' => 'Protokol puasa yang dipilih tidak ditemukan.',
            'start_date.required' => 'Tanggal mulai wajib diisi.',
            'start_date.date_format' => 'Format tanggal mulai harus YYYY-MM-DD.',
            'start_date.after_or_equal' => 'Tanggal mulai puasa tidak boleh tanggal yang sudah lewat.',
        ];
    }
}

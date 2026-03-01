<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWisataKuotaRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'kuota_total' => 'nullable|integer|min:0|max:10000',
            'status' => 'nullable|boolean',
        ];
    }

    /**
     * Get custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'kuota_total.integer' => 'Kuota harus berupa angka',
            'kuota_total.min' => 'Kuota minimal 0',
            'kuota_total.max' => 'Kuota maksimal 10.000',
            'status.boolean' => 'Status harus berupa boolean (0 atau 1)',
        ];
    }
}

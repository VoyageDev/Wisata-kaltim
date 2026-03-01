<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreWisataKuotaRequest extends FormRequest
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
            'wisata_id' => 'required|exists:wisatas,id',
            'tanggal' => 'required|date|after_or_equal:today',
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
            'wisata_id.required' => 'Pilih wisata terlebih dahulu',
            'wisata_id.exists' => 'Wisata tidak ditemukan',
            'tanggal.required' => 'Tanggal harus diisi',
            'tanggal.date' => 'Format tanggal tidak valid',
            'tanggal.after_or_equal' => 'Tanggal tidak boleh kurang dari hari ini',
            'kuota_total.integer' => 'Kuota harus berupa angka',
            'kuota_total.min' => 'Kuota minimal 0',
            'kuota_total.max' => 'Kuota maksimal 10.000',
            'status.boolean' => 'Status harus berupa boolean (0 atau 1)',
        ];
    }
}

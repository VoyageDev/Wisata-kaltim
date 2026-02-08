<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreBookingsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
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
            'paket_wisata_id' => 'nullable|exists:paket_wisatas,id',
            'jumlah_orang' => 'nullable|numeric|min:1',
            'tanggal_kunjungan' => 'required|date|after:today',
        ];
    }
}

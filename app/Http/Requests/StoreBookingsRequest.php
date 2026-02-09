<?php

namespace App\Http\Requests;

use App\Models\PaketWisata;
use App\Models\WisataKuota;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Validator;

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

    /**
     * Get custom messages for validation errors
     */
    public function messages(): array
    {
        return [
            'wisata_id.required' => 'Pilih wisata yang ingin dikunjungi',
            'wisata_id.exists' => 'Wisata tidak ditemukan',
            'paket_wisata_id.exists' => 'Paket wisata tidak ditemukan',
            'jumlah_orang.numeric' => 'Jumlah orang harus berupa angka',
            'jumlah_orang.min' => 'Jumlah orang minimal 1',
            'tanggal_kunjungan.required' => 'Tanggal kunjungan harus diisi',
            'tanggal_kunjungan.date' => 'Format tanggal tidak valid',
            'tanggal_kunjungan.after' => 'Tanggal kunjungan tidak boleh kurang dari hari ini',
        ];
    }

    /**
     * Configure the validator instance
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            // Determine number of people
            if ($this->paket_wisata_id) {
                $paket = PaketWisata::find($this->paket_wisata_id);
                $jumlahOrang = $paket?->jumlah_orang ?? 1;
            } else {
                $jumlahOrang = (int) ($this->jumlah_orang ?? 1);
            }

            // Check if tickets are available for the selected date
            $kuota = WisataKuota::where('wisata_id', $this->wisata_id)
                ->where('tanggal', $this->tanggal_kunjungan)
                ->first();

            if (! $kuota) {
                $validator->errors()->add(
                    'tanggal_kunjungan',
                    'Maaf, tiket untuk tanggal ini belum tersedia. Silakan pilih tanggal lain.'
                );
            } else {
                $sisaTiket = $kuota->kuota_total - $kuota->kuota_terpakai;

                if ($sisaTiket <= 0) {
                    $validator->errors()->add(
                        'tanggal_kunjungan',
                        'Maaf, tiket untuk tanggal ini sudah terjual habis. Silakan pilih tanggal lain.'
                    );
                } elseif ($sisaTiket < $jumlahOrang) {
                    $validator->errors()->add(
                        'tanggal_kunjungan',
                        "Maaf, hanya tersisa {$sisaTiket} tiket untuk tanggal ini, sementara Anda membutuhkan {$jumlahOrang} tiket. Silakan pilih tanggal lain atau kurangi jumlah orang."
                    );
                }
            }
        });
    }
}

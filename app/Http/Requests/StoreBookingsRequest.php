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
     * Validate ketersediaan tiket dengan logika yang sama seperti API checkAvailability()
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

            // Get wisata dan kuota (sama seperti API logic)
            $wisata = \App\Models\Wisata::find($this->wisata_id);
            if (! $wisata) {
                return; // Wisata sudah divalidasi di rules()
            }

            $kuota = WisataKuota::where('wisata_id', $this->wisata_id)
                ->where('tanggal', $this->tanggal_kunjungan)
                ->first();

            // Cek status (jika ada override dan statusnya tutup)
            if ($kuota && ! $kuota->status) {
                $validator->errors()->add(
                    'tanggal_kunjungan',
                    'Wisata tutup untuk tanggal ini'
                );

                return;
            }

            // Tentukan kuota total (override atau default)
            $kuotaTotal = $kuota && $kuota->kuota_total !== null
                ? $kuota->kuota_total
                : $wisata->kuota_default;

            // Hitung kuota terpakai
            $kuotaTerpakai = $kuota ? $kuota->kuota_terpakai : 0;

            // Hitung sisa tiket
            $sisaTiket = $kuotaTotal - $kuotaTerpakai;

            // Cek apakah tersedia untuk jumlah orang yang diminta
            if ($sisaTiket <= 0) {
                $validator->errors()->add(
                    'tanggal_kunjungan',
                    'Tiket untuk tanggal ini sudah terjual habis. Silakan pilih tanggal lain.'
                );
            } elseif ($sisaTiket < $jumlahOrang) {
                $validator->errors()->add(
                    'tanggal_kunjungan',
                    "Hanya tersisa {$sisaTiket} tiket untuk tanggal ini, sementara Anda membutuhkan {$jumlahOrang} tiket. Silakan pilih tanggal lain atau kurangi jumlah orang."
                );
            }
        });
    }
}

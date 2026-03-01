@extends('layouts.admin')
@section('header')
    <div class="flex items-center gap-4">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Kelola Kuota Tiket') }}
        </h2>
    </div>
@endsection

@section('content')
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            {{-- Alert Error --}}
            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-circle text-red-600 dark:text-red-400 mr-3 mt-1"></i>
                        <div>
                            <p class="text-red-800 dark:text-red-300 font-medium mb-2">Terjadi kesalahan:</p>
                            <ul class="list-disc list-inside text-red-700 dark:text-red-400 text-sm space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            {{-- back button --}}
            <div class="mb-6">
                <a href="{{ route('admin.wisata.tiket') }}"
                    class="text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 inline-flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>

            {{-- Form Card --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden">
                <div class="p-8">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6 flex items-center">
                        <i class="fas fa-ticket text-blue-600 mr-3"></i>
                        {{ isset($wisataKuota) ? 'Edit' : 'Tambah' }} Kuota Tiket Wisata
                    </h3>

                    <form
                        action="{{ isset($wisataKuota) ? route('admin.tiket.update', $wisataKuota) : route('admin.tiket.store') }}"
                        method="POST">
                        @csrf
                        @if (isset($wisataKuota))
                            @method('PUT')
                        @endif

                        {{-- Pilih Wisata --}}
                        <div class="mb-6">
                            <label for="wisata_id"
                                class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-map-marked-alt text-gray-500 mr-2"></i>Pilih Wisata <span
                                    class="text-red-500">*</span>
                            </label>
                            <select name="wisata_id" id="wisata_id"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-100 @error('wisata_id') border-red-500 @enderror"
                                required {{ isset($wisataKuota) ? 'disabled' : '' }}>
                                <option value="">-- Pilih Wisata --</option>
                                @foreach ($wisatas as $wisata)
                                    <option value="{{ $wisata->id }}"
                                        {{ (old('wisata_id') ?? ($wisataKuota->wisata_id ?? '')) == $wisata->id ? 'selected' : '' }}>
                                        {{ $wisata->name }} (Default: {{ $wisata->kuota_default }} tiket)
                                        @if ($wisata->kota)
                                            - {{ $wisata->kota->name }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @if (isset($wisataKuota))
                                <input type="hidden" name="wisata_id" value="{{ $wisataKuota->wisata_id }}">
                            @endif
                            @error('wisata_id')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                            <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                <i class="fas fa-info-circle mr-1"></i>Admin dapat override kuota atau menutup tanggal
                                tertentu
                            </p>
                        </div>

                        {{-- Tanggal --}}
                        <div class="mb-6">
                            <label for="tanggal" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-calendar text-gray-500 mr-2"></i>Tanggal <span
                                    class="text-red-500">*</span>
                            </label>
                            <input type="date" name="tanggal" id="tanggal"
                                value="{{ old('tanggal', isset($wisataKuota) ? $wisataKuota->tanggal->format('Y-m-d') : now()->addDays(1)->format('Y-m-d')) }}"
                                min="{{ now()->format('Y-m-d') }}"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-100 @error('tanggal') border-red-500 @enderror"
                                required {{ isset($wisataKuota) ? 'disabled' : '' }}>
                            @if (isset($wisataKuota))
                                <input type="hidden" name="tanggal" value="{{ $wisataKuota->tanggal->format('Y-m-d') }}">
                            @endif
                            @error('tanggal')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                            <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                <i class="fas fa-info-circle mr-1"></i>Pilih tanggal untuk mengatur kuota spesial atau
                                membuka/menutup
                            </p>
                        </div>

                        {{-- Kuota Total (Optional/Override) --}}
                        <div class="mb-6">
                            <label for="kuota_total"
                                class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-users text-gray-500 mr-2"></i>Kuota Tiket (Override)
                            </label>
                            <input type="number" name="kuota_total" id="kuota_total"
                                value="{{ old('kuota_total', isset($wisataKuota) && $wisataKuota->kuota_total ? $wisataKuota->kuota_total : '') }}"
                                min="0" max="10000" placeholder="Kosongkan untuk menggunakan kuota default"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-100 @error('kuota_total') border-red-500 @enderror">
                            @error('kuota_total')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                            <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                <i class="fas fa-info-circle mr-1"></i>Opsional. Jika kosong, akan menggunakan kuota default
                                wisata. Isi jika ingin override.
                            </p>
                        </div>

                        {{-- Status --}}
                        <div class="mb-8">
                            <label for="status" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                                <i class="fas fa-toggle-on text-gray-500 mr-2"></i>Status Tanggal
                            </label>
                            <div class="space-y-3">
                                <label class="flex items-center cursor-pointer">
                                    <input type="radio" name="status" value="1"
                                        {{ old('status', isset($wisataKuota) ? $wisataKuota->status : 1) == 1 ? 'checked' : '' }}
                                        class="w-4 h-4 text-green-600">
                                    <span class="ml-3 text-gray-700 dark:text-gray-300 font-medium">
                                        <i class="fas fa-check-circle text-green-600 mr-2"></i>Buka - Wisata tersedia untuk
                                        pemesanan
                                    </span>
                                </label>
                                <label class="flex items-center cursor-pointer">
                                    <input type="radio" name="status" value="0"
                                        {{ old('status', isset($wisataKuota) ? $wisataKuota->status : 1) == 0 ? 'checked' : '' }}
                                        class="w-4 h-4 text-red-600">
                                    <span class="ml-3 text-gray-700 dark:text-gray-300 font-medium">
                                        <i class="fas fa-times-circle text-red-600 mr-2"></i>Tutup - Wisata tidak tersedia
                                        (maintenance, dll)
                                    </span>
                                </label>
                            </div>
                            <p class="mt-3 text-xs text-gray-500 dark:text-gray-400">
                                <i class="fas fa-info-circle mr-1"></i>Pilih status untuk membuka atau menutup tanggal
                                tertentu
                            </p>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <a href="{{ route('admin.wisata.tiket') }}"
                                class="px-6 py-3 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg font-semibold hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                                <i class="fas fa-times mr-2"></i>Batal
                            </a>
                            <button type="submit"
                                class="px-6 py-3 bg-gradient-to-r from-[#074e0e] to-[#167509] text-white rounded-lg font-semibold hover:shadow-lg transition-all duration-300">
                                <i class="fas fa-save mr-2"></i>{{ isset($wisataKuota) ? 'Update' : 'Simpan' }} Kuota
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Info Card --}}
            <div class="mt-6 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-6">
                <h4 class="text-lg font-semibold text-blue-900 dark:text-blue-300 mb-3 flex items-center">
                    <i class="fas fa-lightbulb mr-2"></i>Cara Kerja Kuota Tiket Baru
                </h4>
                <ul class="space-y-2 text-sm text-blue-800 dark:text-blue-400">
                    <li class="flex items-start">
                        <i class="fas fa-check-circle mr-2 mt-0.5"></i>
                        <span><strong>Kuota Default:</strong> Setiap wisata memiliki kuota default yang otomatis berlaku
                            untuk semua tanggal</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle mr-2 mt-0.5"></i>
                        <span><strong>Override Kuota:</strong> Isi kolom "Kuota Tiket" jika ingin mengubah kuota untuk
                            tanggal tertentu (misal: weekend)</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle mr-2 mt-0.5"></i>
                        <span><strong>Tutup Tanggal:</strong> Pilih "Tutup" jika ada masalah serius dan wisata tidak bisa
                            dibuka hari itu</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle mr-2 mt-0.5"></i>
                        <span><strong>Hapus Override:</strong> Hapus data untuk mengembalikan ke kuota default</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
@endsection

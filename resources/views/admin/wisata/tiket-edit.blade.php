@extends('layouts.admin')
@section('header')
    <div class="flex items-center gap-4">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Kuota Tiket') }}
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
                        Edit Kuota Tiket Wisata
                    </h3>

                    <form action="{{ route('admin.tiket.update', $wisataKuota) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Informasi Wisata --}}
                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-map-marked-alt text-gray-500 mr-2"></i>Wisata
                            </label>
                            <div
                                class="px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700">
                                <p class="text-gray-900 dark:text-gray-100 font-medium">{{ $wisataKuota->wisata->name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Kuota Default:
                                    {{ $wisataKuota->wisata->kuota_default }} tiket</p>
                            </div>
                        </div>

                        {{-- Informasi Tanggal --}}
                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-calendar text-gray-500 mr-2"></i>Tanggal
                            </label>
                            <div
                                class="px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700">
                                <p class="text-gray-900 dark:text-gray-100 font-medium">
                                    {{ \Carbon\Carbon::parse($wisataKuota->tanggal)->format('d M Y - l') }}
                                </p>
                            </div>
                        </div>

                        {{-- Kuota Total (Override) --}}
                        <div class="mb-6">
                            <label for="kuota_total"
                                class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-users text-gray-500 mr-2"></i>Kuota Tiket (Override)
                            </label>
                            <input type="number" name="kuota_total" id="kuota_total"
                                value="{{ old('kuota_total', $wisataKuota->kuota_total) }}" min="0" max="10000"
                                placeholder="Kosongkan untuk menggunakan kuota default"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-100 @error('kuota_total') border-red-500 @enderror">
                            @error('kuota_total')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                            <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                <i class="fas fa-info-circle mr-1"></i>Opsional. Jika kosong, akan menggunakan kuota default
                                ({{ $wisataKuota->wisata->kuota_default }} tiket).
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
                                        {{ old('status', $wisataKuota->status) == 1 ? 'checked' : '' }}
                                        class="w-4 h-4 text-green-600">
                                    <span class="ml-3 text-gray-700 dark:text-gray-300 font-medium">
                                        <i class="fas fa-check-circle text-green-600 mr-2"></i>Buka - Wisata tersedia untuk
                                        pemesanan
                                    </span>
                                </label>
                                <label class="flex items-center cursor-pointer">
                                    <input type="radio" name="status" value="0"
                                        {{ old('status', $wisataKuota->status) == 0 ? 'checked' : '' }}
                                        class="w-4 h-4 text-red-600">
                                    <span class="ml-3 text-gray-700 dark:text-gray-300 font-medium">
                                        <i class="fas fa-times-circle text-red-600 mr-2"></i>Tutup - Wisata tidak tersedia
                                        (maintenance, dll)
                                    </span>
                                </label>
                            </div>
                            <p class="mt-3 text-xs text-gray-500 dark:text-gray-400">
                                <i class="fas fa-info-circle mr-1"></i>Pilih status untuk membuka atau menutup tanggal ini
                            </p>
                        </div>

                        {{-- Informasi Kuota Terpakai --}}
                        <div
                            class="mb-8 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                            <p class="text-sm text-blue-800 dark:text-blue-300 font-semibold mb-2">Kuota Terpakai</p>
                            <p class="text-2xl font-bold text-blue-900 dark:text-blue-200">
                                {{ $wisataKuota->kuota_terpakai }}
                                /
                                <span class="text-lg">
                                    {{ $wisataKuota->kuota_total ?? $wisataKuota->wisata->kuota_default }} tiket
                                </span>
                            </p>
                            <p class="text-xs text-blue-700 dark:text-blue-400 mt-2">
                                Sisa:
                                <strong>{{ ($wisataKuota->kuota_total ?? $wisataKuota->wisata->kuota_default) - $wisataKuota->kuota_terpakai }}
                                    tiket</strong>
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
                                <i class="fas fa-save mr-2"></i>Update Kuota
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Danger Zone --}}
            <div class="mt-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-6">
                <h4 class="text-lg font-semibold text-red-900 dark:text-red-300 mb-3 flex items-center">
                    <i class="fas fa-exclamation-triangle mr-2"></i>Zona Bahaya
                </h4>
                <p class="text-sm text-red-800 dark:text-red-400 mb-4">
                    Hapus override ini untuk mengembalikan ke kuota default otomatis.
                </p>
                <form action="{{ route('admin.tiket.destroy', $wisataKuota) }}" method="POST"
                    onsubmit="return confirm('Yakin ingin menghapus override ini? Tanggal ini akan menggunakan kuota default.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition">
                        <i class="fas fa-trash mr-2"></i>Hapus Override
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection

<x-layouts.admin>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Tambah Kuota Tiket') }}
            </h2>
        </div>
    </x-slot>

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
                        Tambah Kuota Tiket Wisata
                    </h3>

                    <form action="{{ route('admin.tiket.store') }}" method="POST">
                        @csrf

                        {{-- Pilih Wisata --}}
                        <div class="mb-6">
                            <label for="wisata_id"
                                class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-map-marked-alt text-gray-500 mr-2"></i>Pilih Wisata <span
                                    class="text-red-500">*</span>
                            </label>
                            <select name="wisata_id" id="wisata_id"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-100 @error('wisata_id') border-red-500 @enderror"
                                required>
                                <option value="">-- Pilih Wisata --</option>
                                @foreach ($wisatas as $wisata)
                                    <option value="{{ $wisata->id }}"
                                        {{ old('wisata_id') == $wisata->id ? 'selected' : '' }}>
                                        {{ $wisata->name }} @if ($wisata->kota)
                                            - {{ $wisata->kota->name }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('wisata_id')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                            <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                <i class="fas fa-info-circle mr-1"></i>Pilih destinasi wisata yang ingin dikelola
                                kuotanya
                            </p>
                        </div>

                        {{-- Tanggal --}}
                        <div class="mb-6">
                            <label for="tanggal"
                                class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-calendar text-gray-500 mr-2"></i>Tanggal <span
                                    class="text-red-500">*</span>
                            </label>
                            <input type="date" name="tanggal" id="tanggal"
                                value="{{ old('tanggal', now()->addDays(1)->format('Y-m-d')) }}"
                                min="{{ now()->format('Y-m-d') }}"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-100 @error('tanggal') border-red-500 @enderror"
                                required>
                            @error('tanggal')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                            <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                <i class="fas fa-info-circle mr-1"></i>Pilih tanggal untuk kuota tiket (minimal hari
                                ini)
                            </p>
                        </div>

                        {{-- Kuota Total --}}
                        <div class="mb-8">
                            <label for="kuota_total"
                                class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-users text-gray-500 mr-2"></i>Kuota Total <span
                                    class="text-red-500">*</span>
                            </label>
                            <input type="number" name="kuota_total" id="kuota_total"
                                value="{{ old('kuota_total', 100) }}" min="1" max="10000"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-100 @error('kuota_total') border-red-500 @enderror"
                                required>
                            @error('kuota_total')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                            <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                <i class="fas fa-info-circle mr-1"></i>Masukkan jumlah kuota tiket yang tersedia (1 -
                                10.000)
                            </p>
                        </div>

                        {{-- Action Buttons --}}
                        <div
                            class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <a href="{{ route('admin.wisata.tiket') }}"
                                class="px-6 py-3 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg font-semibold hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                                <i class="fas fa-times mr-2"></i>Batal
                            </a>
                            <button type="submit"
                                class="px-6 py-3 bg-gradient-to-r from-[#074e0e] to-[#167509] text-white rounded-lg font-semibold hover:shadow-lg transition-all duration-300">
                                <i class="fas fa-save mr-2"></i>Simpan Kuota
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Info Card --}}
            <div class="mt-6 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-6">
                <h4 class="text-lg font-semibold text-blue-900 dark:text-blue-300 mb-3 flex items-center">
                    <i class="fas fa-lightbulb mr-2"></i>Tips Pengelolaan Kuota
                </h4>
                <ul class="space-y-2 text-sm text-blue-800 dark:text-blue-400">
                    <li class="flex items-start">
                        <i class="fas fa-check-circle mr-2 mt-0.5"></i>
                        <span>Atur kuota sesuai kapasitas maksimal wisata untuk memberikan pengalaman terbaik</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle mr-2 mt-0.5"></i>
                        <span>Untuk weekend atau hari libur, pertimbangkan untuk menambah kuota lebih banyak</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle mr-2 mt-0.5"></i>
                        <span>Kuota yang sama untuk wisata dan tanggal yang sama tidak bisa ditambahkan dua kali</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle mr-2 mt-0.5"></i>
                        <span>Kuota terpakai akan otomatis bertambah saat ada pemesanan baru</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</x-layouts.admin>

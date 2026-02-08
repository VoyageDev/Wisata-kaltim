<x-layouts.admin>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tambah Wisata') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Header --}}
            <div class="mb-8">
                <a href="{{ route('admin.wisata.index') }}"
                    class="text-[#8B6F47] dark:text-[#D4AF37] hover:text-[#D4AF37] dark:hover:text-[#8B6F47] font-medium flex items-center mb-4">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Tambah Wisata</h1>
            </div>

            {{-- Form --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 md:p-8">
                <form action="{{ route('admin.wisata.store') }}" method="POST" enctype="multipart/form-data"
                    class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Nama Wisata --}}
                        <div class="md:col-span-2">
                            <label for="name"
                                class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Nama
                                Wisata</label>
                            <input type="text" id="name" name="name" placeholder="Masukkan nama wisata"
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-[#8B6F47] focus:border-transparent"
                                required value="{{ old('name') }}">
                            @error('name')
                                <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Kota --}}
                        <div>
                            <label for="kota_id"
                                class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Kota</label>
                            <select id="kota_id" name="kota_id"
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-[#8B6F47] focus:border-transparent"
                                required>
                                <option value="">Pilih Kota</option>
                                @foreach ($kotas as $kota)
                                    <option value="{{ $kota->id }}"
                                        {{ old('kota_id') == $kota->id ? 'selected' : '' }}>
                                        {{ $kota->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kota_id')
                                <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Status --}}
                        <div>
                            <label for="status"
                                class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Status</label>
                            <select id="status" name="status"
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-[#8B6F47] focus:border-transparent"
                                required>
                                <option value="Open" {{ old('status') == 'Open' ? 'selected' : '' }}>Open</option>
                                <option value="Closed" {{ old('status') == 'Closed' ? 'selected' : '' }}>Closed
                                </option>
                            </select>
                            @error('status')
                                <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Jam Buka --}}
                        <div>
                            <label for="jam_buka"
                                class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Jam
                                Buka</label>
                            <input type="time" id="jam_buka" name="jam_buka"
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-[#8B6F47] focus:border-transparent"
                                required value="{{ old('jam_buka') }}">
                            @error('jam_buka')
                                <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Jam Tutup --}}
                        <div>
                            <label for="jam_tutup"
                                class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Jam
                                Tutup</label>
                            <input type="time" id="jam_tutup" name="jam_tutup"
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-[#8B6F47] focus:border-transparent"
                                required value="{{ old('jam_tutup') }}">
                            @error('jam_tutup')
                                <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Harga Tiket --}}
                        <div>
                            <label for="harga_tiket"
                                class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Harga Tiket
                            </label>

                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">Rp</span>
                                </div>

                                <input type="text" id="harga_tiket" name="harga_tiket" placeholder="0"
                                    class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-[#8B6F47] focus:border-transparent"
                                    required
                                    value="{{ old('harga_tiket', isset($wisata) ? number_format($wisata->harga_tiket, 0, ',', '.') : '') }}"
                                    onkeyup="formatRupiah(this)">
                            </div>

                            @error('harga_tiket')
                                <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Gambar --}}
                    <div>
                        <label for="gambar"
                            class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Gambar</label>

                        <div id="drop-zone"
                            class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-6 text-center cursor-pointer hover:border-[#8B6F47] dark:hover:border-[#D4AF37] transition">

                            <input type="file" id="gambar" name="gambar" accept="image/*" class="hidden" required
                                onchange="previewGambar(event)">

                            <label for="gambar" class="cursor-pointer">
                                <i
                                    class="fas fa-cloud-upload-alt text-4xl text-gray-400 dark:text-gray-500 mb-2 block"></i>
                                <p class="text-gray-600 dark:text-gray-400 font-medium">Klik untuk upload atau drag &
                                    drop</p>
                                <p class="text-gray-500 dark:text-gray-500 text-sm">PNG, JPG, GIF (Max 2MB)</p>
                            </label>
                        </div>

                        <div id="gambar-preview" class="mt-4 hidden">
                            <img id="preview-image" src="" alt="Preview" class="max-h-40 rounded-lg">
                        </div>

                        @error('gambar')
                            <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Alamat --}}
                    <div>
                        <label for="alamat"
                            class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Alamat</label>
                        <input type="text" id="alamat" name="alamat" placeholder="Masukkan alamat wisata"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-[#8B6F47] focus:border-transparent"
                            required value="{{ old('alamat') }}">
                        @error('alamat')
                            <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Deskripsi --}}
                    <div>
                        <label for="description"
                            class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Deskripsi</label>
                        <textarea id="description" name="description" rows="6" placeholder="Masukkan deskripsi wisata"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-[#8B6F47] focus:border-transparent"
                            required>{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Google Maps Link --}}
                        <div>
                            <label for="links_maps"
                                class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Link Google
                                Maps</label>
                            <input type="url" id="links_maps" name="links_maps" placeholder="https://..."
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-[#8B6F47] focus:border-transparent"
                                value="{{ old('links_maps') }}">
                            @error('links_maps')
                                <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Buttons --}}
                    <div class="flex gap-4 pt-6">
                        <button type="submit"
                            class="bg-gradient-to-r from-[#8B6F47] to-[#D4AF37] text-white px-6 py-2 rounded-lg font-semibold hover:shadow-lg transition-all duration-300">
                            <i class="fas fa-save mr-2"></i>Simpan
                        </button>
                        <a href="{{ route('admin.wisata.index') }}"
                            class="bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-200 px-6 py-2 rounded-lg font-semibold hover:bg-gray-400 dark:hover:bg-gray-500 transition-all duration-300">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function previewGambar(event) {
            const preview = document.getElementById('gambar-preview');
            const previewImage = document.getElementById('preview-image');
            const file = event.target.files[0];

            if (file) {
                const reader = new FileReader();
                reader.onload = function() {
                    previewImage.src = reader.result;
                    preview.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        }

        const dropZone = document.getElementById('drop-zone');
        const inputGambar = document.getElementById('gambar');

        // Mencegah browser membuka gambar saat di-drag
        dropZone.addEventListener('dragover', (e) => e.preventDefault());

        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();

            if (e.dataTransfer.files.length) {
                inputGambar.files = e.dataTransfer.files;
                previewGambar({
                    target: inputGambar
                });
            }
        });

        function formatRupiah(input) {
            let angka = input.value.replace(/[^,\d]/g, '').toString();
            let split = angka.split(',');
            let sisa = split[0].length % 3;
            let rupiah = split[0].substr(0, sisa);
            let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                let separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            input.value = rupiah;
        }
    </script>
</x-layouts.admin>

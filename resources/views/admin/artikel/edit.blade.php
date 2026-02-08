<x-layouts.admin>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Berita') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Header --}}
            <div class="mb-8">
                <a href="{{ route('admin.artikel.index') }}"
                    class="text-[#8B6F47] dark:text-[#D4AF37] hover:text-[#D4AF37] dark:hover:text-[#8B6F47] font-medium flex items-center mb-4">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Edit Artikel</h1>
            </div>

            {{-- Form --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 md:p-8">
                <form action="{{ route('admin.artikel.update', $artikel) }}" method="POST"
                    enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    {{-- Judul --}}
                    <div>
                        <label for="judul"
                            class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Judul
                            Artikel</label>
                        <input type="text" id="judul" name="judul" placeholder="Masukkan judul artikel"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-[#8B6F47] focus:border-transparent"
                            required value="{{ old('judul', $artikel->judul) }}">
                        @error('judul')
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
                            @foreach ($kotas as $kota)
                                <option value="{{ $kota->id }}"
                                    {{ old('kota_id', $artikel->kota_id) == $kota->id ? 'selected' : '' }}>
                                    {{ $kota->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('kota_id')
                            <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Thumbnail --}}
                    <div>
                        <label for="thumbnail"
                            class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Thumbnail</label>
                        @if ($artikel->thumbnail)
                            <div class="mb-4">
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Gambar Saat Ini:</p>
                                <img src="{{ asset('storage/' . $artikel->thumbnail) }}" alt="Thumbnail"
                                    class="max-h-40 rounded-lg">
                            </div>
                        @endif
                        <div
                            class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-6 text-center cursor-pointer hover:border-[#8B6F47] dark:hover:border-[#D4AF37] transition">
                            <input type="file" id="thumbnail" name="thumbnail" accept="image/*" class="hidden"
                                onchange="previewThumbnail(event)">
                            <label for="thumbnail" class="cursor-pointer">
                                <i
                                    class="fas fa-cloud-upload-alt text-4xl text-gray-400 dark:text-gray-500 mb-2 block"></i>
                                <p class="text-gray-600 dark:text-gray-400 font-medium">Klik untuk upload atau drag &
                                    drop</p>
                                <p class="text-gray-500 dark:text-gray-500 text-sm">Biarkan kosong jika tidak ingin
                                    mengubah</p>
                            </label>
                        </div>
                        <div id="thumbnail-preview" class="mt-4 hidden">
                            <img id="preview-image" src="" alt="Preview" class="max-h-40 rounded-lg">
                        </div>
                        @error('thumbnail')
                            <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Isi --}}
                    <div>
                        <label for="isi"
                            class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Isi
                            Artikel</label>
                        <textarea id="isi" name="isi" rows="8" placeholder="Masukkan isi artikel"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-[#8B6F47] focus:border-transparent"
                            required>{{ old('isi', is_array($artikel->isi) ? implode('\n\n', $artikel->isi) : $artikel->isi) }}</textarea>
                        @error('isi')
                            <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Buttons --}}
                    <div class="flex gap-4 pt-6">
                        <button type="submit"
                            class="bg-gradient-to-r from-[#8B6F47] to-[#D4AF37] text-white px-6 py-2 rounded-lg font-semibold hover:shadow-lg transition-all duration-300">
                            <i class="fas fa-save mr-2"></i>Perbarui
                        </button>
                        <a href="{{ route('admin.artikel.index') }}"
                            class="bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-200 px-6 py-2 rounded-lg font-semibold hover:bg-gray-400 dark:hover:bg-gray-500 transition-all duration-300">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function previewThumbnail(event) {
            const preview = document.getElementById('thumbnail-preview');
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
    </script>
</x-layouts.admin>

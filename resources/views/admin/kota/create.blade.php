<x-layouts.admin>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tambah Kota') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Header --}}
            <div class="mb-8">
                <a href="{{ route('admin.kota.index') }}"
                    class="text-[#8B6F47] dark:text-[#D4AF37] hover:text-[#D4AF37] dark:hover:text-[#8B6F47] font-medium flex items-center mb-4">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Tambah Kota</h1>
            </div>

            {{-- Form --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 md:p-8">
                <form action="{{ route('admin.kota.store') }}" method="POST" class="space-y-6">
                    @csrf

                    {{-- Nama Kota --}}
                    <div>
                        <label for="name"
                            class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Nama Kota</label>
                        <input type="text" id="name" name="name" placeholder="Masukkan nama kota"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-[#8B6F47] focus:border-transparent"
                            required value="{{ old('name') }}">
                        @error('name')
                            <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- gambar --}}
                    <div>
                        <label for="gambar"
                            class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Gambar</label>

                        <div id="drop-zone"
                            class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-6 text-center cursor-pointer hover:border-[#8B6F47] dark:hover:border-[#D4AF37] transition">

                            <input type="file" id="gambar" name="image" accept="image/*" class="hidden" required
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

                        @error('image')
                            <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Buttons --}}
                    <div class="flex gap-4 pt-6">
                        <button type="submit"
                            class="bg-gradient-to-r from-[#8B6F47] to-[#D4AF37] text-white px-6 py-2 rounded-lg font-semibold hover:shadow-lg transition-all duration-300">
                            <i class="fas fa-save mr-2"></i>Simpan
                        </button>
                        <a href="{{ route('admin.kota.index') }}"
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
    </script>
</x-layouts.admin>

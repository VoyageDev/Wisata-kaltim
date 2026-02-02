<x-layouts.admin>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Detail Wisata') }}
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
                <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $wisata->name }}</h1>
            </div>

            {{-- Detail Card --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden">
                {{-- Image --}}
                @if ($wisata->gambar)
                    <div class="h-96 overflow-hidden bg-gray-200 dark:bg-gray-700">
                        <img src="{{ asset('storage/' . $wisata->gambar) }}" alt="{{ $wisata->name }}"
                            class="w-full h-full object-cover">
                    </div>
                @endif

                <div class="p-6 md:p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        {{-- Left Column --}}
                        <div class="space-y-6">
                            {{-- Kota --}}
                            <div>
                                <label
                                    class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Kota</label>
                                <div
                                    class="inline-block bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 text-sm px-4 py-2 rounded-lg font-medium">
                                    {{ $wisata->kota->name }}
                                </div>
                            </div>

                            {{-- Status --}}
                            <div>
                                <label
                                    class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Status</label>
                                <span
                                    class="inline-block px-4 py-2 rounded-lg text-sm font-semibold {{ $wisata->status === 'Open' ? 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200' : 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200' }}">
                                    {{ $wisata->status }}
                                </span>
                            </div>

                            {{-- Harga Tiket --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Harga
                                    Tiket</label>
                                <p class="text-2xl font-bold text-[#8B6F47] dark:text-[#D4AF37]">
                                    Rp {{ number_format($wisata->harga_tiket, 0, ',', '.') }}
                                </p>
                            </div>

                            {{-- Jam Operasional --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Jam
                                    Operasional</label>
                                <p class="text-gray-600 dark:text-gray-400">
                                    {{ $wisata->jam_buka }} - {{ $wisata->jam_tutup }}
                                </p>
                            </div>
                        </div>

                        {{-- Right Column --}}
                        <div class="space-y-6">
                            {{-- Alamat --}}
                            <div>
                                <label
                                    class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Alamat</label>
                                <p class="text-gray-600 dark:text-gray-400">{{ $wisata->alamat }}</p>
                            </div>

                            {{-- Google Maps --}}
                            @if ($wisata->links_maps)
                                <div>
                                    <label
                                        class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Lokasi
                                        di Maps</label>
                                    <a href="{{ $wisata->links_maps }}" target="_blank"
                                        class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300 font-medium flex items-center">
                                        <i class="fas fa-map-marker-alt mr-2"></i>Buka di Google Maps
                                    </a>
                                </div>
                            @endif

                            {{-- Booking Links --}}
                            @if ($wisata->links_bookings)
                                <div>
                                    <label
                                        class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Booking
                                        Online</label>
                                    @php
                                        $bookings = is_string($wisata->links_bookings)
                                            ? json_decode($wisata->links_bookings, true)
                                            : (is_array($wisata->links_bookings)
                                                ? $wisata->links_bookings
                                                : []);
                                    @endphp
                                    <div class="space-y-2">
                                        @if (is_array($bookings) && count($bookings) > 0)
                                            @foreach ($bookings as $booking)
                                                <a href="{{ $booking }}" target="_blank"
                                                    class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300 font-medium flex items-center">
                                                    <i
                                                        class="fas fa-link mr-2"></i>{{ parse_url($booking, PHP_URL_HOST) }}
                                                </a>
                                            @endforeach
                                        @else
                                            <p class="text-gray-500 dark:text-gray-400">Tidak ada link booking</p>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Description --}}
                    <div class="mt-8 pt-8 border-t border-gray-200 dark:border-gray-700">
                        <label
                            class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">Deskripsi</label>
                        <div class="prose prose-sm dark:prose-invert max-w-none text-gray-600 dark:text-gray-400">
                            {!! nl2br(e($wisata->description)) !!}
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="mt-8 pt-8 border-t border-gray-200 dark:border-gray-700 flex gap-4">
                        <a href="{{ route('admin.wisata.edit', $wisata) }}"
                            class="bg-gradient-to-r from-[#8B6F47] to-[#D4AF37] text-white px-6 py-2 rounded-lg font-semibold hover:shadow-lg transition-all duration-300 inline-flex items-center">
                            <i class="fas fa-edit mr-2"></i>Edit
                        </a>
                        <form action="{{ route('admin.wisata.destroy', $wisata) }}" method="POST"
                            onsubmit="return confirm('Yakin ingin menghapus?')" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg font-semibold transition-all duration-300 inline-flex items-center">
                                <i class="fas fa-trash mr-2"></i>Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>

<x-layouts.user>
    <div class="min-h-screen bg-gradient-to-b from-gray-50 to-gray-100 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Header --}}
            <div class="mb-12">
                <h1 class="text-4xl font-bold text-gray-800 mb-4">
                    <i class="fas fa-map-marked-alt text-[#8B6F47] mr-3"></i>
                    Daftar Wisata
                </h1>
                <p class="text-gray-600 text-lg">Temukan destinasi wisata terbaik yang ada di kalimantan timur</p>
            </div>

            {{-- Wisata Grid (4 Columns) --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mb-12">
                @forelse ($wisatas as $wisata)
                    <a href="{{ route('wisata.detail', $wisata->slug) }}" class="group">
                        <div
                            class="bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 border border-gray-200 h-full flex flex-col">
                            {{-- Wisata Image --}}
                            <div class="relative h-48 overflow-hidden bg-gray-300">
                                <img src="{{ asset('images/seed/wisata/' . $wisata->gambar) }}" alt="{{ $wisata->name }}"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                {{-- Status Badge --}}
                                <div class="absolute top-4 right-4">
                                    <span
                                        class="px-3 py-1 rounded-full text-xs font-semibold shadow-lg {{ $wisata->status === 'Open' ? 'bg-green-500 text-white' : 'bg-red-500 text-white' }}">
                                        <i class="fas fa-circle text-xs mr-1"></i>{{ $wisata->status }}
                                    </span>
                                </div>
                                {{-- Kota Badge --}}
                                @if ($wisata->kota)
                                    <div class="absolute top-4 left-4">
                                        <span
                                            class="px-3 py-1 bg-[#535353] text-white rounded-full text-xs font-semibold shadow-lg">
                                            <i class="fas fa-map-marker-alt mr-1"></i>{{ $wisata->kota->name }}
                                        </span>
                                    </div>
                                @endif
                            </div>

                            {{-- Wisata Info --}}
                            <div class="p-5 flex flex-col flex-grow">
                                <h3
                                    class="text-lg font-bold text-gray-800 mb-3 line-clamp-2 group-hover:text-[#8B6F47] transition-colors">
                                    {{ $wisata->name }}
                                </h3>
                                <p class="text-sm text-gray-600 mb-3 line-clamp-2 flex-grow">
                                    {{ Str::limit($wisata->description, 80) }}
                                </p>
                                <div class="flex items-center justify-between text-xs text-gray-500 mb-4">
                                    <span class="flex items-center">
                                        <i class="fas fa-ticket text-[#000000] mr-1"></i>
                                        @if ($wisata->harga_tiket > 0)
                                            Rp {{ number_format($wisata->harga_tiket, 0, ',', '.') }}
                                        @else
                                            Gratis
                                        @endif
                                    </span>
                                    <span class="flex items-center">
                                        <i class="fas fa-clock text-[#aaaaaa] mr-1"></i>
                                        {{ $wisata->jam_buka->format('H:i') }} -
                                        {{ $wisata->jam_tutup->format('H:i') }}
                                    </span>
                                </div>
                                <button
                                    class="w-full bg-gradient-to-r from-[#098207] to-[#23e111] text-white py-2 rounded-lg font-semibold hover:shadow-lg transition-all duration-300 text-sm">
                                    <i class="fas fa-info-circle mr-2"></i>Detail
                                </button>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="col-span-full">
                        <div class="bg-white rounded-xl shadow-md p-12 text-center">
                            <i class="fas fa-map-marked-alt text-gray-300 text-6xl mb-4"></i>
                            <h3 class="text-xl font-semibold text-gray-600 mb-2">Belum Ada Wisata</h3>
                            <p class="text-gray-500">Daftar wisata masih kosong.</p>
                        </div>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            @if ($wisatas->hasPages())
                <div class="mt-8">
                    {{ $wisatas->links() }}
                </div>
            @endif

        </div>
    </div>
</x-layouts.user>

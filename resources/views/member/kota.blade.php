<x-layouts.user>
    <div class="min-h-screen bg-gradient-to-b from-gray-50 to-gray-100 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Header --}}
            <div class="mb-12">
                <h1 class="text-4xl font-bold text-gray-800 mb-4">
                    <i class="fas fa-map text-[#8B6F47] mr-3"></i>
                    Daftar Kota
                </h1>
                <p class="text-gray-600 text-lg">Jelajahi berbagai kota dan wisata menarik di Indonesia</p>
            </div>

            {{-- Cities Grid (5 Columns) --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6 mb-12">
                @forelse ($kotas as $kota)
                    <a href="{{ route('kota.detail', $kota->slug) ?? '#' }}" class="group">
                        <div
                            class="bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 border border-gray-200 h-full flex flex-col">
                            {{-- City Image --}}
                            <div class="relative h-40 overflow-hidden bg-gray-300">
                                <img src="{{ asset($kota->image) }}" alt="{{ $kota->name }}"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                {{-- Wisata Count Badge --}}
                                <div
                                    class="absolute top-4 right-4 bg-[#8B6F47] text-white px-3 py-1 rounded-full text-sm font-semibold shadow-lg">
                                    {{ $kota->wisatas_count ?? 0 }}
                                </div>
                            </div>

                            {{-- City Info --}}
                            <div class="p-5 flex flex-col flex-grow">
                                <h3
                                    class="text-lg font-bold text-gray-800 mb-2 line-clamp-2 group-hover:text-[#8B6F47] transition-colors">
                                    {{ $kota->name }}
                                </h3>
                                <p class="text-sm text-gray-600 mb-4 flex-grow">
                                    <i class="fas fa-compass text-[#8B6F47] mr-2"></i>
                                    {{ $kota->wisatas_count ?? 0 }} Tempat Wisata
                                </p>
                                <button
                                    class="w-full bg-gradient-to-r from-[#8B6F47] to-[#D4AF37] text-white py-2 rounded-lg font-semibold hover:shadow-lg transition-all duration-300 text-sm">
                                    Jelajahi
                                </button>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="col-span-full">
                        <div class="bg-white rounded-xl shadow-md p-12 text-center">
                            <i class="fas fa-inbox text-gray-400 text-5xl mb-4"></i>
                            <p class="text-gray-600 text-lg">Tidak ada kota tersedia</p>
                        </div>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            @if ($kotas->hasPages())
                <div class="flex justify-center">
                    {{ $kotas->links() }}
                </div>
            @endif

        </div>
    </div>
</x-layouts.user>

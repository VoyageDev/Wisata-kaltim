<x-layouts.user>
    <div class="min-h-screen bg-gradient-to-b from-gray-50 to-gray-100 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Berita Terbaru Section --}}
            <div class="mb-16">
                <div class="flex items-center justify-between mb-8">
                    <h2 class="text-3xl font-bold text-gray-800 flex items-center">
                        <i class="fas fa-star text-yellow-500 mr-3"></i>
                        Berita Terbaru
                    </h2>
                </div>

                <div class="space-y-4" id="berita-terbaru-list">
                    @foreach ($beritaTerbaru as $artikel)
                        <div
                            class="bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-200">
                            <div class="flex flex-col md:flex-row">
                                <div class="md:w-72 h-48 overflow-hidden">
                                    <img src="/{{ $artikel->thumbnail }}" alt="{{ $artikel->judul }}"
                                        class="w-full h-full object-cover hover:scale-110 transition-transform duration-300">
                                </div>
                                <div class="flex-1 p-6">
                                    <div class="flex items-center gap-3 text-sm text-gray-600 mb-3">
                                        <span class="flex items-center">
                                            <i class="fas fa-map-marker-alt text-[#8B6F47] mr-1"></i>
                                            {{ $artikel->kota->name }}
                                        </span>
                                        <span class="flex items-center">
                                            <i class="fas fa-calendar text-[#8B6F47] mr-1"></i>
                                            {{ $artikel->created_at->format('d M Y') }}
                                        </span>
                                        <span class="flex items-center">
                                            <i class="fas fa-eye text-[#8B6F47] mr-1"></i>
                                            {{ number_format($artikel->views, 0, ',', '.') }} views
                                        </span>
                                    </div>
                                    <h3
                                        class="text-xl font-bold text-gray-800 mb-3 hover:text-[#8B6F47] transition-colors">
                                        <a href="/artikel/{{ $artikel->slug }}">{{ $artikel->judul }}</a>
                                    </h3>
                                    <p class="text-gray-600 line-clamp-2 mb-4">
                                        {{ Str::limit(strip_tags($artikel->isi), 150) }}
                                    </p>
                                    <div class="flex items-center text-sm text-gray-500">
                                        <i class="fas fa-user-circle mr-2"></i>
                                        {{ $artikel->user->name }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if ($beritaTerbaru->count() < $totalBeritaTerbaru)
                    <div class="mt-6 text-center">
                        <button onclick="loadMoreBeritaTerbaru()" id="btn-load-terbaru"
                            class="bg-gradient-to-r from-[#8B6F47] to-[#D4AF37] text-white px-8 py-3 rounded-full font-semibold hover:shadow-lg transition-all duration-300">
                            <i class="fas fa-chevron-down mr-2"></i>Load More
                        </button>
                    </div>
                @endif
            </div>

            {{-- Populer Bulan Ini Section --}}
            <div class="mb-16">
                <div class="flex items-center justify-between mb-8">
                    <h2 class="text-3xl font-bold text-gray-800 flex items-center">
                        <i class="fas fa-fire text-red-500 mr-3"></i>
                        Populer Bulan Ini
                    </h2>
                </div>

                <div class="space-y-4" id="populer-list">
                    @foreach ($populerBulanIni as $artikel)
                        <div
                            class="bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-200">
                            <div class="flex flex-col md:flex-row">
                                <div class="md:w-72 h-48 overflow-hidden">
                                    <img src="/{{ $artikel->thumbnail }}" alt="{{ $artikel->judul }}"
                                        class="w-full h-full object-cover hover:scale-110 transition-transform duration-300">
                                </div>
                                <div class="flex-1 p-6">
                                    <div class="flex items-center gap-3 text-sm text-gray-600 mb-3">
                                        <span class="flex items-center">
                                            <i class="fas fa-map-marker-alt text-[#8B6F47] mr-1"></i>
                                            {{ $artikel->kota->name }}
                                        </span>
                                        <span class="flex items-center">
                                            <i class="fas fa-calendar text-[#8B6F47] mr-1"></i>
                                            {{ $artikel->created_at->format('d M Y') }}
                                        </span>
                                        <span class="flex items-center">
                                            <i class="fas fa-eye text-[#8B6F47] mr-1"></i>
                                            {{ number_format($artikel->views, 0, ',', '.') }} views
                                        </span>
                                    </div>
                                    <h3
                                        class="text-xl font-bold text-gray-800 mb-3 hover:text-[#8B6F47] transition-colors">
                                        <a href="/artikel/{{ $artikel->slug }}">{{ $artikel->judul }}</a>
                                    </h3>
                                    <p class="text-gray-600 line-clamp-2 mb-4">
                                        {{ Str::limit(strip_tags($artikel->isi), 150) }}
                                    </p>
                                    <div class="flex items-center text-sm text-gray-500">
                                        <i class="fas fa-user-circle mr-2"></i>
                                        {{ $artikel->user->name }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if ($populerBulanIni->count() < $totalPopulerBulanIni)
                    <div class="mt-6 text-center">
                        <button onclick="loadMorePopuler()" id="btn-load-populer"
                            class="bg-gradient-to-r from-[#8B6F47] to-[#D4AF37] text-white px-8 py-3 rounded-full font-semibold hover:shadow-lg transition-all duration-300">
                            <i class="fas fa-chevron-down mr-2"></i>Load More
                        </button>
                    </div>
                @endif
            </div>

            {{-- Top Wisata Section --}}
            <div>
                <div class="flex items-center justify-between mb-8">
                    <h2 class="text-3xl font-bold text-gray-800 flex items-center">
                        <i class="fas fa-trophy text-yellow-500 mr-3"></i>
                        Top Wisata
                    </h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="top-wisata-list">
                    @foreach ($topWisata as $artikel)
                        <div
                            class="bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-200">
                            <div class="h-48 overflow-hidden">
                                <img src="/{{ $artikel->thumbnail }}" alt="{{ $artikel->judul }}"
                                    class="w-full h-full object-cover hover:scale-110 transition-transform duration-300">
                            </div>
                            <div class="p-5">
                                <div class="flex items-center gap-3 text-xs text-gray-600 mb-3">
                                    <span class="flex items-center">
                                        <i class="fas fa-map-marker-alt text-[#8B6F47] mr-1"></i>
                                        {{ $artikel->kota->name }}
                                    </span>
                                    <span class="flex items-center">
                                        <i class="fas fa-eye text-[#8B6F47] mr-1"></i>
                                        {{ number_format($artikel->views, 0, ',', '.') }}
                                    </span>
                                </div>
                                <h3
                                    class="text-lg font-bold text-gray-800 mb-2 line-clamp-2 hover:text-[#8B6F47] transition-colors">
                                    <a href="/artikel/{{ $artikel->slug }}">{{ $artikel->judul }}</a>
                                </h3>
                                <p class="text-gray-600 text-sm line-clamp-3 mb-3">
                                    {{ Str::limit(strip_tags($artikel->isi), 100) }}
                                </p>
                                <div class="flex items-center justify-between text-xs text-gray-500">
                                    <span class="flex items-center">
                                        <i class="fas fa-user-circle mr-1"></i>
                                        {{ Str::limit($artikel->user->name, 15) }}
                                    </span>
                                    <span>{{ $artikel->created_at->format('d M Y') }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if ($topWisata->count() < $totalTopWisata)
                    <div class="mt-8 text-center">
                        <button onclick="loadMoreTopWisata()" id="btn-load-wisata"
                            class="bg-gradient-to-r from-[#8B6F47] to-[#D4AF37] text-white px-8 py-3 rounded-full font-semibold hover:shadow-lg transition-all duration-300">
                            <i class="fas fa-chevron-down mr-2"></i>Load More
                        </button>
                    </div>
                @endif
            </div>

        </div>
    </div>

    <script>
        // Script Load More
        var offsetTerbaru = {{ $beritaTerbaru->count() }};
        var totalTerbaru = {{ $totalBeritaTerbaru }};

        var offsetPopuler = {{ $populerBulanIni->count() }};
        var totalPopuler = {{ $totalPopulerBulanIni }};

        var offsetWisata = {{ $topWisata->count() }};
        var totalWisata = {{ $totalTopWisata }};

        function loadMoreBeritaTerbaru() {
            var btn = document.getElementById('btn-load-terbaru');
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Loading...';
            btn.disabled = true;

            fetch('/artikel-load-more/terbaru/' + offsetTerbaru)
                .then(response => response.json())
                .then(data => {
                    var container = document.getElementById('berita-terbaru-list');

                    data.forEach(function(artikel) {
                        var html = `
                            <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-200">
                                <div class="flex flex-col md:flex-row">
                                    <div class="md:w-72 h-48 overflow-hidden">
                                        <img src="/${artikel.thumbnail}" alt="${artikel.judul}"
                                            class="w-full h-full object-cover hover:scale-110 transition-transform duration-300">
                                    </div>
                                    <div class="flex-1 p-6">
                                        <div class="flex items-center gap-3 text-sm text-gray-600 mb-3">
                                            <span class="flex items-center">
                                                <i class="fas fa-map-marker-alt text-[#8B6F47] mr-1"></i>
                                                ${artikel.kota.name}
                                            </span>
                                            <span class="flex items-center">
                                                <i class="fas fa-calendar text-[#8B6F47] mr-1"></i>
                                                ${new Date(artikel.created_at).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' })}
                                            </span>
                                            <span class="flex items-center">
                                                <i class="fas fa-eye text-[#8B6F47] mr-1"></i>
                                                ${artikel.views.toLocaleString('id-ID')} views
                                            </span>
                                        </div>
                                        <h3 class="text-xl font-bold text-gray-800 mb-3 hover:text-[#8B6F47] transition-colors">
                                            <a href="/artikel/${artikel.slug}">${artikel.judul}</a>
                                        </h3>
                                        <p class="text-gray-600 line-clamp-2 mb-4">
                                            ${artikel.isi.replace(/<[^>]*>/g, '').substring(0, 150)}...
                                        </p>
                                        <div class="flex items-center text-sm text-gray-500">
                                            <i class="fas fa-user-circle mr-2"></i>
                                            ${artikel.user.name}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                        container.innerHTML += html;
                    });

                    offsetTerbaru = offsetTerbaru + data.length;

                    if (offsetTerbaru >= totalTerbaru) {
                        btn.style.display = 'none';
                    } else {
                        btn.innerHTML = '<i class="fas fa-chevron-down mr-2"></i>Load More';
                        btn.disabled = false;
                    }
                });
        }

        function loadMorePopuler() {
            var btn = document.getElementById('btn-load-populer');
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Loading...';
            btn.disabled = true;

            fetch('/artikel-load-more/populer/' + offsetPopuler)
                .then(response => response.json())
                .then(data => {
                    var container = document.getElementById('populer-list');

                    data.forEach(function(artikel) {
                        var html = `
                            <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-200">
                                <div class="flex flex-col md:flex-row">
                                    <div class="md:w-72 h-48 overflow-hidden">
                                        <img src="/${artikel.thumbnail}" alt="${artikel.judul}"
                                            class="w-full h-full object-cover hover:scale-110 transition-transform duration-300">
                                    </div>
                                    <div class="flex-1 p-6">
                                        <div class="flex items-center gap-3 text-sm text-gray-600 mb-3">
                                            <span class="flex items-center">
                                                <i class="fas fa-map-marker-alt text-[#8B6F47] mr-1"></i>
                                                ${artikel.kota.name}
                                            </span>
                                            <span class="flex items-center">
                                                <i class="fas fa-calendar text-[#8B6F47] mr-1"></i>
                                                ${new Date(artikel.created_at).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' })}
                                            </span>
                                            <span class="flex items-center">
                                                <i class="fas fa-eye text-[#8B6F47] mr-1"></i>
                                                ${artikel.views.toLocaleString('id-ID')} views
                                            </span>
                                        </div>
                                        <h3 class="text-xl font-bold text-gray-800 mb-3 hover:text-[#8B6F47] transition-colors">
                                            <a href="/artikel/${artikel.slug}">${artikel.judul}</a>
                                        </h3>
                                        <p class="text-gray-600 line-clamp-2 mb-4">
                                            ${artikel.isi.replace(/<[^>]*>/g, '').substring(0, 150)}...
                                        </p>
                                        <div class="flex items-center text-sm text-gray-500">
                                            <i class="fas fa-user-circle mr-2"></i>
                                            ${artikel.user.name}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                        container.innerHTML += html;
                    });

                    offsetPopuler = offsetPopuler + data.length;

                    if (offsetPopuler >= totalPopuler) {
                        btn.style.display = 'none';
                    } else {
                        btn.innerHTML = '<i class="fas fa-chevron-down mr-2"></i>Load More';
                        btn.disabled = false;
                    }
                });
        }

        function loadMoreTopWisata() {
            var btn = document.getElementById('btn-load-wisata');
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Loading...';
            btn.disabled = true;

            fetch('/artikel-load-more/top-wisata/' + offsetWisata)
                .then(response => response.json())
                .then(data => {
                    var container = document.getElementById('top-wisata-list');

                    data.forEach(function(artikel) {
                        var html = `
                            <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-200">
                                <div class="h-48 overflow-hidden">
                                    <img src="/${artikel.thumbnail}" alt="${artikel.judul}"
                                        class="w-full h-full object-cover hover:scale-110 transition-transform duration-300">
                                </div>
                                <div class="p-5">
                                    <div class="flex items-center gap-3 text-xs text-gray-600 mb-3">
                                        <span class="flex items-center">
                                            <i class="fas fa-map-marker-alt text-[#8B6F47] mr-1"></i>
                                            ${artikel.kota.name}
                                        </span>
                                        <span class="flex items-center">
                                            <i class="fas fa-eye text-[#8B6F47] mr-1"></i>
                                            ${artikel.views.toLocaleString('id-ID')}
                                        </span>
                                    </div>
                                    <h3 class="text-lg font-bold text-gray-800 mb-2 line-clamp-2 hover:text-[#8B6F47] transition-colors">
                                        <a href="/artikel/${artikel.slug}">${artikel.judul}</a>
                                    </h3>
                                    <p class="text-gray-600 text-sm line-clamp-3 mb-3">
                                        ${artikel.isi.replace(/<[^>]*>/g, '').substring(0, 100)}...
                                    </p>
                                    <div class="flex items-center justify-between text-xs text-gray-500">
                                        <span class="flex items-center">
                                            <i class="fas fa-user-circle mr-1"></i>
                                            ${artikel.user.name.substring(0, 15)}
                                        </span>
                                        <span>${new Date(artikel.created_at).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' })}</span>
                                    </div>
                                </div>
                            </div>
                        `;
                        container.innerHTML += html;
                    });

                    offsetWisata = offsetWisata + data.length;

                    if (offsetWisata >= totalWisata) {
                        btn.style.display = 'none';
                    } else {
                        btn.innerHTML = '<i class="fas fa-chevron-down mr-2"></i>Load More';
                        btn.disabled = false;
                    }
                });
        }
    </script>
</x-layouts.user>

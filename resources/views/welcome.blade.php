<x-layouts.user>

    <x-slot:title>Portal Berita Wisata Indonesia</x-slot:title>

    <!-- Hero Section with CTA to Login -->
    <section class="relative h-[600px] flex items-center justify-center text-center text-white">
        <div class="absolute inset-0 bg-cover bg-center z-0"
            style="background-image: url('{{ asset('images/seed/water-park.jpg') }}');">
        </div>
        <div class="absolute inset-0 bg-black/50 z-10"></div>
        <div class="relative z-20 px-4 max-w-4xl animate-[fadeInDown_1s_ease]">
            <div class="mb-6">
                <i class="fas fa-book-open text-6xl text-[#ffffff] mb-4"></i>
            </div>
            <h1 class="text-4xl md:text-6xl font-bold mb-6 drop-shadow-md">Platform Booking Tiket dan Berita Wisata</h1>
            <p class="text-xl md:text-2xl mb-4 drop-shadow-sm">Pesan tiket wisata impianmu dan baca kisah inspiratif dari
                destinasi favorit</p>
            <p class="text-lg md:text-xl mb-10 text-gray-200">Login sekarang untuk mulai booking
                wisata!</p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                @auth
                    <a href="#berita"
                        class="bg-transparent text-gray-900 px-10 py-4 rounded-lg font-bold hover:bg-[#E8C547] hover:-translate-y-1 transition-all duration-300 shadow-lg inline-block">
                        <i class="fas fa-book-reader mr-2"></i> Mulai
                    </a>
                @else
                    <a href="{{ route('login') }}"
                        class="bg-[#4ca0e9] text-white px-10 py-4 rounded-lg font-bold hover:bg-[#3e8ad6] hover:-translate-y-1 transition-all duration-300 shadow-lg inline-block">
                        <i class="fas fa-sign-in-alt mr-2"></i> Login untuk Membaca
                    </a>
                    <a href="{{ route('register') }}"
                        class="bg-white/20 backdrop-blur-sm text-white border-2 border-white px-10 py-4 rounded-lg font-bold hover:bg-white hover:text-gray-900 hover:-translate-y-1 transition-all duration-300 shadow-lg inline-block">
                        <i class="fas fa-user-plus mr-2"></i> Daftar Sekarang
                    </a>
                @endauth
            </div>
        </div>
    </section>

    <!-- Berita Terbaru Section -->
    <section id="berita" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">
                    <i class="fas fa-star text-[#f8f008] mr-2"></i>
                    Berita Wisata Terbaru
                </h2>
                <p class="text-gray-600 text-lg">Temukan informasi detail wisata dan berita terbaru seputar destinasi
                    wisata Indonesia</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse ($artikels as $artikel)
                    <article
                        class="bg-white rounded-xl overflow-hidden shadow-lg hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 animate-[fadeInUp_0.6s_ease] group">
                        <div class="h-64 bg-center bg-cover relative overflow-hidden"
                            style="background-image: url('{{ $artikel->thumbnail ? asset($artikel->thumbnail) : asset('images/seed/artikels/artikel-1.svg') }}');">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
                            <div class="absolute top-4 left-4">
                                <span class="bg-[#e0b939] text-gray-900 text-xs font-bold px-3 py-1 rounded-full">
                                    <i class="fas fa-newspaper mr-1"></i> Berita
                                </span>
                            </div>
                            <div class="absolute bottom-4 left-4 right-4">
                                <div class="text-white text-xs font-semibold mb-2 flex items-center">
                                    <i class="fas fa-map-marker-alt mr-2"></i>
                                    {{ $artikel->wisata->kota->name ?? 'Indonesia' }}
                                </div>
                            </div>
                        </div>
                        <div class="p-6 space-y-3">
                            <div class="text-xs text-[#8B6F47] font-semibold flex items-center gap-2">
                                <i class="fas fa-calendar-alt"></i>
                                {{ optional($artikel->created_at)->format('d M Y') }}
                            </div>
                            <h3
                                class="text-xl font-bold text-gray-800 leading-tight group-hover:text-[#8B6F47] transition-colors line-clamp-2">
                                {{ $artikel->judul }}
                            </h3>
                            <p class="text-gray-600 text-sm leading-relaxed line-clamp-3">
                                {{ Str::limit(strip_tags(is_array($artikel->isi) ? implode(' ', $artikel->isi) : $artikel->isi), 120) }}
                            </p>
                            <div class="pt-3">
                                <a href="{{ route('artikel.detail', $artikel->slug) }}"
                                    class="text-[#D4AF37] font-semibold text-sm hover:text-[#8B6F47] transition-colors inline-flex items-center">
                                    Baca Selengkapnya
                                    <i
                                        class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                                </a>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="col-span-full text-center py-12">
                        <i class="fas fa-newspaper text-6xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500 text-lg">Belum ada berita wisata tersedia</p>
                    </div>
                @endforelse
            </div>

            @if ($artikels->count() > 0)
                <div class="text-center mt-12">
                    <a href="{{ route('artikel.index') }}"
                        class="bg-[#8B6F47] text-white px-8 py-3 rounded-lg font-semibold hover:bg-[#D4AF37] hover:text-gray-900 transition-all duration-300 inline-block">
                        <i class="fas fa-th-large mr-2"></i> Lihat Semua Berita
                    </a>
                </div>
            @endif
        </div>
    </section>

    <!-- Wisata Unggulan per Kota Section -->
    <section class="py-20 bg-gradient-to-br from-[#f8f5f0] to-[#ffe8cc]" id="wisata">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">
                    <i class="fas fa-map-marked-alt text-[#D4AF37] mr-2"></i>
                    Destinasi Wisata Unggulan
                </h2>
                <p class="text-gray-600 text-lg">Jelajahi destinasi wisata terbaik dari berbagai kota</p>
            </div>

            @forelse ($kotaWithWisata->take(3) as $kota)
                <div class="mb-16 last:mb-0">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-2xl font-bold text-gray-800 flex items-center">
                            <i class="fas fa-location-dot text-[#D4AF37] mr-3"></i>
                            {{ $kota->name }}
                        </h3>
                        <span class="text-sm text-gray-600">
                            {{ $kota->wisatas->count() }} destinasi
                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($kota->wisatas->take(3) as $wisata)
                            <a href="{{ route('wisata.detail', $wisata->slug) }}"
                                class="bg-white rounded-xl overflow-hidden shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300 block">
                                <div class="h-48 bg-center bg-cover relative"
                                    style="background-image: url('{{ $wisata->gambar ? asset('images/seed/wisata/' . $wisata->gambar) : asset('images/seed/wisatas/wisata-1.svg') }}');">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                                    <div class="absolute top-3 right-3">
                                        <span
                                            class="px-3 py-1 rounded-full text-xs font-semibold {{ $wisata->status === 'Open' ? 'bg-green-500 text-white' : 'bg-red-500 text-white' }}">
                                            {{ $wisata->status }}
                                        </span>
                                    </div>
                                </div>
                                <div class="p-5 space-y-2">
                                    <h4 class="text-lg font-bold text-gray-800 line-clamp-1">{{ $wisata->name }}</h4>
                                    <p class="text-gray-600 text-sm leading-relaxed line-clamp-2">
                                        {{ $wisata->description }}
                                    </p>
                                    <div class="flex items-center justify-between pt-2 border-t border-gray-100">
                                        <div class="text-xs text-gray-500 flex items-center gap-1">
                                            <i class="fas fa-clock"></i>
                                            <span>{{ $wisata->jam_buka_format }} -
                                                {{ $wisata->jam_tutup_format }}</span>
                                        </div>
                                        <div class="text-sm font-bold text-[#8B6F47]">
                                            {{ $wisata->harga_tiket }}
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="text-center py-12">
                    <i class="fas fa-map-marked-alt text-6xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500 text-lg">Belum ada destinasi wisata tersedia</p>
                </div>
            @endforelse
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gradient-to-br from-gray-800 to-gray-700 text-white pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 mb-10">
                <div>
                    <h4 class="text-lg font-bold mb-4 border-b border-gray-600   pb-2 inline-block">
                        <i class="fas fa-info-circle mr-2"></i>Tentang Portal
                    </h4>
                    <p class="text-gray-300 text-sm leading-relaxed">
                        Platform terpadu untuk booking tiket wisata dan membaca informasi destinasi wisata terbaik di
                        seluruh Kalimantan. Temukan pengalaman wisata impianmu di sini!
                    </p>
                </div>
                <div>
                    <h4 class="text-lg font-bold mb-4 border-b border-gray-600 pb-2 inline-block">
                        <i class="fas fa-book mr-2"></i>Konten
                    </h4>
                    <ul class="space-y-2 text-gray-300 text-sm">
                        <li><a href="#berita" class="hover:text-[#D4AF37] transition"><i
                                    class="fas fa-newspaper mr-2"></i>Berita Wisata</a></li>
                        <li><a href="#wisata" class="hover:text-[#D4AF37] transition"><i
                                    class="fas fa-map-marked-alt mr-2"></i>Destinasi</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-bold mb-4 border-b border-gray-600 pb-2 inline-block">
                        <i class="fas fa-user mr-2"></i>Akun
                    </h4>
                    <ul class="space-y-2 text-gray-300 text-sm">
                        @auth
                            <li><a href="{{ route('dashboard') }}" class="hover:text-[#D4AF37] transition"><i
                                        class="fas fa-tachometer-alt mr-2"></i>Dashboard</a></li>
                            <li><a href="{{ route('profile.edit') }}" class="hover:text-[#D4AF37] transition"><i
                                        class="fas fa-user-edit mr-2"></i>Profil Saya</a></li>
                        @else
                            <li><a href="{{ route('login') }}" class="hover:text-[#D4AF37] transition"><i
                                        class="fas fa-sign-in-alt mr-2"></i>Login</a></li>
                            <li><a href="{{ route('register') }}" class="hover:text-[#D4AF37] transition"><i
                                        class="fas fa-user-plus mr-2"></i>Daftar</a></li>
                        @endauth
                        <li><a href="#" class="hover:text-[#D4AF37] transition"><i
                                    class="fas fa-question-circle mr-2"></i>Bantuan</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-bold mb-4 border-b border-gray-600 pb-2 inline-block">
                        <i class="fas fa-share-alt mr-2"></i>Ikuti Kami
                    </h4>
                    <ul class="space-y-2 text-gray-300 text-sm">
                        <li><a href="#" class="hover:text-[#D4AF37] transition"><i
                                    class="fab fa-facebook mr-2"></i> Facebook</a></li>
                        <li><a href="#" class="hover:text-[#D4AF37] transition"><i
                                    class="fab fa-instagram mr-2"></i> Instagram</a></li>
                        <li><a href="#" class="hover:text-[#D4AF37] transition"><i
                                    class="fab fa-github mr-2"></i> Github</a></li>
                        <li><a href="#" class="hover:text-[#D4AF37] transition"><i
                                    class="fab fa-youtube mr-2"></i> YouTube</a></li>
                    </ul>
                </div>
                <div>
                    <h4
                        class="flex items-center gap-2 text-lg font-bold mb-4 border-b border-gray-600 pb-2 inline-flex">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round"
                            class="icon icon-tabler icons-tabler-outline icon-tabler-user-share">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                            <path d="M6 21v-2a4 4 0 0 1 4 -4h3" />
                            <path d="M16 22l5 -5" />
                            <path d="M21 21.5v-4.5h-4.5" />
                        </svg>
                        <span>Kontributor</span>
                    </h4>
                    <ul class="space-y-2 text-gray-300 text-sm">

                        <li><a href="https://github.com/VoyageDev" class="hover:text-[#D4AF37] transition"><i
                                    class="fab fa-github mr-2"></i> VoyageDev</a></li>
                        <li><a href="https://github.com/valeriannn-create" class="hover:text-[#D4AF37] transition"><i
                                    class="fab fa-github mr-2"></i> Nabhan</a></li>
                        <li><a href="https://github.com/" class="hover:text-[#D4AF37] transition"><i
                                    class="fab fa-github mr-2"></i> Aqso</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-600 pt-8 text-center text-sm text-gray-400">
                <p>&copy; 2026 Booking Wisata Kaltim. All rights reserved.</p>
            </div>
        </div>
    </footer>

</x-layouts.user>

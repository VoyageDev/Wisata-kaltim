<x-layouts.user>
    <div class="min-h-screen bg-gradient-to-b from-gray-50 to-gray-100 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Back Button --}}
            <div class="mb-6">
                <a href="{{ route('kota.index') }}"
                    class="inline-flex items-center text-[#8B6F47] hover:text-[#D4AF37] transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali ke Daftar Kota
                </a>
            </div>

            {{-- City Header --}}
            <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-12">
                <div class="h-96 overflow-hidden relative">
                    <img src="{{ asset('images/seed/kota/' . $kota->image) }}" alt="{{ $kota->name }}"
                        class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent flex items-end">
                        <div class="p-8 w-full">
                            <h1 class="text-5xl font-bold text-white mb-2">{{ $kota->name }}</h1>
                            <p class="text-gray-200 text-lg flex items-center">
                                <i class="fas fa-compass text-[#D4AF37] mr-2"></i>
                                {{ $kota->wisatas->count() }} Tempat Wisata
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Wisata Section --}}
            @if ($kota->wisatas->count() > 0)
                <div class="mb-16" x-data="{ selectedWisata: null }">
                    <h2 class="text-3xl font-bold text-gray-800 mb-8 flex items-center">
                        <i class="fas fa-star text-yellow-500 mr-3"></i>
                        Tempat Wisata di {{ $kota->name }}
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($kota->wisatas as $wisata)
                            <div
                                class="bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-200 group">
                                <div class="h-48 overflow-hidden">
                                    <img src="{{ asset('images/seed/wisata/' . ($wisata->gambar ?? 'placeholder.jpg')) }}"
                                        alt="{{ $wisata->name }}"
                                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                </div>
                                <div class="p-5">
                                    <div class="flex items-center justify-between mb-2">
                                        <h3
                                            class="text-lg font-bold text-gray-800 line-clamp-2 group-hover:text-[#8B6F47] transition-colors flex-1">
                                            {{ $wisata->name }}
                                        </h3>
                                        @if ($wisata->ulasans->avg('rating'))
                                            <div class="flex items-center ml-2">
                                                <i class="fas fa-star text-yellow-400 text-sm mr-1"></i>
                                                <span
                                                    class="text-sm font-semibold">{{ number_format($wisata->ulasans->avg('rating'), 1) }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    <p class="text-gray-600 text-sm line-clamp-3 mb-4">
                                        {{ Str::limit(strip_tags($wisata->description ?? ''), 100) }}
                                    </p>
                                    @if ($wisata->address)
                                        <div class="text-xs text-gray-500 mb-4 flex items-start">
                                            <i class="fas fa-map-pin text-[#8B6F47] mr-2 mt-0.5 flex-shrink-0"></i>
                                            <span class="line-clamp-2">{{ $wisata->alamat }}</span>
                                        </div>
                                    @endif
                                    <a href="{{ route('wisata.detail', $wisata->slug) }}"
                                        class="block w-full bg-gradient-to-r from-[#8B6F47] to-[#D4AF37] text-white py-2 rounded-lg font-semibold hover:shadow-lg transition-all duration-300 text-sm text-center">
                                        Lihat Detail & Ulasan
                                    </a>
                                </div>
                            </div>

                            {{-- Wisata Detail Modal --}}
                            <div x-show="selectedWisata === {{ $wisata->id }}" x-cloak
                                class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
                                @click.self="selectedWisata = null"
                                x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                @keydown.escape.window="selectedWisata = null">
                                <div
                                    class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
                                    {{-- Modal Header --}}
                                    <div
                                        class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex justify-between items-center z-10">
                                        <h3 class="text-2xl font-bold text-gray-800">{{ $wisata->name }}</h3>
                                        <button @click="selectedWisata = null"
                                            class="text-gray-400 hover:text-gray-600 text-2xl">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>

                                    {{-- Modal Content --}}
                                    <div class="p-6">
                                        {{-- Wisata Image --}}
                                        <img src="{{ asset('images/seed/wisata/' . $wisata->image ?? 'images/placeholder.jpg') }}"
                                            alt="{{ $wisata->name }}" class="w-full h-64 object-cover rounded-xl mb-6">

                                        {{-- Wisata Info --}}
                                        <div class="mb-6">
                                            <p class="text-gray-700 mb-4">{{ $wisata->description }}</p>
                                            @if ($wisata->address)
                                                <div class="flex items-start text-gray-600 mb-2">
                                                    <i class="fas fa-map-pin text-[#8B6F47] mr-2 mt-1"></i>
                                                    <span>{{ $wisata->address }}</span>
                                                </div>
                                            @endif
                                            @if ($wisata->ticket_price)
                                                <div class="flex items-center text-gray-600">
                                                    <i class="fas fa-ticket-alt text-[#8B6F47] mr-2"></i>
                                                    <span>Rp
                                                        {{ number_format($wisata->ticket_price, 0, ',', '.') }}</span>
                                                </div>
                                            @endif
                                        </div>

                                        {{-- Ulasan Section --}}
                                        <div class="border-t border-gray-200 pt-6" x-data="{ showReviewForm: false }">
                                            <h4
                                                class="text-xl font-bold text-gray-800 mb-4 flex items-center justify-between">
                                                <span>
                                                    <i class="fas fa-comments text-[#8B6F47] mr-2"></i>
                                                    Ulasan
                                                </span>
                                                <span
                                                    class="text-base text-gray-600">({{ $wisata->ulasans->count() }})</span>
                                            </h4>

                                            {{-- Review Form Toggle --}}
                                            <button @click="showReviewForm = !showReviewForm"
                                                class="mb-4 bg-gradient-to-r from-[#8B6F47] to-[#D4AF37] text-white px-4 py-2 rounded-lg font-semibold hover:shadow-lg transition-all text-sm">
                                                <i class="fas fa-pen mr-2"></i>
                                                <span x-text="showReviewForm ? 'Tutup' : 'Tulis Ulasan'"></span>
                                            </button>

                                            {{-- Review Form --}}
                                            <div x-show="showReviewForm" x-transition
                                                class="bg-gray-50 rounded-lg p-4 mb-4">
                                                <form action="{{ route('ulasan.store') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="reviewable_type"
                                                        value="App\Models\Wisata">
                                                    <input type="hidden" name="reviewable_id"
                                                        value="{{ $wisata->id }}">

                                                    <div class="mb-3">
                                                        <label
                                                            class="block text-gray-700 font-semibold mb-2 text-sm">Rating</label>
                                                        <div class="flex gap-2" x-data="{ rating: 0 }">
                                                            @for ($i = 1; $i <= 5; $i++)
                                                                <button type="button"
                                                                    @click="rating = {{ $i }}"
                                                                    class="text-2xl transition-colors">
                                                                    <i class="fas fa-star"
                                                                        :class="rating >= {{ $i }} ?
                                                                            'text-yellow-400' : 'text-gray-300'"></i>
                                                                </button>
                                                            @endfor
                                                            <input type="hidden" name="rating" :value="rating"
                                                                required>
                                                        </div>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label
                                                            class="block text-gray-700 font-semibold mb-2 text-sm">Komentar</label>
                                                        <textarea name="komentar" rows="3"
                                                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#8B6F47] focus:border-transparent"
                                                            placeholder="Tulis komentar..." required></textarea>
                                                    </div>

                                                    <button type="submit"
                                                        class="bg-gradient-to-r from-[#8B6F47] to-[#D4AF37] text-white px-4 py-2 rounded-lg font-semibold hover:shadow-lg transition-all text-sm">
                                                        <i class="fas fa-paper-plane mr-2"></i>
                                                        Kirim
                                                    </button>
                                                </form>
                                            </div>

                                            {{-- Reviews List --}}
                                            <div class="space-y-3 max-h-96 overflow-y-auto">
                                                @forelse ($wisata->ulasans->take(10) as $ulasan)
                                                    <div class="bg-gray-50 rounded-lg p-4">
                                                        <div class="flex items-start justify-between mb-2">
                                                            <div class="flex items-center">
                                                                <div
                                                                    class="w-8 h-8 bg-gradient-to-br from-[#8B6F47] to-[#D4AF37] rounded-full flex items-center justify-center text-white font-bold mr-2 text-sm">
                                                                    {{ strtoupper(substr($ulasan->user->name, 0, 1)) }}
                                                                </div>
                                                                <div>
                                                                    <p class="font-semibold text-gray-800 text-sm">
                                                                        {{ $ulasan->user->name }}</p>
                                                                    <p class="text-xs text-gray-500">
                                                                        {{ $ulasan->created_at->diffForHumans() }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="flex items-center gap-1">
                                                                @for ($i = 1; $i <= 5; $i++)
                                                                    <i
                                                                        class="fas fa-star text-xs {{ $i <= $ulasan->rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                                                @endfor
                                                            </div>
                                                        </div>
                                                        <p class="text-gray-700 text-sm">{{ $ulasan->komentar }}</p>
                                                    </div>
                                                @empty
                                                    <div class="bg-gray-50 rounded-lg p-6 text-center">
                                                        <i
                                                            class="fas fa-comment-slash text-gray-400 text-3xl mb-2"></i>
                                                        <p class="text-gray-600 text-sm">Belum ada ulasan</p>
                                                    </div>
                                                @endforelse
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="bg-white rounded-xl shadow-md p-12 text-center mb-12">
                    <i class="fas fa-inbox text-gray-400 text-5xl mb-4"></i>
                    <p class="text-gray-600 text-lg">Tidak ada tempat wisata tersedia untuk kota ini</p>
                </div>
            @endif

            {{-- Related Articles Section --}}
            @if ($artikelTerkait->count() > 0)
                <div>
                    <h2 class="text-3xl font-bold text-gray-800 mb-8 flex items-center">
                        <i class="fas fa-newspaper text-[#8B6F47] mr-3"></i>
                        Berita Wisata tentang {{ $kota->name }}
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($artikelTerkait as $artikel)
                            <a href="{{ route('artikel.detail', $artikel->slug) }}" class="group">
                                <div
                                    class="bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-200 h-full flex flex-col">
                                    <div class="h-48 overflow-hidden">
                                        <img src="{{ asset($artikel->thumbnail) }}" alt="{{ $artikel->judul }}"
                                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                    </div>
                                    <div class="p-5 flex flex-col flex-grow">
                                        <h3
                                            class="text-lg font-bold text-gray-800 mb-2 line-clamp-2 group-hover:text-[#8B6F47] transition-colors">
                                            {{ $artikel->judul }}
                                        </h3>
                                        <p class="text-gray-600 text-sm line-clamp-3 mb-4 flex-grow">
                                            {{ Str::limit(strip_tags(is_array($artikel->isi) ? implode(' ', $artikel->isi) : $artikel->isi), 100) }}
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
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-layouts.user>

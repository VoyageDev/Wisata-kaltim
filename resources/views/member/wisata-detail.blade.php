<x-layouts.user>
    <div class="min-h-screen bg-gradient-to-b from-gray-50 to-gray-100">
        {{-- Hero Section dengan Breadcrumb --}}
        <div class="bg-gradient-to-r from-[#2C3E50] to-[#1A3C34] text-white py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <nav class="text-sm mb-4">
                    <ol class="list-none p-0 inline-flex">
                        <li class="flex items-center">
                            <a href="{{ route('home') }}" class="hover:text-gray-200">
                                <i class="fas fa-home mr-2"></i>Home
                            </a>
                            <i class="fas fa-chevron-right mx-2"></i>
                        </li>
                        <li class="flex items-center">
                            @if ($wisata->kota)
                                <a href="{{ route('kota.detail', $wisata->kota->slug) }}" class="hover:text-gray-200">
                                    {{ $wisata->kota->name }}
                                </a>
                            @endif
                            <i class="fas fa-chevron-right mx-2"></i>
                        </li>
                        <li class="text-gray-200">{{ $wisata->name }}</li>
                    </ol>
                </nav>
                <h1 class="text-4xl font-bold">{{ $wisata->name }}</h1>
            </div>
        </div>

        {{-- Main Content --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            {{-- Layout Kiri Kanan --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">

                {{-- Kiri - Gambar Wisata --}}
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <img src="{{ asset($wisata->gambar) }}" alt="{{ $wisata->name }}"
                        class="w-full h-[500px] object-cover">
                </div>

                {{-- Kanan - Detail Info WIsata --}}
                <div class="bg-white rounded-xl shadow-lg p-8 relative">
                    <div class="space-y-6">
                        {{-- Status --}}
                        <div class="flex items-center space-x-3">
                            <span
                                class="px-4 py-2 rounded-full text-sm font-semibold {{ $wisata->status === 'Open' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                <i class="fas fa-circle text-xs mr-1"></i>{{ $wisata->status }}
                            </span>
                            @if ($wisata->kota)
                                <span class="text-gray-500 text-sm">
                                    <i class="fas fa-map-marker-alt text-[#2150ea] mr-1"></i>
                                    {{ $wisata->kota->name }}
                                </span>
                            @endif
                        </div>

                        {{-- Google Maps Icon (pojok kanan atas) --}}
                        <a href="{{ $wisata->links_maps }}" target="_blank" title="Lihat di Google Maps"
                            class="absolute top-0 right-8 flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white w-12 h-12 rounded-lg transition-all duration-200 shadow-md hover:shadow-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-map-pin">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M9 11a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" />
                                <path
                                    d="M17.657 16.657l-4.243 4.243a2 2 0 0 1 -2.827 0l-4.244 -4.243a8 8 0 1 1 11.314 0" />
                            </svg>
                        </a>

                        {{-- Deskripsi --}}
                        <div>
                            <h3 class="text-lg font-bold text-gray-800 mb-2 flex items-center">
                                <i class="fas fa-info-circle text-[#acaaaa] mr-2"></i>Deskripsi
                            </h3>
                            <p class="text-gray-600 leading-relaxed">{{ $wisata->description }}</p>
                        </div>

                        {{-- Alamat --}}
                        <div>
                            <h3 class="text-lg font-bold text-gray-800 mb-2 flex items-center">
                                <i class="fas fa-location-dot text-[#2150ea] mr-2"></i>Alamat
                            </h3>
                            <p class="text-gray-600">{{ $wisata->alamat }}</p>
                        </div>

                        {{-- Jam Operasional --}}
                        <div class="flex items-center space-x-8">
                            <div>
                                <h3 class="text-sm font-semibold text-gray-500 mb-1">Jam Buka</h3>
                                <p class="text-lg font-bold text-[#252525]">
                                    <i
                                        class="fas fa-clock mr-1"></i>{{ \Carbon\Carbon::parse($wisata->jam_buka)->format('H:i') }}
                                </p>
                            </div>
                            <div class="h-12 w-px bg-gray-300"></div>
                            <div>
                                <h3 class="text-sm font-semibold text-gray-500 mb-1">Jam Tutup</h3>
                                <p class="text-lg font-bold text-red-600">
                                    <i
                                        class="fas fa-clock mr-1"></i>{{ \Carbon\Carbon::parse($wisata->jam_tutup)->format('H:i') }}
                                </p>
                            </div>
                        </div>

                        {{-- Harga Tiket --}}
                        <div>
                            <h3 class="text-sm font-semibold text-gray-500 mb-1">Harga Tiket</h3>
                            <p class="text-2xl font-bold text-green-600">
                                Rp {{ number_format($wisata->harga_tiket, 0, ',', '.') }}
                                <span class="text-sm font-normal text-gray-600">/ orang</span>
                            </p>
                        </div>

                        {{-- Links Maps & Booking --}}
                        <div class="pt-4 border-t border-gray-200 space-y-3">
                            {{-- Booking Button (di ATAS) --}}
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2 w-full">
                                    <a href="" target="_blank" title="Pesan Tiket"
                                        class="flex-1 flex items-center justify-center gap-2 bg-gradient-to-r from-green-600 to-green-700 hover:shadow-lg text-white px-6 py-3 rounded-lg font-semibold transition-all duration-200">
                                        <i class="fas fa-ticket"></i>
                                        <span>Booking Sekarang</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Section Komentar --}}
            <div class="bg-white rounded-xl shadow-lg p-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-comments text-[#c4c0ba] mr-3"></i>
                    Ulasan & Komentar ({{ $wisata->ulasans->count() }})
                </h2>

                {{-- Form Komentar --}}
                <div class="mb-8">
                    @if (session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
                            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('ulasan.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="reviewable_type" value="App\Models\Wisata">
                        <input type="hidden" name="reviewable_id" value="{{ $wisata->id }}">

                        {{-- Rating --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Rating</label>
                            <div class="flex gap-2">
                                @for ($i = 1; $i <= 5; $i++)
                                    <button type="button" onclick="setRating({{ $i }})"
                                        id="rating-btn-{{ $i }}"
                                        class="text-3xl transition-colors hover:scale-110">
                                        <i class="fas fa-star text-gray-300" id="rating-icon-{{ $i }}"></i>
                                    </button>
                                @endfor
                                <input type="hidden" name="rating" id="rating-input" required>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Tulis Ulasan Anda</label>
                            <textarea name="komentar" rows="4" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#8B6F47] focus:border-transparent resize-none"
                                placeholder="Bagikan pengalaman Anda tentang {{ $wisata->name }}..."></textarea>
                        </div>

                        <button type="submit"
                            class="bg-gradient-to-r from-[#16A34A] to-[#15803D] text-white px-6 py-3 rounded-lg font-semibold hover:shadow-lg transition-all duration-200">
                            <i class="fas fa-paper-plane mr-2"></i>Kirim Ulasan
                        </button>
                    </form>
                </div>

                {{-- Daftar Komentar --}}
                <div class="space-y-6">
                    @forelse($wisata->ulasans as $ulasan)
                        {{-- Parent Comment --}}
                        <div class="border-b border-gray-200 pb-6 last:border-0">
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0">
                                    <div
                                        class="w-12 h-12 rounded-full bg-gradient-to-br from-[#8B6F47] to-[#D4AF37] flex items-center justify-center text-white font-bold text-lg">
                                        {{ strtoupper(substr($ulasan->user->name, 0, 1)) }}
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between mb-2">
                                        <div>
                                            <h4 class="font-semibold text-gray-800">{{ $ulasan->user->name }}</h4>
                                            <div class="flex items-center space-x-2">
                                                <p class="text-xs text-gray-500">
                                                    {{ $ulasan->created_at->diffForHumans() }}
                                                </p>
                                                @if ($ulasan->rating)
                                                    <div class="flex items-center">
                                                        @for ($i = 1; $i <= 5; $i++)
                                                            <i
                                                                class="fas fa-star text-xs {{ $i <= $ulasan->rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                                        @endfor
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        @if (auth()->id() === $ulasan->user_id)
                                            <form action="{{ route('ulasan.destroy', $ulasan->id) }}" method="POST"
                                                onsubmit="return confirm('Yakin ingin menghapus ulasan ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="text-red-500 hover:text-red-700 text-sm">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                    <p class="text-gray-700 leading-relaxed">{{ $ulasan->komentar }}</p>

                                    {{-- Reply Button --}}
                                    <button onclick="toggleReplyForm({{ $ulasan->id }})"
                                        class="mt-3 text-sm text-[#8B6F47] hover:text-[#D4AF37] font-medium">
                                        <i class="fas fa-reply mr-1"></i>Balas {{ $ulasan->user->name }}
                                    </button>

                                    {{-- Reply Form --}}
                                    <div id="reply-form-{{ $ulasan->id }}" class="hidden mt-4">
                                        <form action="{{ route('ulasan.store') }}" method="POST" class="space-y-3">
                                            @csrf
                                            <input type="hidden" name="reviewable_type" value="App\Models\Wisata">
                                            <input type="hidden" name="reviewable_id" value="{{ $wisata->id }}">
                                            <input type="hidden" name="parent_id" value="{{ $ulasan->id }}">

                                            <textarea name="komentar" rows="2" required
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#8B6F47] focus:border-transparent resize-none text-sm"
                                                placeholder="Tulis balasan untuk {{ $ulasan->user->name }}..."></textarea>

                                            <div class="flex space-x-2">
                                                <button type="submit"
                                                    class="bg-[#22C55E] text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-[#15803D] transition">
                                                    Kirim
                                                </button>
                                                <button type="button" onclick="toggleReplyForm({{ $ulasan->id }})"
                                                    class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-gray-400 transition">
                                                    Batal
                                                </button>
                                            </div>
                                        </form>
                                    </div>

                                    {{-- Replies (Collapsible) - Show all replies that directly or indirectly reply to this comment --}}
                                    @php
                                        // Get all reply IDs of this comment
                                        $directReplies = $allReplies
                                            ->where('parent_id', $ulasan->id)
                                            ->pluck('id')
                                            ->toArray();
                                        // Get all nested replies (replies to replies)
                                        $nestedReplies = $allReplies
                                            ->whereIn('parent_id', $directReplies)
                                            ->pluck('id')
                                            ->toArray();
                                        // Combine all relevant replies
                                        $allRelatedReplies = array_merge($directReplies, $nestedReplies);
                                        $repliesToShow = $allReplies->whereIn('id', $allRelatedReplies);
                                    @endphp
                                    @if ($repliesToShow->count() > 0)
                                        <div class="mt-4">
                                            {{-- Toggle Button for Replies --}}
                                            <button onclick="toggleReplies({{ $ulasan->id }})"
                                                class="flex items-center gap-2 text-sm text-[#8B6F47] hover:text-[#D4AF37] font-medium mb-3 transition">
                                                <i id="reply-icon-{{ $ulasan->id }}"
                                                    class="fas fa-chevron-down"></i>
                                                <span id="reply-text-{{ $ulasan->id }}">Tampilkan
                                                    {{ $repliesToShow->count() }} balasan</span>
                                            </button>

                                            {{-- Replies Container (Initially Hidden) --}}
                                            <div id="replies-container-{{ $ulasan->id }}" class="hidden space-y-3">
                                                @foreach ($repliesToShow as $reply)
                                                    @include('member.partials.reply-comment', [
                                                        'reply' => $reply,
                                                        'reviewableType' => 'App\Models\Wisata',
                                                        'reviewableId' => $wisata->id,
                                                    ])
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <i class="fas fa-comments text-gray-300 text-6xl mb-4"></i>
                            <p class="text-gray-500 text-lg">Belum ada ulasan. Jadilah yang pertama!</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleReplyForm(id) {
            var form = document.getElementById('reply-form-' + id);
            if (form.classList.contains('hidden')) {
                form.classList.remove('hidden');
            } else {
                form.classList.add('hidden');
            }
        }

        function toggleReplies(commentId) {
            var container = document.getElementById('replies-container-' + commentId);
            var icon = document.getElementById('reply-icon-' + commentId);
            var text = document.getElementById('reply-text-' + commentId);
            var replyCount = container.children.length;

            if (container.classList.contains('hidden')) {
                // Show replies
                container.classList.remove('hidden');
                icon.classList.remove('fa-chevron-down');
                icon.classList.add('fa-chevron-up');
                text.textContent = 'Sembunyikan ' + replyCount + ' balasan';
            } else {
                // Hide replies
                container.classList.add('hidden');
                icon.classList.remove('fa-chevron-up');
                icon.classList.add('fa-chevron-down');
                text.textContent = 'Tampilkan ' + replyCount + ' balasan';
            }
        }

        function setRating(rating) {
            document.getElementById('rating-input').value = rating;
            for (let i = 1; i <= 5; i++) {
                var icon = document.getElementById('rating-icon-' + i);
                if (i <= rating) {
                    icon.classList.remove('text-gray-300');
                    icon.classList.add('text-yellow-400');
                } else {
                    icon.classList.remove('text-yellow-400');
                    icon.classList.add('text-gray-300');
                }
            }
        }
    </script>
</x-layouts.user>

<x-layouts.user>
    <div class="min-h-screen bg-gradient-to-b from-gray-50 to-gray-100 py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Back Button --}}
            <div class="mb-6">
                <a href="{{ route('artikel.index') }}"
                    class="inline-flex items-center text-[#8B6F47] hover:text-[#D4AF37] transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali ke Berita
                </a>
            </div>

            {{-- Main Article --}}
            <article class="bg-white rounded-xl shadow-lg overflow-hidden mb-12">
                {{-- Featured Image --}}
                <div class="h-96 overflow-hidden">
                    <img src="{{ asset($artikel->thumbnail) }}" alt="{{ $artikel->judul }}"
                        class="w-full h-full object-cover">
                </div>

                {{-- Article Content --}}
                <div class="p-8 md:p-12">
                    {{-- Metadata --}}
                    <div
                        class="flex flex-wrap items-center gap-4 text-sm text-gray-600 mb-6 pb-6 border-b border-gray-200">
                        @if ($artikel->wisata && $artikel->wisata->kota)
                            <span class="flex items-center">
                                <i class="fas fa-map-marker-alt text-[#8B6F47] mr-2"></i>
                                {{ $artikel->wisata->kota->name }}
                            </span>
                        @endif
                        <span class="flex items-center">
                            <i class="fas fa-calendar text-[#8B6F47] mr-2"></i>
                            {{ $artikel->created_at->format('d F Y') }}
                        </span>
                        <span class="flex items-center">
                            <i class="fas fa-eye text-[#8B6F47] mr-2"></i>
                            {{ number_format($artikel->views) }} views
                        </span>
                    </div>

                    {{-- Title --}}
                    <h1 class="text-4xl font-bold text-gray-800 mb-6">
                        {{ $artikel->judul }}
                    </h1>

                    {{-- Author Info --}}
                    <div class="flex items-center mb-8 pb-8 border-b border-gray-200">
                        <div>
                            <p class="text-gray-500 text-sm">Ditulis oleh</p>
                            <p class="font-semibold text-gray-800">{{ $artikel->user->name }}</p>
                        </div>
                    </div>

                    {{-- Article Body --}}
                    <div class="prose prose-lg max-w-none mb-8 text-gray-700 leading-relaxed">
                        {!! $artikel->isi !!}
                    </div>

                    {{-- API Source Info (if exists) --}}
                    @if ($artikel->api_source)
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-8">
                            <p class="text-sm text-gray-600">
                                <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                                Artikel ini bersumber dari <strong>{{ $artikel->api_source }}</strong>
                            </p>
                        </div>
                    @endif
                </div>
            </article>

            {{-- Ulasan/Reviews Section --}}
            <div class="mb-12 bg-white rounded-xl shadow-lg p-8" x-data="reviewStore()">
                <h2 class="text-3xl font-bold text-gray-800 mb-8 flex items-center justify-between">
                    <span>
                        <i class="fas fa-comments text-[#383838] mr-3"></i>
                        Ulasan & Komentar
                    </span>
                    <span class="text-lg text-gray-600">({{ $artikel->ulasans->count() }})</span>
                </h2>

                {{-- Review Form Toggle Button --}}
                <button @click="showReviewForm = !showReviewForm"
                    class="mb-6 bg-gradient-to-r from-[#4174e1] to-[#1D4ED8] text-white px-6 py-3 rounded-lg font-semibold hover:shadow-lg transition-all duration-300">
                    <i class="fas fa-pen mr-2"></i>
                    <span x-text="showReviewForm ? 'Tutup Form' : 'Tulis Ulasan'"></span>
                </button>

                {{-- Review Form --}}
                <div x-show="showReviewForm" x-transition class="bg-white rounded-xl shadow-lg p-6 mb-8">
                    <form action="{{ route('ulasan.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="reviewable_type" value="App\Models\Artikel">
                        <input type="hidden" name="reviewable_id" value="{{ $artikel->id }}">

                        {{-- Rating --}}
                        <div class="mb-4">
                            <label class="block text-gray-700 font-semibold mb-2">Rating</label>
                            <div class="flex gap-2" x-data="{ rating: 0 }">
                                @for ($i = 1; $i <= 5; $i++)
                                    <button type="button" @click="rating = {{ $i }}"
                                        class="text-3xl transition-colors">
                                        <i class="fas fa-star"
                                            :class="rating >= {{ $i }} ? 'text-yellow-400' : 'text-gray-300'"></i>
                                    </button>
                                @endfor
                                <input type="hidden" name="rating" :value="rating" required>
                            </div>
                        </div>


                        {{-- Komentar --}}
                        <div class="mb-4">
                            <label for="komentar" class="block text-gray-700 font-semibold mb-2">Komentar</label>
                            <textarea name="komentar" id="komentar" rows="4"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#8B6F47] focus:border-transparent"
                                placeholder="Tulis komentar Anda..." required></textarea>
                        </div>

                        <button type="submit"
                            class="bg-gradient-to-r from-[#16A34A] to-[#15803D] text-white px-6 py-2 rounded-lg font-semibold hover:shadow-lg transition-all duration-300">
                            <i class="fas fa-paper-plane mr-2"></i>
                            Kirim Ulasan
                        </button>
                    </form>
                </div>

                {{-- Reviews List --}}
                <div id="ulasan-container" class="space-y-6">
                    @forelse($artikel->ulasans as $ulasan)
                        {{-- Parent Comment --}}
                        <div class="border-b border-gray-200 pb-6 last:border-0">
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0">
                                    <div
                                        class="w-12 h-12 rounded-full bg-gradient-to-br from-[#8c8c8c] to-[#000000] flex items-center justify-center text-white font-bold text-lg">
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
                                                <button type="submit" class="text-red-500 hover:text-red-700 text-sm">
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
                                            <input type="hidden" name="reviewable_type" value="App\Models\Artikel">
                                            <input type="hidden" name="reviewable_id" value="{{ $artikel->id }}">
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
                                                        'reviewableType' => 'App\Models\Artikel',
                                                        'reviewableId' => $artikel->id,
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

            {{-- Related Articles Section --}}
            @if ($artikelTerkait->count() > 0)
                <div class="mb-12">
                    <h2 class="text-3xl font-bold text-gray-800 mb-8 flex items-center">
                        <i class="fas fa-link text-[#8B6F47] mr-3"></i>
                        Berita Lainnya
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($artikelTerkait as $terkait)
                            <div
                                class="bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-200 group">
                                <div class="h-48 overflow-hidden">
                                    <img src="{{ asset($terkait->thumbnail) }}" alt="{{ $terkait->judul }}"
                                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                </div>
                                <div class="p-5">
                                    <div class="flex items-center gap-3 text-xs text-gray-600 mb-3">
                                        @if ($terkait->wisata && $terkait->wisata->kota)
                                            <span class="flex items-center">
                                                <i class="fas fa-map-marker-alt text-[#8B6F47] mr-1"></i>
                                                {{ $terkait->wisata->kota->name }}
                                            </span>
                                        @endif
                                        <span class="flex items-center">
                                            <i class="fas fa-eye text-[#8B6F47] mr-1"></i>
                                            {{ number_format($terkait->views) }}
                                        </span>
                                        <span class="flex items-center">
                                            <i class="fas fa-comments text-[#8B6F47] mr-1"></i>
                                            {{ $terkait->ulasans_count ?? 0 }}
                                        </span>
                                    </div>
                                    <h3 class="text-lg font-bold text-gray-800 mb-2 line-clamp-2">
                                        <a href="{{ route('artikel.detail', $terkait->slug) }}"
                                            class="hover:text-[#8B6F47] transition-colors">
                                            {{ $terkait->judul }}
                                        </a>
                                    </h3>
                                    <p class="text-gray-600 text-sm line-clamp-3 mb-3">
                                        {{ Str::limit(strip_tags($terkait->isi), 100) }}
                                    </p>
                                    <div class="flex items-center justify-between text-xs text-gray-500">
                                        <span class="flex items-center">
                                            <i class="fas fa-user-circle mr-1"></i>
                                            {{ Str::limit($terkait->user->name, 15) }}
                                        </span>
                                        <span>{{ $terkait->created_at->format('d M Y') }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </div>

    <script>
        function reviewStore() {
            return {
                showReviewForm: false,
                showReplyForm: null,
                offset: 3,
                hasMore: {{ $artikel->ulasans->count() > 3 ? 'true' : 'false' }},
                loading: false,
                loadMoreUlasan() {
                    this.loading = true;
                    const url =
                        `{{ route('ulasan.loadMore') }}?reviewable_type=App%5CModels%5CArtikel&reviewable_id={{ $artikel->id }}&offset=${this.offset}`;
                    fetch(url)
                        .then(response => response.json())
                        .then(data => {
                            document.getElementById('ulasan-container').insertAdjacentHTML('beforeend', data.html);
                            this.offset += 5;
                            this.hasMore = data.hasMore;
                            this.loading = false;
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            this.loading = false;
                        });
                }
            };
        }

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
    </script>

    <script>
        // {{-- Related Articles Section --}}
        @if ($artikelTerkait->count() > 0)
            <
            div class = "mb-12" >
            <
            h2 class = "text-3xl font-bold text-gray-800 mb-8 flex items-center" >
            <
            i class = "fas fa-link text-[#8B6F47] mr-3" > < /i>
            Artikel Terkait
                <
                /h2>

                <
                div class = "grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" >
                @foreach ($artikelTerkait as $terkait)
                    <
                    div class =
                    "bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-200 group" >
                    <
                    div class = "h-48 overflow-hidden" >
                    <
                    img src = "{{ asset($terkait->thumbnail) }}"
                    alt = "{{ $terkait->judul }}"
                    class = "w-full h-full object-cover group-hover:scale-110 transition-transform duration-300" >
                    <
                    /div> <
                    div class = "p-5" >
                    <
                    div class = "flex items-center gap-3 text-xs text-gray-600 mb-3" >
                    <
                    span class = "flex items-center" >
                    <
                    i class = "fas fa-map-marker-alt text-[#8B6F47] mr-1" > < /i>
                    @if ($terkait->wisata && $terkait->wisata->kota)
                        {{ $terkait->wisata->kota->name }}
                    @endif <
                    /span> <
                    span class = "flex items-center" >
                    <
                    i class = "fas fa-eye text-[#8B6F47] mr-1" > < /i>
                    {{ number_format($terkait->views) }}
                        <
                        /span> <
                    span class = "flex items-center" >
                    <
                    i class = "fas fa-comments text-[#8B6F47] mr-1" > < /i>
                    {{ $terkait->ulasans_count ?? 0 }}
                        <
                        /span> < /
                    div > <
                        h3 class = "text-lg font-bold text-gray-800 mb-2 line-clamp-2" >
                        <
                        a href = "{{ route('artikel.detail', $terkait->slug) }}"
                    class = "hover:text-[#8B6F47] transition-colors" >
                    {{ $terkait->judul }}
                        <
                        /a> < /
                    h3 > <
                        p class = "text-gray-600 text-sm line-clamp-3 mb-3" >
                        {{ Str::limit(strip_tags($terkait->isi), 100) }} <
                        /p> <
                    div class = "flex items-center justify-between text-xs text-gray-500" >
                    <
                    span class = "flex items-center" >
                    <
                    i class = "fas fa-user-circle mr-1" > < /i>
                    {{ Str::limit($terkait->user->name, 15) }}
                        <
                        /span> <
                    span > {{ $terkait->created_at->format('d M Y') }} < /span> < /
                    div > <
                        /div> < /
                    div >
                @endforeach <
                /div> < /
            div >
        @endif

        <
        /div> < /
        div >

            <
            script >
            function reviewStore() {
                return {
                    showReviewForm: false,
                    showReplyForm: null,
                    offset: 3,
                    hasMore: {{ $artikel->ulasans->count() > 3 ? 'true' : 'false' }},
                    loading: false,
                    loadMoreUlasan() {
                        this.loading = true;
                        const url =
                            `{{ route('ulasan.loadMore') }}?reviewable_type=App%5CModels%5CArtikel&reviewable_id={{ $artikel->id }}&offset=${this.offset}`;
                        fetch(url)
                            .then(response => response.json())
                            .then(data => {
                                document.getElementById('ulasan-container').insertAdjacentHTML('beforeend', data.html);
                                this.offset += 5;
                                this.hasMore = data.hasMore;
                                this.loading = false;
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                this.loading = false;
                            });
                    }
                };
            }
    </script>
</x-layouts.user>

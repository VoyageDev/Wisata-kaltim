@props(['comment', 'artikel', 'depth' => 0])

@if ($depth === 0)
    {{-- Parent Comment --}}
    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
        <div class="flex items-start justify-between mb-3">
            <div class="flex items-center">
                <div
                    class="w-10 h-10 bg-gradient-to-br from-[#8B6F47] to-[#D4AF37] rounded-full flex items-center justify-center text-white font-bold mr-3">
                    {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                </div>
                <div>
                    <p class="font-semibold text-gray-800">{{ $comment->user->name }}</p>
                    <p class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</p>
                </div>
            </div>
            @if ($comment->rating)
                <div class="flex items-center gap-1">
                    @for ($i = 1; $i <= 5; $i++)
                        <i
                            class="fas fa-star text-sm {{ $i <= $comment->rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                    @endfor
                </div>
            @endif
        </div>

        <p class="text-gray-700 mb-4">{{ $comment->komentar }}</p>

        {{-- Reply Button --}}
        <button @click="showReplyForm = showReplyForm === {{ $comment->id }} ? null : {{ $comment->id }}"
            class="text-[#8B6F47] text-sm hover:text-[#D4AF37] transition-colors">
            <i class="fas fa-reply mr-1"></i>Balas
        </button>

        {{-- Reply Form --}}
        <div x-show="showReplyForm === {{ $comment->id }}" x-transition
            class="mt-4 bg-gray-50 rounded-lg p-4 border-l-4 border-[#8B6F47]">
            <form action="{{ route('ulasan.store') }}" method="POST">
                @csrf
                <input type="hidden" name="reviewable_type" value="App\Models\Artikel">
                <input type="hidden" name="reviewable_id" value="{{ $artikel->id }}">
                <input type="hidden" name="parent_id" value="{{ $comment->id }}">

                <div class="mb-3">
                    <label for="reply-{{ $comment->id }}" class="block text-gray-700 font-semibold mb-2 text-sm">
                        Balas ke {{ $comment->user->name }}
                    </label>
                    <textarea name="komentar" id="reply-{{ $comment->id }}" rows="3"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#8B6F47] focus:border-transparent text-sm"
                        placeholder="Tulis balasan Anda..." required></textarea>
                </div>

                <div class="flex gap-2">
                    <button type="submit"
                        class="bg-gradient-to-r from-[#8B6F47] to-[#D4AF37] text-white px-4 py-2 rounded-lg font-semibold hover:shadow-lg transition-all duration-300 text-sm">
                        <i class="fas fa-paper-plane mr-1"></i>
                        Kirim
                    </button>
                    <button type="button" @click="showReplyForm = null"
                        class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-semibold hover:bg-gray-300 transition-all duration-300 text-sm">
                        Batal
                    </button>
                </div>
            </form>
        </div>

        {{-- Nested Replies (Thread Style) --}}
        @if ($comment->replies->count() > 0)
            <div class="mt-6 pl-8 border-l-2 border-gray-300">
                @foreach ($comment->replies as $reply)
                    <x-thread-reply :comment="$reply" :artikel="$artikel" />
                @endforeach
            </div>
        @endif
    </div>
@endif

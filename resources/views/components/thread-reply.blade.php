@props(['comment', 'artikel'])

<div class="mb-4">
    <div class="flex gap-4">
        {{-- Thread Line --}}
        <div class="flex flex-col items-center">
            <div class="w-0.5 h-8 bg-gray-300"></div>
        </div>

        {{-- Reply Content --}}
        <div class="flex-1">
            <div class="bg-transparent border-l-2 border-gray-300 pl-4">
                <div class="flex items-start justify-between mb-2">
                    <div class="flex items-center">
                        <div
                            class="w-8 h-8 bg-gradient-to-br from-[#8B6F47] to-[#D4AF37] rounded-full flex items-center justify-center text-white font-bold text-xs mr-2">
                            {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800 text-sm">{{ $comment->user->name }}</p>
                            <p class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>

                <p class="text-gray-700 text-sm mb-3">{{ $comment->komentar }}</p>

                {{-- Reply Button --}}
                <button @click="showReplyForm = showReplyForm === {{ $comment->id }} ? null : {{ $comment->id }}"
                    class="text-[#8B6F47] text-xs hover:text-[#D4AF37] transition-colors">
                    <i class="fas fa-reply mr-1"></i>Balas
                </button>

                {{-- Reply Form --}}
                <div x-show="showReplyForm === {{ $comment->id }}" x-transition
                    class="mt-3 bg-gray-50 rounded-lg p-3 border-l-4 border-[#8B6F47]">
                    <form action="{{ route('ulasan.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="reviewable_type" value="App\Models\Artikel">
                        <input type="hidden" name="reviewable_id" value="{{ $artikel->id }}">
                        <input type="hidden" name="parent_id" value="{{ $comment->id }}">

                        <div class="mb-2">
                            <textarea name="komentar" rows="2"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-[#8B6F47] focus:border-transparent text-xs"
                                placeholder="Balas ke {{ $comment->user->name }}..." required></textarea>
                        </div>

                        <div class="flex gap-2">
                            <button type="submit"
                                class="bg-gradient-to-r from-[#8B6F47] to-[#D4AF37] text-white px-3 py-1 rounded-lg font-semibold hover:shadow-lg transition-all duration-300 text-xs">
                                <i class="fas fa-paper-plane mr-1"></i>Kirim
                            </button>
                            <button type="button" @click="showReplyForm = null"
                                class="bg-gray-200 text-gray-700 px-3 py-1 rounded-lg font-semibold hover:bg-gray-300 transition-all duration-300 text-xs">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Nested Replies (Recursive) --}}
                @if ($comment->replies->count() > 0)
                    <div class="mt-3">
                        @foreach ($comment->replies as $reply)
                            <x-thread-reply :comment="$reply" :artikel="$artikel" />
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

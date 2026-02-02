{{-- Flat Reply Component - No Nested Structure --}}
<div class="border-l-2 border-gray-200 pl-3 py-3">
    <div class="flex items-start space-x-3">
        <div class="flex-shrink-0">
            <div
                class="w-10 h-10 rounded-full bg-gradient-to-br from-gray-400 to-gray-600 flex items-center justify-center text-white font-bold text-sm">
                {{ strtoupper(substr($reply->user->name, 0, 1)) }}
            </div>
        </div>
        <div class="flex-1 min-w-0">
            <div class="flex items-center justify-between mb-2">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 flex-wrap">
                        <h5 class="font-semibold text-gray-800 text-sm">{{ $reply->user->name }}</h5>
                        @if ($reply->parent_id)
                            @php
                                $parentComment = \App\Models\Ulasan::find($reply->parent_id);
                            @endphp
                            @if ($parentComment)
                                <span class="text-xs text-gray-500">
                                    <i class="fas fa-reply mr-1"></i>reply to
                                    <span class="font-semibold text-[#8B6F47]">{{ $parentComment->user->name }}</span>
                                </span>
                            @endif
                        @endif
                    </div>
                    <p class="text-xs text-gray-500">{{ $reply->created_at->diffForHumans() }}</p>
                </div>
                @if (auth()->id() === $reply->user_id)
                    <form action="{{ route('ulasan.destroy', $reply->id) }}" method="POST"
                        onsubmit="return confirm('Yakin ingin menghapus balasan ini?')" class="flex-shrink-0">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:text-red-700 text-sm">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                @endif
            </div>
            <p class="text-gray-700 text-sm leading-relaxed break-words">{{ $reply->komentar }}</p>

            {{-- Reply Button --}}
            <button onclick="toggleReplyForm({{ $reply->id }})"
                class="mt-2 text-xs text-[#8B6F47] hover:text-[#D4AF37] font-medium">
                <i class="fas fa-reply mr-1"></i>Balas
            </button>

            {{-- Reply Form --}}
            <div id="reply-form-{{ $reply->id }}" class="hidden mt-3">
                <form action="{{ route('ulasan.store') }}" method="POST" class="space-y-2">
                    @csrf
                    <input type="hidden" name="reviewable_type" value="{{ $reviewableType ?? 'App\Models\Wisata' }}">
                    <input type="hidden" name="reviewable_id" value="{{ $reviewableId ?? ($wisata->id ?? '') }}">
                    {{-- Always reply to the root parent (original comment) --}}
                    <input type="hidden" name="parent_id" value="{{ $reply->parent_id ?? $reply->id }}">

                    <textarea name="komentar" rows="2" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#8B6F47] focus:border-transparent resize-none text-sm"
                        placeholder="Tulis balasan untuk {{ $reply->user->name }}..."></textarea>

                    <div class="flex space-x-2">
                        <button type="submit"
                            class="bg-[#8B6F47] text-white px-3 py-1.5 rounded-lg text-xs font-semibold hover:bg-[#6B5436] transition">
                            Kirim
                        </button>
                        <button type="button" onclick="toggleReplyForm({{ $reply->id }})"
                            class="bg-gray-300 text-gray-700 px-3 py-1.5 rounded-lg text-xs font-semibold hover:bg-gray-400 transition">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

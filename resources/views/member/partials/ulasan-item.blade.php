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
                    <input type="hidden" name="reviewable_type" value="{{ $reviewableType }}">
                    <input type="hidden" name="reviewable_id" value="{{ $reviewableId }}">
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

            {{-- Replies (Collapsible) --}}
            @php
                $directReplies = $allReplies->where('parent_id', $ulasan->id)->pluck('id')->toArray();
                $nestedReplies = $allReplies->whereIn('parent_id', $directReplies)->pluck('id')->toArray();
                $allRelatedReplies = array_merge($directReplies, $nestedReplies);
                $repliesToShow = $allReplies->whereIn('id', $allRelatedReplies);
            @endphp
            @if ($repliesToShow->count() > 0)
                <div class="mt-4">
                    {{-- Toggle Button for Replies --}}
                    <button onclick="toggleReplies({{ $ulasan->id }})"
                        class="flex items-center gap-2 text-sm text-[#8B6F47] hover:text-[#D4AF37] font-medium mb-3 transition">
                        <i id="reply-icon-{{ $ulasan->id }}" class="fas fa-chevron-down"></i>
                        <span id="reply-text-{{ $ulasan->id }}">Tampilkan {{ $repliesToShow->count() }}
                            balasan</span>
                    </button>

                    {{-- Replies Container (Initially Hidden) --}}
                    <div id="replies-container-{{ $ulasan->id }}" class="hidden space-y-3">
                        @foreach ($repliesToShow as $reply)
                            @include('member.partials.reply-comment', [
                                'reply' => $reply,
                                'reviewableType' => $reviewableType,
                                'reviewableId' => $reviewableId,
                            ])
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

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
                        <h5 class="font-semibold text-gray-800 text-sm flex items-center gap-1">{{ $reply->user->name }}
                            @if ($ulasan->user->role === 'admin')
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                    class="w-4 h-4 text-blue-500" title="Administrator">
                                    <path fill-rule="evenodd"
                                        d="M8.603 3.799A4.49 4.49 0 0112 2.25c1.357 0 2.573.6 3.397 1.549a4.49 4.49 0 013.498 1.307 4.491 4.491 0 011.307 3.497A4.49 4.49 0 0121.75 12a4.49 4.49 0 01-1.549 3.397 4.491 4.491 0 01-1.307 3.497 4.491 4.491 0 01-3.497 1.307A4.49 4.49 0 0112 21.75a4.49 4.49 0 01-3.397-1.549 4.49 4.49 0 01-3.498-1.306 4.491 4.491 0 01-1.307-3.498A4.49 4.49 0 012.25 12c0-1.357.6-2.573 1.549-3.397a4.49 4.49 0 011.307-3.497 4.491 4.491 0 013.497-1.307zm7.007 6.387a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z"
                                        clip-rule="evenodd" />
                                </svg>
                            @endif
                        </h5>
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

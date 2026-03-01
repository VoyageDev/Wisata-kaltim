<!-- Ulasans List -->
@if ($ulasans->count() > 0)
    <div class="space-y-4">
        @foreach ($ulasans as $ulasan)
            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                <div class="flex justify-between items-start mb-3">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <div>
                                {{-- tipe ulasan --}}
                                <span class="text-xs text-gray-500">
                                    {{ class_basename($ulasan->reviewable_type) }}
                                </span>
                                {{-- pengarahan url --}}
                                @php
                                    $reviewableType = class_basename($ulasan->reviewable_type);
                                    $anchorId = $ulasan->parent_id ?: $ulasan->id;
                                    $url = null;
                                    if ($reviewableType === 'Wisata' && $ulasan->reviewable) {
                                        $url =
                                            route('wisata.detail', $ulasan->reviewable->slug) . '#ulasan-' . $anchorId;
                                    } elseif ($reviewableType === 'Artikel' && $ulasan->reviewable) {
                                        $url =
                                            route('artikel.detail', $ulasan->reviewable->slug) . '#ulasan-' . $anchorId;
                                    }
                                @endphp

                                {{-- judul  --}}
                                @if ($url)
                                    <a href="{{ $url }}"
                                        class="block font-semibold text-blue-600 hover:text-blue-800 hover:underline">
                                        {{ $ulasan->reviewable->name ?? ($ulasan->reviewable->judul ?? 'Tidak tersedia') }}
                                    </a>
                                @else
                                    <h3 class="font-semibold text-gray-900">
                                        {{ $ulasan->reviewable->name ?? ($ulasan->reviewable->judul ?? 'Tidak tersedia') }}
                                    </h3>
                                @endif
                            </div>

                            {{-- rating --}}
                            @if ($ulasan->rating)
                                <div class="flex gap-1">
                                    @for ($i = 0; $i < $ulasan->rating; $i++)
                                        <span class="text-yellow-400">★</span>
                                    @endfor
                                    @for ($i = $ulasan->rating; $i < 5; $i++)
                                        <span class="text-gray-300">★</span>
                                    @endfor
                                </div>
                            @endif
                        </div>
                        {{-- komentar --}}
                        <p class="text-gray-700 mb-2">{{ $ulasan->komentar }}</p>
                        {{-- waktu komentar --}}
                        <p class="text-sm text-gray-500">{{ $ulasan->created_at->diffForHumans() }}</p>
                    </div>
                </div>

                <div class="relative flex gap-3">
                    <button type="button" class="text-blue-600 hover:text-blue-800 text-sm font-medium"
                        onclick="toggleEditUlasan({{ $ulasan->id }})">
                        Edit
                    </button>
                    <div id="edit-form-{{ $ulasan->id }}"
                        class="hidden absolute left-0 top-full mt-2 w-64 bg-white border border-gray-200 rounded-lg shadow-lg p-3 z-10">
                        <form action="{{ route('history.ulasan.update', $ulasan) }}" method="POST" class="space-y-2">
                            @csrf
                            @method('PUT')
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Komentar</label>
                                <textarea name="komentar" rows="2" required
                                    class="w-full border border-gray-300 rounded-md px-2 py-1 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none">{{ $ulasan->komentar }}</textarea>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Rating (opsional)</label>
                                <select name="rating"
                                    class="w-full border border-gray-300 rounded-md px-2 py-1 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">--</option>
                                    @for ($i = 1; $i <= 5; $i++)
                                        <option value="{{ $i }}" @selected($ulasan->rating === $i)>
                                            {{ $i }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            <div class="flex items-center justify-end gap-2">
                                <button type="button" onclick="toggleEditUlasan({{ $ulasan->id }})"
                                    class="text-xs text-gray-600 hover:text-gray-800">
                                    Batal
                                </button>
                                <button type="submit"
                                    class="text-xs bg-blue-600 text-white px-3 py-1 rounded-md hover:bg-blue-700">
                                    Simpan
                                </button>
                            </div>
                        </form>
                    </div>
                    <form action="{{ route('history.ulasan.delete', $ulasan) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Yakin ingin menghapus ulasan ini?')"
                            class="text-red-600 hover:text-red-800 text-sm font-medium">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        @endforeach

        <!-- Pagination -->
        <div class="mt-6">
            {{ $ulasans->links() }}
        </div>
    </div>
@else
    <div class="text-center py-12">
        <div class="text-6xl mb-4">💭</div>
        <h3 class="text-xl font-semibold text-gray-900 mb-2">Belum Ada Ulasan</h3>
        <p class="text-gray-600 mb-4">Mulai bagikan pengalaman Anda tentang wisata atau artikel</p>
        <a href="{{ route('wisata.index') }}"
            class="inline-block bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
            Kunjungi Wisata
        </a>
    </div>
@endif

<!-- Edit Ulasan Modal akan ditambahkan di sini dengan JavaScript -->
<script>
    function toggleEditUlasan(id) {
        var target = document.getElementById('edit-form-' + id);
        if (!target) {
            return;
        }
        document.querySelectorAll('[id^="edit-form-"]').forEach(function(form) {
            if (form !== target) {
                form.classList.add('hidden');
            }
        });
        target.classList.toggle('hidden');
    }
</script>

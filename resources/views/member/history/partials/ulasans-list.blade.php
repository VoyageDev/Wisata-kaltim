<!-- Ulasans List -->
@if ($ulasans->count() > 0)
    <div class="space-y-4">
        @foreach ($ulasans as $ulasan)
            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                <div class="flex justify-between items-start mb-3">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <div>
                                <span class="text-xs text-gray-500">
                                    {{ class_basename($ulasan->reviewable_type) }}
                                </span>
                                @php
                                    $reviewableType = class_basename($ulasan->reviewable_type);
                                    $url = null;
                                    if ($reviewableType === 'Wisata' && $ulasan->reviewable) {
                                        $url = route('wisata.detail', $ulasan->reviewable->slug);
                                    } elseif ($reviewableType === 'Artikel' && $ulasan->reviewable) {
                                        $url = route('artikel.detail', $ulasan->reviewable->slug);
                                    }
                                @endphp

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
                        <p class="text-gray-700 mb-2">{{ $ulasan->komentar }}</p>
                        <p class="text-sm text-gray-500">{{ $ulasan->created_at->diffForHumans() }}</p>
                    </div>
                </div>

                <div class="flex gap-3">
                    @if ($url)
                        <a href="{{ $url }}" class="text-green-600 hover:text-green-800 text-sm font-medium">
                            Lihat Detail →
                        </a>
                    @endif
                    <button class="text-blue-600 hover:text-blue-800 text-sm font-medium"
                        onclick="editUlasan({{ $ulasan->id }})">
                        Edit
                    </button>
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

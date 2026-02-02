<x-layouts.user>
    <div class="min-h-screen bg-gradient-to-b from-gray-50 to-gray-100 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Header --}}
            <div class="mb-12">
                <h1 class="text-4xl font-bold text-gray-800 mb-4">
                    <i class="fas fa-history text-[#8B6F47] mr-3"></i>
                    History Ulasan Saya
                </h1>
                <p class="text-gray-600 text-lg">Semua ulasan yang pernah Anda berikan</p>
            </div>

            {{-- Ulasan List --}}
            <div class="space-y-6">
                @forelse ($ulasans as $ulasan)
                    <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200">
                        <div class="p-6">
                            {{-- Ulasan Header --}}
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    {{-- Reviewable Info --}}
                                    <div class="flex items-center gap-3 mb-2">
                                        @if ($ulasan->reviewable_type === 'App\Models\Artikel')
                                            <a href="{{ route('artikel.detail', $ulasan->reviewable->slug) }}"
                                                class="flex items-center gap-3 group">
                                                <img src="{{ asset($ulasan->reviewable->thumbnail) }}"
                                                    alt="{{ $ulasan->reviewable->judul }}"
                                                    class="w-16 h-16 object-cover rounded-lg">
                                                <div>
                                                    <span
                                                        class="inline-block bg-blue-100 text-blue-800 text-xs font-semibold px-2 py-1 rounded mb-1">
                                                        <i class="fas fa-newspaper mr-1"></i>Artikel
                                                    </span>
                                                    <h3
                                                        class="font-bold text-gray-800 group-hover:text-[#8B6F47] transition-colors line-clamp-1">
                                                        {{ $ulasan->reviewable->judul }}
                                                    </h3>
                                                </div>
                                            </a>
                                        @elseif ($ulasan->reviewable_type === 'App\Models\Wisata')
                                            <div class="flex items-center gap-3">
                                                <img src="{{ asset($ulasan->reviewable->image ?? 'images/placeholder.jpg') }}"
                                                    alt="{{ $ulasan->reviewable->name }}"
                                                    class="w-16 h-16 object-cover rounded-lg">
                                                <div>
                                                    <span
                                                        class="inline-block bg-green-100 text-green-800 text-xs font-semibold px-2 py-1 rounded mb-1">
                                                        <i class="fas fa-map-marker-alt mr-1"></i>Wisata
                                                    </span>
                                                    <h3 class="font-bold text-gray-800 line-clamp-1">
                                                        {{ $ulasan->reviewable->name }}
                                                    </h3>
                                                    <p class="text-xs text-gray-500">
                                                        {{ $ulasan->reviewable->kota->name ?? '' }}
                                                    </p>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                {{-- Rating & Delete Button --}}
                                <div class="flex flex-col items-end gap-2">
                                    <div class="flex items-center gap-1">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i
                                                class="fas fa-star {{ $i <= $ulasan->rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                        @endfor
                                    </div>
                                    <form action="{{ route('ulasan.destroy', $ulasan) }}" method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus ulasan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-red-500 text-sm hover:text-red-700 transition-colors">
                                            <i class="fas fa-trash mr-1"></i>Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>

                            {{-- Komentar --}}
                            <div class="bg-gray-50 rounded-lg p-4 mb-3">
                                <p class="text-gray-700">{{ $ulasan->komentar }}</p>
                            </div>

                            {{-- Timestamp --}}
                            <div class="flex items-center text-sm text-gray-500">
                                <i class="fas fa-clock mr-2"></i>
                                {{ $ulasan->created_at->format('d F Y, H:i') }}
                                <span class="mx-2">•</span>
                                {{ $ulasan->created_at->diffForHumans() }}
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-xl shadow-md p-12 text-center">
                        <i class="fas fa-comment-slash text-gray-400 text-5xl mb-4"></i>
                        <p class="text-gray-600 text-lg mb-4">Anda belum memiliki ulasan</p>
                        <a href="{{ route('artikel.index') }}"
                            class="inline-block bg-gradient-to-r from-[#8B6F47] to-[#D4AF37] text-white px-6 py-3 rounded-lg font-semibold hover:shadow-lg transition-all duration-300">
                            <i class="fas fa-newspaper mr-2"></i>
                            Jelajahi Artikel
                        </a>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            @if ($ulasans->hasPages())
                <div class="mt-8 flex justify-center">
                    {{ $ulasans->links() }}
                </div>
            @endif

        </div>
    </div>
</x-layouts.user>

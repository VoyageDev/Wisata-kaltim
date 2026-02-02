<x-layouts.admin>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Detail Ulasan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            {{-- Back Button --}}
            <a href="{{ route('admin.ulasan.index') }}"
                class="inline-flex items-center text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 font-medium mb-6">
                <i class="fas fa-arrow-left mr-2"></i>Kembali ke Daftar Ulasan
            </a>

            {{-- Content Card --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-8 space-y-8">
                {{-- User Info --}}
                <div class="pb-8 border-b border-gray-200 dark:border-gray-700">
                    <div class="grid grid-cols-2 gap-6 mb-6">
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 font-medium mb-1">Nama Pengunjung</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                {{ $ulasan->user->name ?? 'Guest' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 font-medium mb-1">Email</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                {{ $ulasan->user->email ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 font-medium mb-1">Tanggal Ulasan</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                {{ $ulasan->created_at->format('d F Y H:i') }}</p>
                        </div>
                    </div>
                </div>

                {{-- Item & Rating --}}
                <div class="pb-8 border-b border-gray-200 dark:border-gray-700">
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 font-medium mb-2">Item</p>
                            @if ($ulasan->reviewable_type === 'App\\Models\\Wisata')
                                <a href="{{ route('wisata.detail', $ulasan->reviewable->slug) }}"
                                    class="text-lg font-semibold text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300">
                                    {{ $ulasan->reviewable->name }}
                                </a>
                            @else
                                <span
                                    class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $ulasan->reviewable->name ?? '-' }}</span>
                            @endif
                        </div>
                        </a>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 font-medium mb-2">Rating</p>
                        <div class="flex items-center">
                            @for ($i = 0; $i < $ulasan->rating; $i++)
                                <i class="fas fa-star text-yellow-400 text-xl mr-1"></i>
                            @endfor
                            <span
                                class="ml-2 text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $ulasan->rating }}/5</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Review Content --}}
            <div class="pb-8 border-b border-gray-200 dark:border-gray-700">
                <p class="text-sm text-gray-600 dark:text-gray-400 font-medium mb-3">Isi Ulasan</p>
                <div
                    class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 text-gray-800 dark:text-gray-200 leading-relaxed">
                    {!! nl2br(e($ulasan->komentar)) !!}
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex flex-col sm:flex-row gap-4">
                <form action="{{ route('admin.ulasan.destroy', $ulasan->id) }}" method="POST" class="flex-1"
                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus ulasan ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="w-full bg-red-600 hover:bg-red-700 dark:bg-red-700 dark:hover:bg-red-600 text-white font-semibold py-3 rounded-lg transition">
                        <i class="fas fa-trash mr-2"></i>Hapus Ulasan
                    </button>
                </form>
            </div>
        </div>
    </div>
    </div>
</x-layouts.admin>

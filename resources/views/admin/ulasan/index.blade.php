<x-layouts.admin>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manajemen Ulasan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            {{-- Filter & Search --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="GET" action="{{ route('admin.ulasan.index') }}" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Cari
                                Nama/Judul</label>
                            <input type="text" name="search" placeholder="Cari berdasarkan nama atau judul..."
                                value="{{ request('search') }}"
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tipe
                                Item</label>
                            <select name="type"
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Semua Tipe</option>
                                <option value="App\Models\Wisata" @if (request('type') === 'App\Models\Wisata') selected @endif>
                                    Wisata</option>
                                <option value="App\Models\Artikel" @if (request('type') === 'App\Models\Artikel') selected @endif>
                                    Artikel</option>
                            </select>
                        </div>
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Rating</label>
                            <select name="rating"
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Semua Rating</option>
                                <option value="5" @if (request('rating') === '5') selected @endif>5 Bintang
                                </option>
                                <option value="4" @if (request('rating') === '4') selected @endif>4 Bintang
                                </option>
                                <option value="3" @if (request('rating') === '3') selected @endif>3 Bintang
                                </option>
                                <option value="2" @if (request('rating') === '2') selected @endif>2 Bintang
                                </option>
                                <option value="1" @if (request('rating') === '1') selected @endif>1 Bintang
                                </option>
                            </select>
                        </div>
                        <div class="flex items-end gap-2">
                            <button type="submit"
                                class="flex-1 bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-600 text-white font-medium py-2 rounded-lg transition">
                                <i class="fas fa-search mr-2"></i>Filter
                            </button>
                            <a href="{{ route('admin.ulasan.index') }}"
                                class="flex-1 bg-gray-600 hover:bg-gray-700 dark:bg-gray-700 dark:hover:bg-gray-600 text-white font-medium py-2 rounded-lg transition text-center">
                                <i class="fas fa-redo mr-2"></i>Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Table --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                            <tr>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">
                                    User</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">
                                    Tipe</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">
                                    Judul</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">
                                    Komentar</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">
                                    Rating</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">
                                    Tanggal</th>
                                <th
                                    class="px-6 py-4 text-center text-sm font-semibold text-gray-900 dark:text-gray-100">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                            @forelse ($ulasans as $ulasan)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                    <td class="px-6 py-4">
                                        <div>
                                            <p class="font-medium text-gray-900 dark:text-gray-100">
                                                {{ $ulasan->user->name ?? 'Guest' }}</p>
                                            <p class="text-xs text-gray-600 dark:text-gray-400">
                                                {{ $ulasan->user->email ?? '-' }}</p>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                            @if ($ulasan->reviewable_type === 'App\\Models\\Wisata') bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400
                                            @elseif ($ulasan->reviewable_type === 'App\\Models\\Artikel')
                                                bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400
                                            @elseif ($ulasan->reviewable_type === 'App\\Models\\Kota')
                                                bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-400
                                            @else
                                                bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300 @endif">
                                            {{ class_basename($ulasan->reviewable_type) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="font-medium text-gray-900 dark:text-gray-100 max-w-xs truncate">
                                            @if ($ulasan->reviewable_type === 'App\\Models\\Artikel')
                                                {{ $ulasan->reviewable->judul ?? '-' }}
                                            @else
                                                {{ $ulasan->reviewable->name ?? '-' }}
                                            @endif
                                        </p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="text-gray-700 dark:text-gray-300 text-sm max-w-xs truncate">
                                            {{ $ulasan->komentar }}</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= $ulasan->rating)
                                                    <i class="fas fa-star text-yellow-400 text-sm"></i>
                                                @else
                                                    <i class="fas fa-star text-gray-500 text-sm"></i>
                                                @endif
                                            @endfor
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                        {{ $ulasan->created_at->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex justify-center gap-3">
                                            <a href="{{ route('admin.ulasan.show', $ulasan->id) }}"
                                                class="text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 font-medium text-sm"
                                                title="Lihat Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <form action="{{ route('admin.ulasan.destroy', $ulasan->id) }}"
                                                method="POST" class="inline"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus ulasan ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 font-medium text-sm"
                                                    title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center">
                                        <i
                                            class="fas fa-inbox text-gray-300 dark:text-gray-600 text-5xl mb-4 block"></i>
                                        <p class="text-gray-500 dark:text-gray-400 font-medium">Belum ada ulasan</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if ($ulasans->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-600">
                        {{ $ulasans->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts.admin>

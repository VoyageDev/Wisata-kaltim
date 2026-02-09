<x-layouts.admin>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Kelola Berita') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Header --}}
            <div class="mb-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Manajemen Artikel</h1>
                        <p class="text-gray-600 dark:text-gray-400 mt-1">Kelola artikel di platform Anda</p>
                    </div>
                    <a href="{{ route('admin.artikel.create') }}"
                        class="bg-gradient-to-r from-[#074e0e] to-[#167509] text-white px-6 py-3 rounded-lg font-semibold hover:shadow-lg transition-all duration-300 inline-flex items-center justify-center">
                        <i class="fas fa-plus mr-2"></i>Tambah Berita
                    </a>
                </div>
            </div>

            {{-- Alert --}}
            @if (session('success'))
                <div
                    class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                    <p class="text-green-800 dark:text-green-300 font-medium flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                    </p>
                </div>
            @endif

            {{-- Table --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gradient-to-r from-[#0f0c29] via-[#302b63] to-[#24243e] text-white">
                            <tr>
                                <th class="px-6 py-4 text-left text-sm font-semibold">Judul</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold">Wisata</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold">Kota</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold">Penulis</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold">Views</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold">Tanggal</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($artikels as $artikel)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-gray-900 dark:text-gray-100 line-clamp-2">
                                            {{ $artikel->judul }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-gray-600 dark:text-gray-400 text-sm line-clamp-1">
                                            {{ $artikel->wisata->name ?? 'Wisata Tidak Tersedia' }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="inline-block bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 text-xs px-3 py-1 rounded-full font-medium">
                                            {{ $artikel->wisata->kota->name }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-gray-600 dark:text-gray-400 text-sm">
                                        {{ $artikel->user->name }}</td>
                                    <td class="px-6 py-4 text-gray-600 dark:text-gray-400 text-sm">
                                        <i
                                            class="fas fa-eye text-[#8B6F47] dark:text-[#D4AF37] mr-1"></i>{{ number_format($artikel->views) }}
                                    </td>
                                    <td class="px-6 py-4 text-gray-600 dark:text-gray-400 text-sm">
                                        {{ $artikel->created_at->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex gap-2">
                                            <a href="{{ route('admin.artikel.edit', $artikel) }}"
                                                class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300 font-medium text-sm">
                                                <i class="fas fa-edit"></i>Edit
                                            </a>
                                            <form action="{{ route('admin.artikel.destroy', $artikel) }}"
                                                method="POST" onsubmit="return confirm('Yakin ingin menghapus?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 font-medium text-sm">
                                                    <i class="fas fa-trash"></i>Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <i
                                            class="fas fa-inbox text-gray-300 dark:text-gray-600 text-5xl mb-4 block"></i>
                                        <p class="text-gray-500 dark:text-gray-400 font-medium">Belum ada artikel</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if ($artikels->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                        {{ $artikels->links() }}
                    </div>
                @endif
            </div>
</x-layouts.admin>

<x-layouts.admin>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Kelola Wisata') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Header --}}
            <div class="mb-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Manajemen Wisata</h1>
                        <p class="text-gray-600 dark:text-gray-400 mt-1">Kelola destinasi wisata di platform Anda</p>
                    </div>
                    <a href="{{ route('admin.wisata.create') }}"
                        class="bg-gradient-to-r from-[#074e0e] to-[#167509] text-white px-6 py-3 rounded-lg font-semibold hover:shadow-lg transition-all duration-300 inline-flex items-center justify-center">
                        <i class="fas fa-plus mr-2"></i>Tambah Wisata
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
                        <thead class="bg-gradient-to-r from-[#000428] via-[#004e92] to-[#000428] text-white">
                            <tr>
                                <th class="px-6 py-4 text-left text-sm font-semibold">Nama Wisata</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold">Kota</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold">Harga Tiket</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold">Status</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($wisatas as $wisata)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-gray-900 dark:text-gray-100">{{ $wisata->name }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="inline-block bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 text-xs px-3 py-1 rounded-full font-medium">
                                            {{ $wisata->kota->name }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-gray-600 dark:text-gray-400 text-sm">
                                        Rp {{ number_format($wisata->harga_tiket, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="inline-block px-3 py-1 rounded-full text-xs font-semibold {{ $wisata->status === 'Open' ? 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200' : 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200' }}">
                                            {{ $wisata->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex gap-2">
                                            <a href="{{ route('admin.wisata.edit', $wisata) }}"
                                                class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300 font-medium text-sm">
                                                <i class="fas fa-edit"></i>Edit
                                            </a>
                                            <form action="{{ route('admin.wisata.destroy', $wisata) }}" method="POST"
                                                onsubmit="return confirm('Yakin ingin menghapus?')">
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
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <i
                                            class="fas fa-map-location-dot text-gray-300 dark:text-gray-600 text-5xl mb-4 block"></i>
                                        <p class="text-gray-500 dark:text-gray-400 font-medium">Belum ada wisata</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if ($wisatas->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                        {{ $wisatas->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts.admin>

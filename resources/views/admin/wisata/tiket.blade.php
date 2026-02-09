<x-layouts.admin>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.wisata.index') }}"
                class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Kelola Wisata') }}
            </a>
            <span class="text-gray-300 text-xl">|</span>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight underline">
                {{ __('Kelola Tiket') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Header --}}
            <div class="mb-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Manajemen Kuota Tiket Wisata
                        </h1>
                        <p class="text-gray-600 dark:text-gray-400 mt-1">Kelola kuota tiket wisata per tanggal</p>
                    </div>
                    <a href="{{ route('admin.tiket.create') }}"
                        class="bg-gradient-to-r from-[#074e0e] to-[#167509] text-white px-6 py-3 rounded-lg font-semibold hover:shadow-lg transition-all duration-300 inline-flex items-center justify-center">
                        <i class="fas fa-plus mr-2"></i>Tambah Kuota Tiket
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

            {{-- Filter & Search --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                <form method="GET" action="{{ route('admin.wisata.tiket') }}" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

                        {{-- Input Search --}}
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Cari Nama Wisata
                            </label>
                            <input type="text" name="search" placeholder="Cari berdasarkan nama wisata..."
                                value="{{ request('search') }}"
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        {{-- Input Filter Tanggal --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Filter Waktu
                            </label>
                            <select name="filter_date"
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Semua Waktu</option>
                                <option value="besok" {{ request('filter_date') == 'besok' ? 'selected' : '' }}>
                                    Besok
                                </option>
                                <option value="minggu_depan"
                                    {{ request('filter_date') == 'minggu_depan' ? 'selected' : '' }}>
                                    Minggu Depan
                                </option>
                                <option value="bulan_ini" {{ request('filter_date') == 'bulan_ini' ? 'selected' : '' }}>
                                    Bulan Ini
                                </option>
                            </select>
                        </div>

                        {{-- Tombol Action --}}
                        <div class="flex items-end gap-2">
                            <button type="submit"
                                class="flex-1 bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-600 text-white font-medium py-2 rounded-lg transition">
                                <i class="fas fa-search mr-2"></i>Filter
                            </button>
                            <a href="{{ route('admin.wisata.tiket') }}"
                                class="flex-1 bg-gray-600 hover:bg-gray-700 dark:bg-gray-700 dark:hover:bg-gray-600 text-white font-medium py-2 rounded-lg transition text-center">
                                <i class="fas fa-redo mr-2"></i>Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Table --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gradient-to-r from-[#000428] via-[#004e92] to-[#000428] text-white">
                            <tr>
                                <th class="px-6 py-4 text-left text-sm font-semibold">Nama Wisata</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold">Tanggal</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold">Kuota Total</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold">Kuota Terpakai</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($wisatas as $wisata)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-gray-900 dark:text-gray-100">
                                            {{ $wisata->wisata->name }}
                                        </div>
                                        @if ($wisata->wisata->kota)
                                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                <i
                                                    class="fas fa-map-marker-alt mr-1"></i>{{ $wisata->wisata->kota->name }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="inline-block bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 text-xs px-3 py-1 rounded-full font-medium">
                                            {{ \Carbon\Carbon::parse($wisata->tanggal)->format('d M Y') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-gray-600 dark:text-gray-400 text-sm">
                                        <span class="font-semibold">{{ number_format($wisata->kuota_total) }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">
                                                {{ number_format($wisata->kuota_terpakai) }}
                                            </span>
                                            <span class="text-xs text-gray-500">
                                                ({{ $wisata->kuota_total > 0 ? number_format(($wisata->kuota_terpakai / $wisata->kuota_total) * 100, 1) : 0 }}%)
                                            </span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-1.5 mt-1">
                                            <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-1.5 rounded-full"
                                                style="width: {{ $wisata->kuota_total > 0 ? min(($wisata->kuota_terpakai / $wisata->kuota_total) * 100, 100) : 0 }}%">
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex gap-2">
                                            <a href="{{ route('admin.wisata.edit', $wisata->wisata) }}"
                                                class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300 font-medium text-sm">
                                                <i class="fas fa-edit"></i>Edit
                                            </a>
                                            <form action="{{ route('admin.wisata.destroy', $wisata->wisata) }}"
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
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <i
                                            class="fas fa-ticket text-gray-300 dark:text-gray-600 text-5xl mb-4 block"></i>
                                        <p class="text-gray-500 dark:text-gray-400 font-medium">Belum ada kuota tiket
                                        </p>
                                        <a href="{{ route('admin.tiket.create') }}"
                                            class="inline-block mt-4 text-blue-600 hover:text-blue-800 font-medium">
                                            <i class="fas fa-plus mr-1"></i>Tambah kuota tiket pertama
                                        </a>
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

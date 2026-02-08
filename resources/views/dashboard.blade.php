<x-layouts.admin>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard Admin') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Stats Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
                {{-- Berita Card --}}
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">Total Berita</p>
                                <p class="text-3xl font-bold text-gray-900 dark:text-gray-100 mt-2">{{ $artikelCount }}
                                </p>
                            </div>
                            <div class="bg-blue-100 dark:bg-blue-900 p-4 rounded-lg">
                                <i class="fas fa-newspaper text-blue-600 dark:text-blue-400 text-2xl"></i>
                            </div>
                        </div>
                        <a href="{{ route('admin.artikel.index') }}"
                            class="block mt-4 text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 font-medium">
                            Kelola Berita <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>

                {{-- Wisata Card --}}
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">Total Wisata</p>
                                <p class="text-3xl font-bold text-gray-900 dark:text-gray-100 mt-2">{{ $wisataCount }}
                                </p>
                            </div>
                            <div class="bg-green-100 dark:bg-green-900 p-4 rounded-lg">
                                <i class="fas fa-map-marked-alt text-green-600 dark:text-green-400 text-2xl"></i>
                            </div>
                        </div>
                        <a href="{{ route('admin.wisata.index') }}"
                            class="block mt-4 text-green-600 dark:text-green-400 hover:text-green-700 dark:hover:text-green-300 font-medium">
                            Kelola Wisata <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>

                {{-- Kota Card --}}
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">Total Kota</p>
                                <p class="text-3xl font-bold text-gray-900 dark:text-gray-100 mt-2">{{ $kotaCount }}
                                </p>
                            </div>
                            <div class="bg-purple-100 dark:bg-purple-900 p-4 rounded-lg">
                                <i class="fas fa-city text-purple-600 dark:text-purple-400 text-2xl"></i>
                            </div>
                        </div>
                        <a href="{{ route('admin.kota.index') }}"
                            class="block mt-4 text-purple-600 dark:text-purple-400 hover:text-purple-700 dark:hover:text-purple-300 font-medium">
                            Kelola Kota <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>

                {{-- Ulasan Card --}}
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">Total Ulasan</p>
                                <p class="text-3xl font-bold text-gray-900 dark:text-gray-100 mt-2">{{ $ulasanCount }}
                                </p>
                            </div>
                            <div class="bg-orange-100 dark:bg-orange-900 p-4 rounded-lg">
                                <i class="fas fa-comments text-orange-600 dark:text-orange-400 text-2xl"></i>
                            </div>
                        </div>
                        <a href="{{ route('admin.ulasan.index') }}"
                            class="block mt-4 text-orange-600 dark:text-orange-400 hover:text-orange-700 dark:hover:text-orange-300 font-medium">
                            Kelola Ulasan <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>

                {{-- booking Card --}}
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">Total Booking</p>
                                <p class="text-3xl font-bold text-gray-900 dark:text-gray-100 mt-2">{{ $bookingsCount }}
                                </p>
                            </div>
                            <div class="bg-red-100 dark:bg-red-900 p-4 rounded-lg">
                                <i class="fas fa-ticket-alt text-red-600 dark:text-red-400 text-2xl"></i>
                            </div>
                        </div>
                        <a href="{{ route('admin.booking.index') }}"
                            class="block mt-4 text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 font-medium">
                            Kelola Booking <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Quick Action Buttons --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-6">Aksi Cepat</h2>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <a href="{{ route('admin.artikel.create') }}"
                            class="flex items-center justify-center p-4 bg-blue-50 dark:bg-blue-900/30 hover:bg-blue-100 dark:hover:bg-blue-900/50 rounded-lg transition">
                            <div class="text-center">
                                <i class="fas fa-plus text-blue-600 dark:text-blue-400 text-2xl mb-2 block"></i>
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Tambah Berita</span>
                            </div>
                        </a>
                        <a href="{{ route('admin.wisata.create') }}"
                            class="flex items-center justify-center p-4 bg-green-50 dark:bg-green-900/30 hover:bg-green-100 dark:hover:bg-green-900/50 rounded-lg transition">
                            <div class="text-center">
                                <i class="fas fa-plus text-green-600 dark:text-green-400 text-2xl mb-2 block"></i>
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Tambah Wisata</span>
                            </div>
                        </a>
                        <a href="{{ route('admin.kota.create') }}"
                            class="flex items-center justify-center p-4 bg-purple-50 dark:bg-purple-900/30 hover:bg-purple-100 dark:hover:bg-purple-900/50 rounded-lg transition">
                            <div class="text-center">
                                <i class="fas fa-plus text-purple-600 dark:text-purple-400 text-2xl mb-2 block"></i>
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Tambah Kota</span>
                            </div>
                        </a>
                        <a href="{{ route('admin.ulasan.index') }}"
                            class="flex items-center justify-center p-4 bg-orange-50 dark:bg-orange-900/30 hover:bg-orange-100 dark:hover:bg-orange-900/50 rounded-lg transition">
                            <div class="text-center">
                                <i class="fas fa-list text-orange-600 dark:text-orange-400 text-2xl mb-2 block"></i>
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Lihat Ulasan</span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Info Section --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-6">Info Dashboard</h2>
                    <div class="space-y-4">
                        <div class="flex items-center p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                            <i class="fas fa-info-circle text-blue-600 dark:text-blue-400 text-lg mr-4"></i>
                            <p class="text-gray-700 dark:text-gray-300"><span
                                    class="font-medium">{{ Auth::user()->name }}</span> - Terakhir login hari ini</p>
                        </div>
                        <div class="flex items-center p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                            <i class="fas fa-calendar-alt text-green-600 dark:text-green-400 text-lg mr-4"></i>
                            <p class="text-gray-700 dark:text-gray-300">Tanggal hari ini: <span
                                    class="font-medium">{{ now()->format('d F Y') }}</span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>

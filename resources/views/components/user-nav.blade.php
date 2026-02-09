<nav class="bg-gradient-to-r from-[#9e7944] to-[#D4AF37] shadow-lg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">

            <div class="flex-shrink-0 flex items-center text-white text-2xl font-bold">
                <a href="{{ url('/') }}" class="flex items-center">
                    <i class="fas fa-map mr-2"></i> Go Vacation
                </a>
            </div>

            {{-- nav links dekstop --}}
            <div class="hidden md:flex flex-1 justify-center items-center">
                <ul class="flex space-x-8">
                    @auth
                        <li><a href="{{ url('/artikel') }}"
                                class="text-white font-medium hover:scale-110 transition-transform block">Berita</a>
                        </li>
                        {{-- Kota Dropdown --}}
                        <li class="relative group">
                            <button class="text-white font-medium hover:scale-110 transition-transform">
                                Kota <i class="fas fa-chevron-down ml-1"></i>
                            </button>
                            <div
                                class="absolute left-0 mt-0 w-48 bg-white rounded-md shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                                <div class="py-2 max-h-96 overflow-y-auto">
                                    @foreach ($kotas as $kota)
                                        <a href="{{ route('kota.detail', $kota->slug) }}"
                                            class="block px-4 py-2 text-gray-700 hover:bg-[#D4AF37] hover:text-white transition-colors">
                                            {{ $kota->name }}
                                        </a>
                                    @endforeach
                                    <div class="border-t border-gray-200">
                                        <a href="{{ route('kota.index') }}"
                                            class="block px-4 py-2 text-gray-700 hover:bg-[#D4AF37] hover:text-white transition-colors font-semibold">
                                            <i class="fas fa-arrow-right mr-2"></i>Show More
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </li>

                        <li><a href="{{ route('wisata.index') }}"
                                class="text-white font-medium hover:scale-110 transition-transform block">Wisata</a>
                        </li>

                        <li><a href="{{ route('booking.index') }}"
                                class="text-white font-medium hover:scale-110 transition-transform block">Booking</a>
                        </li>

                        <li><a href="{{ route('history.index') }}"
                                class="text-white font-medium hover:scale-110 transition-transform block">History</a>
                        </li>
                    @else
                        <div class="flex gap-8 items-center justify-center ">
                            <li><a href="{{ route('artikel.index') }}"
                                    class="text-white font-medium hover:scale-110 transition-transform block">Berita</a>
                            </li>
                            <li class="relative group">
                                <button class="text-white font-medium hover:scale-110 transition-transform">
                                    Kota <i class="fas fa-chevron-down ml-1"></i>
                                </button>
                                <div
                                    class="absolute left-0 mt-0 w-48 bg-white rounded-md shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                                    <div class="py-2 max-h-96 overflow-y-auto">
                                        @foreach ($kotas as $kota)
                                            <a href="{{ route('kota.detail', $kota->slug) }}"
                                                class="block px-4 py-2 text-gray-700 hover:bg-[#D4AF37] hover:text-white transition-colors">
                                                {{ $kota->name }}
                                            </a>
                                        @endforeach
                                        <div class="border-t border-gray-200">
                                            <a href="{{ route('kota.index') }}"
                                                class="block px-4 py-2 text-gray-700 hover:bg-[#D4AF37] hover:text-white transition-colors font-semibold">
                                                <i class="fas fa-arrow-right mr-2"></i>Show More
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li><a href="{{ route('wisata.index') }}"
                                    class="text-white font-medium hover:scale-110 transition-transform block">Wisata</a>
                            </li>
                            <li><a href="{{ route('booking.index') }}"
                                    class="text-white font-medium hover:scale-110 transition-transform block">Booking</a>
                            </li>
                        </div>
                    @endauth
                </ul>
            </div>

            {{-- login logout button dekstop --}}
            <div class="hidden md:flex items-center space-x-4 justify-end">
                @auth
                    <div class="flex items-center space-x-3">
                        <div class="text-right">
                            <p class="text-white font-medium text-sm">{{ Auth::user()->name }}</p>
                            <p class="text-white/70 text-xs">{{ Auth::user()->email }}</p>
                        </div>
                        <i class="fas fa-user-circle text-white text-2xl"></i>
                    </div>
                    <button @click="showLogoutModal = true" class="text-white font-medium hover:text-gray-200">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-logout">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2" />
                            <path d="M9 12h12l-3 -3" />
                            <path d="M18 15l3 -3" />
                        </svg>
                    </button>
                @else
                    @if (Route::has('login'))
                        <a href="{{ route('login') }}" class="text-white font-medium hover:text-gray-200">Login</a>
                    @endif
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                            class="bg-white text-[#8B6F47] px-4 py-2 rounded-full font-semibold hover:bg-gray-100 transition shadow">Register</a>
                    @endif
                @endauth
            </div>

            {{-- open modal --}}
            <div class="flex md:hidden items-center space-x-2">
                <button @click="mobileMenuOpen = !mobileMenuOpen"
                    class="text-white hover:text-gray-200 focus:outline-none p-2 rounded-lg hover:bg-[#6B5436] transition">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
        </div>
    </div>

    {{-- nav links mobile --}}
    <div x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-2" class="md:hidden bg-[#8B6F47] border-t border-[#D4AF37]">

        <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
            @auth
                <a href="{{ url('/artikel') }}"
                    class="block px-3 py-2 rounded-md text-base font-medium text-white hover:bg-[#D4AF37] transition">Berita</a>

                {{-- Mobile Kota Dropdown --}}
                <div x-data="{ kotaOpen: false }" class="relative">
                    <button @click="kotaOpen = !kotaOpen"
                        class="w-full text-left px-3 py-2 rounded-md text-base font-medium text-white hover:bg-[#D4AF37] transition flex items-center justify-between">
                        Kota
                        <i class="fas fa-chevron-down" :class="kotaOpen && 'rotate-180'"
                            style="transition: transform 0.2s;"></i>
                    </button>
                    <div x-show="kotaOpen" class="ml-4 space-y-1 mt-1">
                        @foreach ($kotas as $kota)
                            <a href="{{ route('kota.detail', $kota->slug) }}"
                                class="block px-3 py-2 rounded-md text-sm font-medium text-white hover:bg-[#6B5436] transition"
                                @click="kotaOpen = false; mobileMenuOpen = false">
                                {{ $kota->name }}
                            </a>
                        @endforeach
                        <a href="{{ route('kota.index') }}"
                            class="block px-3 py-2 rounded-md text-sm font-semibold text-[#D4AF37] hover:bg-[#6B5436] transition"
                            @click="kotaOpen = false; mobileMenuOpen = false">
                            <i class="fas fa-arrow-right mr-2"></i>Show More
                        </a>
                    </div>
                </div>

                <a href="{{ route('wisata.index') }}"
                    class="block px-3 py-2 rounded-md text-base font-medium text-white hover:bg-[#D4AF37] transition">Wisata</a>

                <a href="{{ route('booking.index') }}"
                    class="block px-3 py-2 rounded-md text-base font-medium text-white hover:bg-[#D4AF37] transition">Booking</a>
                <a href="{{ route('history.index') }}"
                    class="block px-3 py-2 rounded-md text-base font-medium text-white hover:bg-[#D4AF37] transition">History</a>
            @else
                <a href="{{ route('artikel.index') }}"
                    class="block px-3 py-2 rounded-md text-base font-medium text-white hover:bg-[#D4AF37] transition">Berita</a>

                {{-- Mobile Kota Dropdown (Unauthenticated) --}}
                <div x-data="{ kotaOpen: false }" class="relative">
                    <button @click="kotaOpen = !kotaOpen"
                        class="w-full text-left px-3 py-2 rounded-md text-base font-medium text-white hover:bg-[#D4AF37] transition flex items-center justify-between">
                        Kota
                        <i class="fas fa-chevron-down" :class="kotaOpen && 'rotate-180'"
                            style="transition: transform 0.2s;"></i>
                    </button>
                    <div x-show="kotaOpen" class="ml-4 space-y-1 mt-1">
                        @foreach ($kotas as $kota)
                            <a href="{{ route('kota.detail', $kota->slug) }}"
                                class="block px-3 py-2 rounded-md text-sm font-medium text-white hover:bg-[#6B5436] transition">
                                {{ $kota->name }}
                            </a>
                        @endforeach
                        <a href="{{ route('kota.index') }}"
                            class="block px-3 py-2 rounded-md text-sm font-semibold text-[#D4AF37] hover:bg-[#6B5436] transition">
                            <i class="fas fa-arrow-right mr-2"></i>Show More
                        </a>
                    </div>
                </div>

                <a href="{{ route('wisata.index') }}"
                    class="block px-3 py-2 rounded-md text-base font-medium text-white hover:bg-[#D4AF37] transition">Wisata</a>

                <a href="{{ route('booking.index') }}"
                    class="block px-3 py-2 rounded-md text-base font-medium text-white hover:bg-[#D4AF37] transition">Booking</a>
            @endauth
        </div>

        <div class="pt-4 pb-4 border-t border-[#D4AF37]">
            @auth
                <div class="px-4 mb-4 pb-3 border-b border-[#D4AF37]">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-user-circle text-white text-2xl"></i>
                        <div>
                            <p class="text-white font-semibold text-sm">{{ Auth::user()->name }}</p>
                            <p class="text-white/70 text-xs">{{ Auth::user()->email }}</p>
                        </div>
                    </div>
                </div>
                <button @click="showLogoutModal = true; mobileMenuOpen = false"
                    class="block w-full text-left text-white font-medium hover:text-gray-200 px-3 py-2 rounded-md hover:bg-[#D4AF37] transition">
                    <i class="fas fa-sign-out-alt mr-2"></i>Logout
                </button>
            @else
                <div class="px-5 space-y-2">
                    @if (Route::has('login'))
                        <a href="{{ route('login') }}" class="block text-white font-medium">Login</a>
                    @endif
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="block text-white font-medium">Register</a>
                    @endif
                </div>
            @endauth
        </div>
    </div>
</nav>

{{-- Login Modal --}}
<div x-show="showLoginModal" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50"
    @click.self="showLoginModal = false" x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0">

    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 p-8 relative" @click.stop
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90"
        x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90">

        <button @click="showLoginModal = false" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
            <i class="fas fa-times text-2xl"></i>
        </button>

        <div class="text-center mb-6">
            <div
                class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gradient-to-br from-[#051477] to-[#00ccff] mb-4">
                <i class="fas fa-lock text-white text-2xl"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-800 mb-2">Login Required</h3>
            <p class="text-gray-600">Silakan login terlebih dahulu untuk mengakses fitur ini</p>
        </div>

        <div class="space-y-3">
            <a href="{{ route('login') }}"
                class="block w-full bg-gradient-to-r from-[#197009] to-[#1dff28] text-white text-center px-6 py-3 rounded-lg font-semibold hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200">
                <i class="fas fa-sign-in-alt mr-2"></i> Login
            </a>

            @if (Route::has('register'))
                <a href="{{ route('register') }}"
                    class="block w-full bg-white border-2 border-[#8B6F47] text-[#8B6F47] text-center px-6 py-3 rounded-lg font-semibold hover:bg-[#f8f5f0] transition-all duration-200">
                    <i class="fas fa-user-plus mr-2"></i> Register
                </a>
            @endif

            <button @click="showLoginModal = false"
                class="block w-full text-gray-600 text-center px-6 py-2 rounded-lg font-medium hover:bg-gray-100 transition-all duration-200">
                Batal
            </button>
        </div>
    </div>
</div>

{{-- logout modal --}}
<div x-show="showLogoutModal" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50"
    @click.self="showLogoutModal = false" x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0">

    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 p-8 relative" @click.stop
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90"
        x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90">

        <button @click="showLogoutModal = false" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
            <i class="fas fa-times text-2xl"></i>
        </button>

        <div class="text-center mb-6">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-red-100 mb-4">
                <i class="fas fa-sign-out-alt text-red-600 text-2xl"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-800 mb-2">Logout</h3>
            <p class="text-gray-600">Apakah Anda yakin ingin keluar dari akun ini?</p>
        </div>

        <div class="space-y-3">
            <form action="{{ route('logout') }}" method="POST" class="w-full">
                @csrf
                <button type="submit"
                    class="block w-full bg-red-600 text-white text-center px-6 py-3 rounded-lg font-semibold hover:bg-red-700 transition-all duration-200">
                    <i class="fas fa-sign-out-alt mr-2"></i>Logout
                </button>
            </form>

            <button @click="showLogoutModal = false"
                class="block w-full bg-white border-2 border-gray-300 text-gray-700 text-center px-6 py-3 rounded-lg font-semibold hover:bg-gray-50 transition-all duration-200">
                <i class="fas fa-times mr-2"></i> Batal
            </button>
        </div>
    </div>
</div>

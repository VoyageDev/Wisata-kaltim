<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 shadow-sm">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <div class="flex items-center">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    @if (Auth::check() && Auth::user()->role === 'admin')
                        <x-nav-link :href="route('admin.booking.index')" :active="request()->routeIs('admin.booking.*')">
                            {{ __('Booking') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.artikel.index')" :active="request()->routeIs('admin.artikel.*')">
                            {{ __('Berita') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.wisata.index')" :active="request()->routeIs('admin.wisata.*')">
                            {{ __('Wisata') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.kota.index')" :active="request()->routeIs('admin.kota.*')">
                            {{ __('Kota') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.ulasan.index')" :active="request()->routeIs('admin.ulasan.*')">
                            {{ __('Ulasan') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="ml-auto flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-lg text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-[#8B6F47] transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }"
        class="hidden sm:hidden border-t border-gray-200 dark:border-gray-700">
        <div class="pt-3 pb-3 space-y-1 px-4">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="rounded-lg">
                <i class="fas fa-gauge-high mr-2 text-[#8B6F47]"></i>
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            @if (Auth::check() && Auth::user()->role === 'admin')
                <x-responsive-nav-link :href="route('admin.booking.index')" :active="request()->routeIs('admin.booking.*')" class="rounded-lg">
                    <i class="fas fa-newspaper mr-2 text-blue-600 dark:text-blue-400"></i>
                    {{ __('Booking') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.artikel.index')" :active="request()->routeIs('admin.artikel.*')" class="rounded-lg">
                    <i class="fas fa-newspaper mr-2 text-blue-600 dark:text-blue-400"></i>
                    {{ __('Berita') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.wisata.index')" :active="request()->routeIs('admin.wisata.*')" class="rounded-lg">
                    <i class="fas fa-map-marked-alt mr-2 text-green-600 dark:text-green-400"></i>
                    {{ __('Wisata') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.kota.index')" :active="request()->routeIs('admin.kota.*')" class="rounded-lg">
                    <i class="fas fa-city mr-2 text-purple-600 dark:text-purple-400"></i>
                    {{ __('Kota') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.ulasan.index')" :active="request()->routeIs('admin.ulasan.*')" class="rounded-lg">
                    <i class="fas fa-comments mr-2 text-orange-600 dark:text-orange-400"></i>
                    {{ __('Ulasan') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-3 border-t border-gray-200 dark:border-gray-700">
            <div class="px-4 mb-3">
                <div class="font-semibold text-base text-gray-800 dark:text-gray-200 flex items-center">
                    <i class="fas fa-user-circle mr-2 text-lg text-[#828282]"></i>
                    {{ Auth::user()->name }}
                </div>
                <div class="font-medium text-xs text-gray-500 mt-1">{{ Auth::user()->email }}</div>
            </div>

            <div class="space-y-1 px-4">
                <x-responsive-nav-link :href="route('profile.edit')" class="rounded-lg">
                    <i class="fas fa-user mr-2 text-[#8B6F47]"></i>
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                        class="rounded-lg text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20"
                        onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        <i class="fas fa-sign-out-alt mr-2"></i>
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>

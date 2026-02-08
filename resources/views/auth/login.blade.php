<x-guest-layout>
    <div class="fixed inset-0 -z-10">
        <img src="https://images.unsplash.com/photo-1506501139174-099022df5260?q=80&w=2071&auto=format&fit=crop"
            alt="Background" class="w-full h-full object-cover blur-md scale-110 brightness-50">
    </div>

    <div class="min-h-screen flex items-center justify-center p-4 sm:p-6">

        <div
            class="flex w-full xl:max-w-7xl bg-white shadow-2xl overflow-hidden rounded-lg xl:rounded-3xl lg:min-h-[650px]">

            <div class="hidden lg:flex lg:w-1/2 relative bg-gray-900">
                <img src="https://images.unsplash.com/photo-1506501139174-099022df5260?q=80&w=2071&auto=format&fit=crop"
                    alt="Background Wisata" class="absolute inset-0 w-full h-full object-cover opacity-80">

                <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent"></div>

                <div class="relative z-10 flex flex-col justify-center h-full px-12 xl:px-20 text-white">
                    <div class="mb-6">
                        <svg width="50" height="50" viewBox="0 0 50 50" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path d="M5 45L25 5L45 45" stroke="#10B981" stroke-width="4" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M15 45L25 25L35 45" stroke="#10B981" stroke-width="4" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </div>

                    <h1 class="text-4xl xl:text-5xl font-bold leading-tight mb-6">
                        Jelajahi Keindahan <br> Wisata
                    </h1>
                    <p class="text-lg text-gray-200 mb-8 leading-relaxed">
                        Temukan destinasi wisata alam terbaik Indonesia.
                    </p>
                </div>
            </div>

            <div
                class="w-full lg:w-1/2 flex flex-col justify-center bg-white dark:bg-gray-900 px-8 py-12 lg:px-16 xl:px-24 transition-colors duration-300">

                <div class="mb-8">
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Halo!</h2>
                    <p class="text-gray-500 dark:text-gray-400">Masuk untuk mengakses lebih banyak fitur fitur yang
                        unik.</p>
                </div>

                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div>
                        <x-input-label for="email" value="Email" class="dark:text-gray-300" />

                        <x-text-input id="email"
                            class="block mt-1 w-full bg-gray-100 dark:bg-gray-800 dark:text-white dark:border-gray-700 border-transparent focus:border-emerald-500 focus:bg-white dark:focus:bg-gray-800 focus:ring-0 rounded-lg placeholder-gray-400"
                            type="email" name="email" :value="old('email')" required autofocus
                            autocomplete="email" />

                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="password" value="Password" class="dark:text-gray-300" />

                        <x-text-input id="password"
                            class="block mt-1 w-full bg-gray-100 dark:bg-gray-800 dark:text-white dark:border-gray-700 border-transparent focus:border-emerald-500 focus:bg-white dark:focus:bg-gray-800 focus:ring-0 rounded-lg placeholder-gray-400"
                            type="password" name="password" required autocomplete="current-password" />

                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-between mt-4">
                        <label for="remember_me" class="inline-flex items-center cursor-pointer">
                            <input id="remember_me" type="checkbox"
                                class="rounded border-gray-300 dark:border-gray-700 dark:bg-gray-800 text-[#118cde] shadow-sm focus:ring-[#13a6c7]"
                                name="remember">
                            <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#8B3A10]"
                                href="{{ route('password.request') }}">
                                {{ __('Lupa password?') }}
                            </a>
                        @endif
                    </div>

                    <div class="mt-6">
                        <x-primary-button class="w-full justify-center py-3 bg-[#8B3A10] hover:bg-[#702E0C] text-white">
                            {{ __('Masuk') }}
                        </x-primary-button>
                    </div>

                    <div class="mt-6 text-center">
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Belum punya akun?
                            <a href="{{ route('register') }}"
                                class="font-bold text-[#8B3A10] hover:text-[#702E0C] hover:underline">
                                Daftar sekarang
                            </a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>

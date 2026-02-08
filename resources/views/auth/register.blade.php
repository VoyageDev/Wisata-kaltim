<x-guest-layout>
    <div class="fixed inset-0 -z-10">
        <img src="https://images.unsplash.com/photo-1506501139174-099022df5260?q=80&w=2071&auto=format&fit=crop"
             alt="Background"
             class="w-full h-full object-cover blur-md scale-110 brightness-50">
    </div>

    <div class="min-h-screen flex items-center justify-center p-4 sm:p-6">

        <div class="flex w-full xl:max-w-7xl bg-white shadow-2xl overflow-hidden rounded-lg xl:rounded-3xl lg:min-h-[700px]">

            <div class="hidden lg:flex lg:w-1/2 relative bg-gray-900">
                <img src="https://images.unsplash.com/photo-1506501139174-099022df5260?q=80&w=2071&auto=format&fit=crop"
                     alt="Background Wisata"
                     class="absolute inset-0 w-full h-full object-cover opacity-80">

                <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent"></div>

                <div class="relative z-10 flex flex-col justify-center h-full px-12 xl:px-20 text-white">
                    <div class="mb-6">
                        <svg width="50" height="50" viewBox="0 0 50 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M5 45L25 5L45 45" stroke="#10B981" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M15 45L25 25L35 45" stroke="#10B981" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>

                    <h1 class="text-4xl xl:text-5xl font-bold leading-tight mb-6">
                        Bergabunglah dalam <br> Petualangan
                    </h1>
                    <p class="text-lg text-gray-200 mb-8 leading-relaxed">
                        Buat akun sekarang dan mulailah menjelajahi keindahan alam Indonesia yang tak terlupakan.
                    </p>
                </div>
            </div>

            <div class="w-full lg:w-1/2 flex flex-col justify-center bg-white dark:bg-gray-900 px-8 py-12 lg:px-16 xl:px-24 transition-colors duration-300">

                <div class="mb-8">
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Buat Akun Baru</h2>
                    <p class="text-gray-500 dark:text-gray-400">Lengkapi data diri Anda untuk mendaftar.</p>
                </div>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="mb-4">
                        <x-input-label for="name" value="Nama Lengkap" class="dark:text-gray-300" />
                        <x-text-input id="name"
                            class="block mt-1 w-full bg-gray-100 dark:bg-gray-800 dark:text-white dark:border-gray-700 border-transparent focus:border-emerald-500 focus:bg-white dark:focus:bg-gray-800 focus:ring-0 rounded-lg placeholder-gray-400"
                            type="text"
                            name="name"
                            :value="old('name')"
                            required autofocus autocomplete="name"
                            placeholder="Masukkan nama lengkap Anda" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="email" value="Alamat Email" class="dark:text-gray-300" />
                        <x-text-input id="email"
                            class="block mt-1 w-full bg-gray-100 dark:bg-gray-800 dark:text-white dark:border-gray-700 border-transparent focus:border-emerald-500 focus:bg-white dark:focus:bg-gray-800 focus:ring-0 rounded-lg placeholder-gray-400"
                            type="email"
                            name="email"
                            :value="old('email')"
                            required autocomplete="username"
                            placeholder="Contoh: nama@email.com" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div class="mb-4" x-data="{ show: false }">
                        <x-input-label for="password" value="Password" class="dark:text-gray-300" />

                        <div class="relative mt-1">
                            <x-text-input id="password"
                                class="block w-full bg-gray-100 dark:bg-gray-800 dark:text-white dark:border-gray-700 border-transparent focus:border-emerald-500 focus:bg-white dark:focus:bg-gray-800 focus:ring-0 rounded-lg placeholder-gray-400"
                                ::type="show ? 'text' : 'password'"
                                name="password"
                                required autocomplete="new-password"
                                placeholder="Minimal 8 karakter" />

                            <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none">
                                <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <svg x-show="show" style="display: none;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                                </svg>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="mb-6" x-data="{ show: false }">
                        <x-input-label for="password_confirmation" value="Konfirmasi Password" class="dark:text-gray-300" />

                        <div class="relative mt-1">
                            <x-text-input id="password_confirmation"
                                class="block w-full bg-gray-100 dark:bg-gray-800 dark:text-white dark:border-gray-700 border-transparent focus:border-emerald-500 focus:bg-white dark:focus:bg-gray-800 focus:ring-0 rounded-lg placeholder-gray-400"
                                ::type="show ? 'text' : 'password'"
                                name="password_confirmation"
                                required autocomplete="new-password"
                                placeholder="Ulangi password Anda" />

                            <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none">
                                <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <svg x-show="show" style="display: none;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                                </svg>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                    <div class="mt-6">
                        <x-primary-button class="w-full justify-center py-3 bg-[#8B3A10] hover:bg-[#702E0C] text-white">
                            {{ __('Daftar Sekarang') }}
                        </x-primary-button>
                    </div>

                    <div class="mt-6 text-center">
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Sudah punya akun?
                            <a href="{{ route('login') }}" class="font-bold text-[#8B3A10] hover:text-[#702E0C] hover:underline">
                                Masuk di sini
                            </a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>

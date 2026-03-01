@extends('layouts.admin')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('Tambah User') }}
    </h2>
@endsection

@section('content')
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-8">
                <a href="{{ route('admin.user.index') }}"
                    class="text-[#8B6F47] dark:text-[#D4AF37] hover:underline font-medium flex items-center mb-4">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Tambah User Baru</h1>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 md:p-8">
                <form action="{{ route('admin.user.store') }}" method="POST" class="space-y-6">
                    @csrf

                    {{-- Nama --}}
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Nama
                            Lengkap</label>
                        <input type="text" id="name" name="name"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded-lg focus:ring-2 focus:ring-[#8B6F47]"
                            required value="{{ old('name') }}">
                        @error('name')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email"
                            class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Alamat Email</label>
                        <input type="email" id="email" name="email"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded-lg focus:ring-2 focus:ring-[#8B6F47]"
                            required value="{{ old('email') }}">
                        @error('email')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div>
                        <label for="password"
                            class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Password (Min. 8
                            Karakter)</label>
                        <input type="password" id="password" name="password"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded-lg focus:ring-2 focus:ring-[#8B6F47]"
                            required>
                        @error('password')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Role --}}
                    <div>
                        <label for="role" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Role
                            Akses</label>
                        <select id="role" name="role"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded-lg focus:ring-2 focus:ring-[#8B6F47]"
                            required>
                            <option value="member" {{ old('role') == 'member' ? 'selected' : '' }}>Member</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                        @error('role')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex gap-4 pt-4">
                        <button type="submit"
                            class="bg-gradient-to-r from-[#8B6F47] to-[#D4AF37] text-white px-6 py-2 rounded-lg font-semibold hover:shadow-lg transition-all duration-300">
                            <i class="fas fa-save mr-2"></i>Simpan User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

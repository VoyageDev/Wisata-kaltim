@extends('layouts.admin')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('Kelola User') }}
    </h2>
@endsection

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Header & Pencarian --}}
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Manajemen User</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">Kelola hak akses dan akun pengguna</p>
                </div>

                <div class="flex flex-col sm:flex-row items-center gap-4">
                    <form action="{{ route('admin.user.index') }}" method="GET" class="relative w-full sm:w-auto">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari nama atau email..."
                            class="w-full pl-10 pr-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-[#167509] focus:border-[#167509] outline-none dark:bg-gray-700 dark:text-white transition-all">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    </form>

                    <a href="{{ route('admin.user.create') }}"
                        class="w-full sm:w-auto bg-gradient-to-r from-[#074e0e] to-[#167509] text-white px-6 py-3 rounded-lg font-semibold hover:shadow-lg transition-all duration-300 inline-flex items-center justify-center whitespace-nowrap">
                        <i class="fas fa-plus mr-2"></i>Tambah User
                    </a>
                </div>
            </div>

            {{-- Alerts --}}
            @if (session('success'))
                <div
                    class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                    <p class="text-green-800 dark:text-green-300 font-medium flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                    </p>
                </div>
            @endif
            @if (session('error'))
                <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                    <p class="text-red-800 dark:text-red-300 font-medium flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
                    </p>
                </div>
            @endif

            {{-- Tabel --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gradient-to-r from-[#1a0033] via-[#330066] to-[#000428] text-white">
                            <tr>
                                <th class="px-6 py-4 text-left text-sm font-semibold">Pengguna</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold">Role</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold">Terdaftar</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($users as $user)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-gray-900 dark:text-gray-100">{{ $user->name }}</div>
                                        <div class="text-gray-500 dark:text-gray-400 text-sm">{{ $user->email }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($user->role === 'admin')
                                            <span
                                                class="inline-block bg-purple-100 text-purple-800 text-xs px-3 py-1 rounded-full font-bold">Admin</span>
                                        @else
                                            <span
                                                class="inline-block bg-blue-100 text-blue-800 text-xs px-3 py-1 rounded-full font-bold">Member</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                        {{ $user->created_at->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex gap-3">
                                            <a href="{{ route('admin.user.edit', $user) }}"
                                                class="text-blue-600 hover:text-blue-900 font-medium text-sm">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <form action="{{ route('admin.user.destroy', $user) }}" method="POST"
                                                onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="text-red-600 hover:text-red-900 font-medium text-sm">
                                                    <i class="fas fa-trash"></i> Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan=\"4\" class=\"px-6 py-12 text-center\">
                                        <i class=\"fas fa-users text-gray-300 dark:text-gray-600 text-5xl mb-4 block\"></i>
                                        <p class=\"text-gray-500 dark:text-gray-400 font-medium\">Belum ada data user</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($users->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                        {{ $users->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

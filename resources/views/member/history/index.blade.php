<x-layouts.user>
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-2">History Saya</h1>
            <p class="text-gray-600">Kelola pemesanan dan ulasan wisata Anda</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">

            <a href="{{ route('history.index', ['tab' => 'bookings']) }}"
                class="bg-white p-4 rounded-lg shadow hover:shadow-lg transition cursor-pointer
        {{ !request('status') ? 'ring-2 ring-blue-500 bg-blue-50' : '' }}">
                <div class="text-sm text-gray-600 mb-1">Total Pemesanan</div>
                <div class="text-2xl font-bold text-blue-600">{{ $bookingsStats['all'] }}</div>
            </a>

            <a href="{{ route('history.index', ['tab' => 'bookings', 'status' => 'pending']) }}"
                class="bg-white p-4 rounded-lg shadow hover:shadow-lg transition cursor-pointer
        {{ request('status') == 'pending' ? 'ring-2 ring-yellow-500 bg-yellow-50' : '' }}">
                <div class="text-sm text-gray-600 mb-1">Pending</div>
                <div class="text-2xl font-bold text-yellow-600">{{ $bookingsStats['pending'] }}</div>
            </a>

            <a href="{{ route('history.index', ['tab' => 'bookings', 'status' => 'paid']) }}"
                class="bg-white p-4 rounded-lg shadow hover:shadow-lg transition cursor-pointer
        {{ request('status') == 'paid' ? 'ring-2 ring-green-500 bg-green-50' : '' }}">
                <div class="text-sm text-gray-600 mb-1">Dibayar</div>
                <div class="text-2xl font-bold text-green-600">{{ $bookingsStats['paid'] }}</div>
            </a>

            <a href="{{ route('history.index', ['tab' => 'bookings', 'status' => 'done']) }}"
                class="bg-white p-4 rounded-lg shadow hover:shadow-lg transition cursor-pointer
        {{ request('status') == 'done' ? 'ring-2 ring-indigo-500 bg-indigo-50' : '' }}">
                <div class="text-sm text-gray-600 mb-1">Selesai</div>
                <div class="text-2xl font-bold text-indigo-600">{{ $bookingsStats['done'] }}</div>
            </a>

            <a href="{{ route('history.index', ['tab' => 'bookings', 'status' => 'cancelled']) }}"
                class="bg-white p-4 rounded-lg shadow hover:shadow-lg transition cursor-pointer
        {{ request('status') == 'cancelled' ? 'ring-2 ring-red-500 bg-red-50' : '' }}">
                <div class="text-sm text-gray-600 mb-1">Dibatalkan</div>
                <div class="text-2xl font-bold text-red-600">{{ $bookingsStats['cancelled'] }}</div>
            </a>
        </div>

        <!-- Tabs -->
        <div class="bg-white rounded-lg shadow">
            <div class="border-b border-gray-200">
                <div class="flex gap-4 px-6">
                    <a href="{{ route('history.index', ['tab' => 'bookings']) }}"
                        class="py-4 px-4 font-medium transition {{ $tab === 'bookings' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-600 hover:text-gray-900' }}">
                        📅 Pemesanan
                    </a>
                    <a href="{{ route('history.index', ['tab' => 'ulasans']) }}"
                        class="py-4 px-4 font-medium transition {{ $tab === 'ulasans' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-600 hover:text-gray-900' }}">
                        💬 Ulasan ({{ $ulasanCount }})
                    </a>
                </div>
            </div>

            {{-- isi history --}}
            <div class="p-6">
                @if ($tab === 'bookings')
                    @include('member.history.partials.bookings-list', ['bookings' => $bookings])
                @elseif ($tab === 'ulasans')
                    @include('member.history.partials.ulasans-list', ['ulasans' => $ulasans])
                @endif
            </div>
        </div>
    </div>
</x-layouts.user>

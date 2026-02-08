<x-layouts.user>
    <div class="container mx-auto px-4 py-8">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('history.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                ← Kembali ke History
            </a>
        </div>

        <!-- Booking Header -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">
                        <a href="{{ route('wisata.detail', $booking->wisata->slug) }}" class="hover:underline">
                            {{ $booking->wisata->name ?? 'Wisata Tidak Tersedia' }}
                        </a>
                    </h1>
                    <p class="text-gray-600">Kode Tiket: <span
                            class="font-mono font-bold">{{ $booking->kode_tiket }}</span>
                    </p>
                </div>
                <span
                    class="px-4 py-2 rounded-lg text-lg font-bold
                @if ($booking->status === 'pending') bg-yellow-100 text-yellow-800
                @elseif ($booking->status === 'paid') bg-green-100 text-green-800
                @elseif ($booking->status === 'done') bg-blue-100 text-blue-800
                @elseif ($booking->status === 'cancelled') bg-red-100 text-red-800 @endif">
                    {{ ucfirst($booking->status) }}
                </span>
            </div>

            <!-- Booking Details -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 pb-6 border-b">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Tanggal Kunjungan</p>
                    <p class="text-lg font-bold text-gray-900">{{ $booking->tanggal_kunjungan->format('d M Y') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">Jumlah Orang</p>
                    <p class="text-lg font-bold text-gray-900">{{ $booking->jumlah_orang }} orang</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">Harga/Orang (Reguler)</p>
                    <p class="text-lg font-bold text-gray-900">Rp
                        {{ number_format($booking->wisata->harga_tiket, 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Harga</p>
                    <p class="text-lg font-bold text-blue-600">Rp
                        {{ number_format($booking->total_harga, 0, ',', '.') }}
                    </p>
                </div>
            </div>

            <!-- Paket Info -->
            @if ($booking->paketWisata)
                <div class="mt-6 pt-6 border-t w-full lg:w-1/3">
                    <h3 class="font-semibold text-gray-900 mb-3">Paket yang Dipilih</h3>
                    <div class="bg-gray-50 p-4 rounded">
                        <p class="text-gray-900 font-medium">{{ $booking->paketWisata->name }}</p>
                        <p class="text-sm text-gray-600 mt-1">{{ $booking->paketWisata->jumlah_orang }} orang per paket
                        </p>
                        <p class="text-blue-600 font-bold mt-2">Rp
                            {{ number_format($booking->paketWisata->harga_paket, 0, ',', '.') }}</p>
                    </div>
                </div>
            @endif

            <!-- Price Breakdown -->
            <div class="mt-6 pt-6 border-t w-full lg:w-1/4">
                <h3 class="font-semibold text-gray-900 mb-3">Rincian Harga</h3>
                <div class="bg-gray-50 p-4 rounded space-y-2">
                    @php
                        $hargaPerTiket = $booking->total_harga / $booking->jumlah_tiket;
                        $hargaRegularTotal = $booking->wisata->harga_tiket * $booking->jumlah_tiket;
                        $savings = $hargaRegularTotal - $booking->total_harga;
                        $savingsPercent = $hargaRegularTotal > 0 ? ($savings / $hargaRegularTotal) * 100 : 0;
                    @endphp

                    <div class="flex justify-between">
                        <span class="text-gray-600">Harga per Tiket:</span>
                        <span class="font-semibold">Rp
                            {{ number_format($hargaPerTiket, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Jumlah Tiket:</span>
                        <span class="font-semibold">{{ $booking->jumlah_tiket }} tiket</span>
                    </div>

                    @if ($booking->paketWisata && $savings > 0)
                        <div class="flex justify-between text-green-600 pt-2 border-t">
                            <span class="font-medium">Hemat dari Harga Reguler:</span>
                            <span class="font-semibold">Rp {{ number_format($savings, 0, ',', '.') }}
                                <span class="text-sm">({{ number_format($savingsPercent, 1, ',', '.') }}%)</span>
                            </span>
                        </div>
                    @endif

                    <div class="flex justify-between border-t pt-2 font-bold text-lg">
                        <span>Total Bayar:</span>
                        <span class="text-blue-600">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-6 pt-6 border-t flex gap-4">
                @if ($booking->status === 'pending')
                    <form action="{{ route('history.booking.cancel', $booking) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" onclick="return confirm('Yakin ingin membatalkan pemesanan ini?')"
                            class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 font-medium">
                            Batalkan Pemesanan
                        </button>
                    </form>
                @endif
                @if ($booking->status === 'paid')
                    <form action="{{ route('history.booking.complete', $booking) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit"
                            onclick="return confirm('Apakah Anda yakin ingin menyelesaikan pesanan ini? Status akan berubah menjadi Selesai.')"
                            class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 font-medium">
                            Tandai sebagai Selesai
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <!-- Payment History -->
        @if ($booking->payments->count() > 0)
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Status Pembayaran</h2>
                <div class="space-y-3">
                    @foreach ($booking->payments as $payment)
                        <div class="flex justify-between items-center p-4 border border-gray-200 rounded-lg">
                            <div class="flex items-center gap-3">
                                <img src="https://i.pinimg.com/736x/b4/8d/5c/b48d5c693587925fd743e9b69d306125.jpg"
                                    alt="" class="w-10 h-10 object-cover rounded mr-3">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $payment->metode }}</p>
                                    <p class="text-sm text-gray-600">
                                        @if ($payment->paymentChannel)
                                            {{ $payment->paymentChannel->name }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-lg font-bold text-blue-600">Rp
                                    {{ number_format($payment->jumlah, 0, ',', '.') }}</p>
                                <span
                                    class="inline-block px-3 py-1 rounded text-sm font-medium mt-1
                                @if ($payment->status === 'paid') bg-green-100 text-green-800
                                @elseif ($payment->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif ($payment->status === 'failed') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst($payment->status) }}
                                </span>
                            </div>
                        </div>
                </div>
        @endforeach
    </div>
    </div>
    @endif
</x-layouts.user>

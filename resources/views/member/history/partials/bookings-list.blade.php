<!-- Bookings List -->
@if ($bookings->count() > 0)
    <div class="space-y-4">
        @foreach ($bookings as $booking)
            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">
                            {{ $booking->wisata->name ?? 'Wisata Tidak Tersedia' }}
                        </h3>
                        <p class="text-sm text-gray-600">Kode: {{ $booking->kode_tiket }}</p>
                    </div>
                    <span
                        class="px-3 py-1 rounded-full text-sm font-medium
                        @if ($booking->status === 'pending') bg-yellow-100 text-yellow-800
                        @elseif ($booking->status === 'paid') bg-green-100 text-green-800
                        @elseif ($booking->status === 'done') bg-blue-100 text-blue-800
                        @elseif ($booking->status === 'cancelled') bg-red-100 text-red-800 @endif">
                        {{ ucfirst($booking->status) }}
                    </span>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4 text-sm">
                    <div>
                        <p class="text-gray-600">Tanggal Kunjungan</p>
                        <p class="font-semibold">{{ $booking->tanggal_kunjungan->format('d M Y') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Jumlah Orang</p>
                        <p class="font-semibold">{{ $booking->jumlah_orang }} orang</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Harga/Orang (Reguler)</p>
                        <p class="font-semibold">Rp
                            {{ number_format($booking->wisata->harga_tiket, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Total Harga</p>
                        <p class="font-semibold text-blue-600">Rp
                            {{ number_format($booking->total_harga, 0, ',', '.') }}</p>
                    </div>
                </div>

                <div class="flex gap-3">
                    <a href="{{ route('history.booking.show', $booking) }}"
                        class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        Lihat Detail →
                    </a>
                    @if ($booking->status === 'pending')
                        <form action="{{ route('history.booking.cancel', $booking) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" onclick="return confirm('Yakin ingin membatalkan pemesanan ini?')"
                                class="text-red-600 hover:text-red-800 text-sm font-medium">
                                Batalkan
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @endforeach

        <!-- Pagination -->
        <div class="mt-6">
            {{ $bookings->links() }}
        </div>
    </div>
@else
    <div class="text-center py-12">
        <div class="text-6xl mb-4">📭</div>
        <h3 class="text-xl font-semibold text-gray-900 mb-2">Belum Ada Pemesanan</h3>
        <p class="text-gray-600 mb-4">Mulai jelajahi wisata dan buat pemesanan pertama Anda</p>
        <a href="{{ route('wisata.index') }}"
            class="inline-block bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
            Jelajahi Wisata
        </a>
    </div>
@endif

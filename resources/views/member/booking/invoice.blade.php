<x-layouts.user>
    <form action="{{ route('payment.store') }}" method="POST" id="paymentForm">
        @csrf
        <input type="hidden" name="booking_id" value="{{ $booking->id }}">
        <div class="min-h-screen bg-gray-100 font-sans max-w-3xl mx-auto">
            <main class="mx-auto mt-5 p-4">
                <div class="bg-white rounded-lg shadow-lg p-8">
                    <div class="text-center mb-8">
                        <h1 class="text-3xl font-bold mb-2">Invoice Pemesanan</h1>
                        <p class="text-gray-600">Kode Tiket: <span
                                class="font-bold text-blue-600">{{ $booking->kode_tiket }}</span></p>
                    </div>

                    <div class="border-t-2 border-b-2 py-4 mb-6">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-gray-600 text-sm">Nama Pemesan</p>
                                <p class="font-bold">{{ $booking->user->name }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600 text-sm">Tanggal Pemesanan</p>
                                <p class="font-bold">{{ $booking->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600 text-sm">Tanggal Kunjungan</p>
                                <p class="font-bold">{{ $booking->tanggal_kunjungan->format('d/m/Y') }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600 text-sm">Status</p>
                                <p class="font-bold">
                                    @php
                                        $statusColors = [
                                            'pending' => '#FFDE00',
                                            'paid' => '#10b981',
                                            'done' => '#3b82f6',
                                            'cancelled' => '#ef4444',
                                        ];
                                        $bgColor = $statusColors[$booking->status] ?? '#6b7280';
                                    @endphp
                                    <span class="px-3 py-1 rounded-full text-white text-sm"
                                        style="background-color: {{ $bgColor }}">
                                        {{ strtoupper($booking->status) }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-6">
                        <h2 class="text-xl font-bold mb-4">Detail Pemesanan</h2>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <p class="text-gray-600">Wisata</p>
                                <p class="font-bold">{{ $booking->wisata->name }}</p>
                            </div>
                            <div class="flex justify-between">
                                <p class="text-gray-600">Jumlah Orang</p>
                                <p class="font-bold">{{ $booking->jumlah_orang }} orang</p>
                            </div>
                            @if ($booking->paketWisata)
                                <div class="flex justify-between">
                                    <p class="text-gray-600">Paket</p>
                                    <p class="font-bold">{{ $booking->paketWisata->name }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="mb-6">
                        <h2 class="text-xl font-bold mb-4">Pilih Metode Pembayaran</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach ($channels as $channel)
                                <label class="cursor-pointer">
                                    <input type="radio" name="payment_channel_id" value="{{ $channel->id }}"
                                        class="peer sr-only" required>
                                    <div
                                        class="p-4 rounded-xl border-2 border-gray-200 peer-checked:border-blue-500 peer-checked:bg-blue-50 hover:bg-gray-50 transition-all flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <div>
                                                <p class="font-bold text-gray-700">{{ $channel->name }}</p>
                                                <p class="text-xs text-gray-500 uppercase">
                                                    {{ str_replace('_', ' ', $channel->type) }}</p>
                                            </div>
                                        </div>
                                        <div class="hidden peer-checked:block text-blue-600">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7" />
                                            </svg>
                                        </div>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-lg mb-6">
                        <div class="flex justify-between items-center">
                            <p class="text-lg font-bold">Total Harga</p>
                            <p class="text-2xl font-bold text-green-600">Rp
                                {{ number_format($booking->total_harga, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <div class="flex gap-4 justify-center">
                        <a href="{{ route('booking.index') }}"
                            class="px-6 py-2 bg-gray-400 text-white rounded-lg hover:bg-gray-500">
                            Kembali
                        </a>
                        <button type="submit" id="submitPayment" disabled
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:bg-gray-400 disabled:cursor-not-allowed transition">
                            Lanjut Pembayaran
                        </button>
                    </div>
                </div>
            </main>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('paymentForm');
            const submitButton = document.getElementById('submitPayment');
            const paymentChannels = document.querySelectorAll('input[name="payment_channel_id"]');

            // Enable/disable button based on payment channel selection
            paymentChannels.forEach(radio => {
                radio.addEventListener('change', function() {
                    submitButton.disabled = false;
                });
            });

            // Form validation before submit
            form.addEventListener('submit', function(e) {
                const selectedChannel = document.querySelector('input[name="payment_channel_id"]:checked');
                if (!selectedChannel) {
                    e.preventDefault();
                    alert('Silakan pilih metode pembayaran terlebih dahulu');
                }
            });
        });
    </script>
</x-layouts.user>

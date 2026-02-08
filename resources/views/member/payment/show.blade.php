<x-layouts.user>
    <div class="min-h-screen bg-gray-100 font-sans max-w-4xl mx-auto">
        <main class="mx-auto mt-5 p-4">
            <div class="bg-white rounded-lg shadow-lg p-8">
                <!-- Header -->
                <div class="text-center mb-8">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 rounded-full mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                    </div>
                    <h1 class="text-3xl font-bold mb-2">Menunggu Pembayaran</h1>
                    <p class="text-gray-600">Selesaikan pembayaran sebelum melanjutkan</p>
                </div>

                <!-- Status Badge -->
                <div class="text-center mb-6">
                    @php
                        $statusColors = [
                            'pending' => [
                                'bg' => 'bg-yellow-100',
                                'text' => 'text-yellow-800',
                                'label' => 'MENUNGGU PEMBAYARAN',
                            ],
                            'paid' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'label' => 'SUDAH DIBAYAR'],
                            'cancelled' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'label' => 'DIBATALKAN'],
                            'expired' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'label' => 'KADALUARSA'],
                        ];
                        $status = $statusColors[$payment->status] ?? [
                            'bg' => 'bg-gray-100',
                            'text' => 'text-gray-800',
                            'label' => strtoupper($payment->status),
                        ];
                    @endphp
                    <span
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-bold {{ $status['bg'] }} {{ $status['text'] }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-clock">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path
                                d="M17 3.34a10 10 0 1 1 -14.995 8.984l-.005 -.324l.005 -.324a10 10 0 0 1 14.995 -8.336zm-5 2.66a1 1 0 0 0 -.993 .883l-.007 .117v5l.009 .131a1 1 0 0 0 .197 .477l.087 .1l3 3l.094 .082a1 1 0 0 0 1.226 0l.094 -.083l.083 -.094a1 1 0 0 0 0 -1.226l-.083 -.094l-2.707 -2.708v-4.585l-.007 -.117a1 1 0 0 0 -.993 -.883z" />
                        </svg>
                        {{ $status['label'] }}
                    </span>
                </div>

                <!-- Booking Info -->
                <div class="bg-gray-50 rounded-lg p-6 mb-6">
                    <h3 class="font-bold text-lg mb-4">Detail Pesanan</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Kode Tiket</span>
                            <div class="flex items-center gap-2 cursor-pointer group"
                                onclick="copyToClipboard('{{ $payment->booking->kode_tiket }}')"
                                title="Klik untuk menyalin">
                                <span
                                    class="font-bold group-hover:text-blue-600 transition">{{ $payment->booking->kode_tiket }}</span>
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-4 w-4 text-gray-400 group-hover:text-blue-600 transition" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Wisata</span>
                            <div class="flex items-center gap-2 cursor-pointer group">
                                <span
                                    class="font-bold group-hover:text-blue-600 transition">{{ $payment->booking->wisata->name }}</span>
                            </div>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Tanggal Kunjungan</span>
                            <span class="font-bold">{{ $payment->booking->tanggal_kunjungan->format('d/m/Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Jumlah Orang</span>
                            <span class="font-bold">{{ $payment->booking->jumlah_orang }} orang</span>
                        </div>
                    </div>
                </div>

                <!-- Payment Method Info -->
                <div class="bg-blue-50 border-2 border-blue-200 rounded-lg p-6 mb-6">
                    <h3 class="font-bold text-lg mb-4 text-blue-900">Metode Pembayaran</h3>
                    <div class="bg-white rounded-lg p-4 mb-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-bold text-xl text-gray-800">{{ $payment->paymentChannel->name }}</span>
                            <span class="text-xs bg-blue-100 text-blue-800 px-3 py-1 rounded-full font-semibold">
                                {{ strtoupper(str_replace('_', ' ', $payment->paymentChannel->type)) }}
                            </span>
                        </div>

                        @if ($payment->paymentChannel->code === 'qris')
                            <!-- QRIS Code -->
                            <div class="text-center py-6">
                                <p class="text-sm text-gray-600 mb-4">Scan QR Code di bawah ini untuk membayar</p>
                                <div class="inline-block bg-white p-4 rounded-lg shadow-md">
                                    <img id="qrisImage" src="{{ asset('images/qris-placeholder.png') }}"
                                        alt="QRIS Code" class="w-64 h-64 object-contain mx-auto"
                                        onerror="this.onerror=null; this.src='https://api.qrserver.com/v1/create-qr-code/?size=256x256&data={{ urlencode($payment->booking->kode_tiket) }}'">
                                </div>
                                <div class="mt-4">
                                    <button type="button" onclick="downloadQris()"
                                        class="text-blue-600 hover:text-blue-800 text-sm font-semibold flex items-center justify-center gap-2 mx-auto transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                        Unduh QRIS
                                    </button>
                                </div>
                                <p class="text-xs text-gray-500 mt-4">
                                    Gunakan aplikasi pembayaran yang mendukung QRIS
                                </p>
                            </div>
                        @elseif($payment->paymentChannel->type === 'bank_transfer')
                            <!-- Bank Transfer -->
                            <div class="space-y-4 mt-4">
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Nomor Rekening</p>
                                    <div class="flex items-center justify-between bg-gray-50 p-3 rounded-lg">
                                        <span
                                            class="font-mono text-lg font-bold">{{ $payment->paymentChannel->account_number }}</span>
                                        <button type="button"
                                            onclick="copyToClipboard('{{ $payment->paymentChannel->account_number }}')"
                                            class="text-blue-600 hover:text-blue-800 text-sm font-semibold">
                                            SALIN
                                        </button>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Atas Nama</p>
                                    <div class="bg-gray-50 p-3 rounded-lg">
                                        <span class="font-bold">{{ $payment->paymentChannel->account_name }}</span>
                                    </div>
                                </div>
                                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mt-4">
                                    <p class="text-sm text-yellow-800">
                                        <strong>⚠️ Penting:</strong> Transfer tepat sesuai nominal di bawah agar
                                        otomatis terverifikasi
                                    </p>
                                </div>
                            </div>
                        @elseif($payment->paymentChannel->type === 'e_wallet')
                            <!-- E-Wallet -->
                            <div class="space-y-4 mt-4">
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Nomor E-Wallet</p>
                                    <div class="flex items-center justify-between bg-gray-50 p-3 rounded-lg">
                                        <span
                                            class="font-mono text-lg font-bold">{{ $payment->paymentChannel->account_number }}</span>
                                        <button type="button"
                                            onclick="copyToClipboard('{{ $payment->paymentChannel->account_number }}')"
                                            class="text-blue-600 hover:text-blue-800 text-sm font-semibold">
                                            SALIN
                                        </button>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Atas Nama</p>
                                    <div class="bg-gray-50 p-3 rounded-lg">
                                        <span class="font-bold">{{ $payment->paymentChannel->account_name }}</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Total Amount -->
                <div class="bg-gradient-to-r from-green-500 to-emerald-600 rounded-lg p-6 mb-6 text-white">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm opacity-90 mb-1">Total Pembayaran</p>
                            <p class="text-3xl font-bold">Rp {{ number_format($payment->jumlah, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Instructions -->
                <div class="bg-gray-50 rounded-lg p-6 mb-6">
                    <h3 class="font-bold text-lg mb-4">Instruksi Pembayaran</h3>
                    <ol class="list-decimal list-inside space-y-2 text-gray-700">
                        <li>Lakukan pembayaran sesuai metode yang dipilih</li>
                        <li>Pastikan nominal pembayaran sesuai dengan total yang tertera</li>
                        <li>Setelah melakukan pembayaran, klik tombol "Saya Sudah Bayar"</li>
                        <li>Tiket akan terbit setelah pembayaran dikonfirmasi</li>
                    </ol>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-4 justify-center">
                    <a href="{{ route('history.index') }}"
                        class="px-6 py-3 bg-gray-400 text-white rounded-lg hover:bg-gray-500 transition">
                        Kembali ke History
                    </a>
                    @if ($payment->status === 'pending')
                        <form action="{{ route('payment.confirm', $payment->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit"
                                class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-semibold">
                                ✓ Saya Sudah Bayar
                            </button>
                        </form>
                    @elseif ($payment->status === 'cancelled')
                        <div class="text-center">
                            <p class="text-red-600 font-semibold">Pembayaran telah dibatalkan</p>
                        </div>
                    @endif
                </div>
            </div>
        </main>
    </div>

    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                // Show notification
                const notification = document.createElement('div');
                notification.className =
                    'fixed top-4 right-4 bg-green-600 text-white px-6 py-3 rounded-lg shadow-lg z-50';
                notification.textContent = 'Berhasil disalin!';
                document.body.appendChild(notification);

                setTimeout(() => {
                    notification.remove();
                }, 2000);
            });
        }

        async function downloadQris() {
            try {
                // Ambil elemen gambar berdasarkan ID
                const image = document.getElementById('qrisImage');
                if (!image) return;
                const imageSrc = image.src;

                const response = await fetch(imageSrc);
                const blob = await response.blob();
                const url = window.URL.createObjectURL(blob);
                const link = document.createElement('a');
                link.href = url;

                // Nama file saat didownload
                link.download = 'QRIS-Pembayaran-{{ $payment->booking->kode_tiket }}.png';

                // Klik otomatis
                document.body.appendChild(link);
                link.click();

                // Bersihkan
                document.body.removeChild(link);
                window.URL.revokeObjectURL(url);

            } catch (error) {
                console.error('Gagal download gambar:', error);
                alert("Gagal mengunduh otomatis. Gambar akan dibuka di tab baru.");
                const image = document.getElementById('qrisImage');
                if (image) window.open(image.src, '_blank');
            }
        }
    </script>
</x-layouts.user>

<x-layouts.user>
    <div class="min-h-screen bg-gray-100 font-sans max-w-7xl mx-auto">
        <main class="mx-auto mt-5 p-4">
            <div class="inline-block bg-[#60a5fa] text-white px-6 py-2 rounded-2xl shadow-md mb-2">
                <h1 class="text-xl font-bold italic">Form Pemesanan</h1>
                <p class="text-sm mt-1">Total Kota Tersedia: <span class="font-bold">{{ $kotas->count() }}</span></p>
            </div>

            <div class="flex flex-col md:flex-row rounded-3xl overflow-hidden shadow-xl">

                <div class="w-full md:w-1/3 bg-[#69db86] p-8">
                    <h2 class="text-2xl font-bold mb-6">Pilih Wisata</h2>

                    <form method="GET" action="{{ route('booking.index') }}" id="bookingForm">
                        <div class="space-y-4">
                            {{-- dropdown kota --}}
                            <div>
                                <label class="block font-semibold mb-1">Kota</label>
                                <select name="kota_id" onchange="document.getElementById('bookingForm').submit()"
                                    class="w-full p-3 rounded-full border-none focus:ring-2 focus:ring-green-400 appearance-none bg-white">
                                    <option value="">-- Pilih Kota --</option>
                                    @foreach ($kotas as $kota)
                                        <option value="{{ $kota->id }}"
                                            {{ $selectedKotaId == $kota->id ? 'selected' : '' }}>
                                            {{ $kota->name }} ({{ $kota->wisatas->count() }} wisata)
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- dropdown wisata (choose kota first) --}}
                            <div>
                                <label class="block font-semibold mb-1">Wisata</label>
                                <select name="wisata_id" onchange="document.getElementById('bookingForm').submit()"
                                    {{ !$selectedKotaId ? 'disabled' : '' }}
                                    class="w-full p-3 rounded-full border-none focus:ring-2 focus:ring-green-400 appearance-none bg-white disabled:bg-gray-200 disabled:cursor-not-allowed">
                                    <option value="">-- Pilih Wisata --</option>
                                    @foreach ($availableWisatas as $wisata)
                                        <option value="{{ $wisata->id }}"
                                            {{ $selectedWisataId == $wisata->id ? 'selected' : '' }}>
                                            {{ $wisata->name }} - Rp
                                            {{ number_format($wisata->harga_tiket, 0, ',', '.') }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </form>

                    <!-- Wisata Info -->
                    @if ($selectedWisata)
                        <div class="bg-white rounded-lg p-4 mt-4">
                            <h3 class="font-bold mb-2">{{ $selectedWisata->name }}</h3>
                            <p class="text-sm text-gray-600 mb-2">{{ Str::limit($selectedWisata->description, 100) }}
                            </p>
                            <div class="text-lg font-bold text-green-600">
                                Harga: Rp {{ number_format($selectedWisata->harga_tiket, 0, ',', '.') }} / Tiket
                            </div>
                        </div>
                    @endif
                </div>

                <div class="w-full md:w-2/3 bg-[#c98d3f] p-8 relative">
                    <h2 class="text-2xl font-bold mb-6">Tipe Pemesanan</h2>

                    @if (!$selectedWisata)
                        <div class="bg-white rounded-2xl p-6 text-center text-gray-500">
                            Silakan pilih kota dan wisata terlebih dahulu
                        </div>
                    @else
                        <form method="POST" action="{{ route('booking.store') }}" class="space-y-6"
                            x-data="{
                                bookingType: '{{ old('paket_wisata_id') ? 'paket' : (old('jumlah_orang') ? 'regular' : '') }}',
                                jumlahOrang: '{{ old('jumlah_orang') }}',
                                tanggalKunjungan: '{{ old('tanggal_kunjungan') }}',
                                selectedPaketId: {{ old('paket_wisata_id', 'null') }},
                                hargaTiket: {{ $selectedWisata->harga_tiket ?? 0 }},
                            
                                // Fungsi saat mengetik di input reguler
                                inputRegular() {
                                    if (this.jumlahOrang > 0) {
                                        this.bookingType = 'regular';
                                        this.selectedPaketId = null; // Reset paket
                                    } else {
                                        this.bookingType = ''; // Reset jika kosong
                                    }
                                },
                            
                                // Fungsi saat memilih paket
                                selectPaket(id) {
                                    // Jika diklik lagi paket yg sama (deselect)
                                    if (this.selectedPaketId === id) {
                                        this.selectedPaketId = null;
                                        this.bookingType = '';
                                    } else {
                                        this.selectedPaketId = id;
                                        this.bookingType = 'paket';
                                        this.jumlahOrang = ''; // Reset input reguler
                                    }
                                },
                            
                                // Hitung total harga reguler secara live
                                get totalReguler() {
                                    return (this.jumlahOrang * this.hargaTiket).toLocaleString('id-ID');
                                }
                            }">

                            @csrf
                            <input type="hidden" name="wisata_id" value="{{ $selectedWisata->id }}">
                            <input type="hidden" name="paket_wisata_id" :value="selectedPaketId">

                            <!-- Tanggal Kunjungan Required -->
                            <div class="bg-white rounded-2xl p-6 shadow-sm">
                                <div class="flex items-center gap-2 text-blue-600 font-bold mb-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-event">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path
                                            d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2l0 -12" />
                                        <path d="M16 3l0 4" />
                                        <path d="M8 3l0 4" />
                                        <path d="M4 11l16 0" />
                                        <path d="M8 15h2v2h-2l0 -2" />
                                    </svg>
                                    Pilih Tanggal Kunjungan <span class="text-red-500">*</span>
                                </div>
                                <input type="date" name="tanggal_kunjungan" x-model="tanggalKunjungan"
                                    min="{{ now()->format('Y-m-d') }}"
                                    class="w-full p-3 rounded-full border-none focus:ring-2 focus:ring-blue-400 appearance-none bg-white">
                            </div>

                            <div class="bg-white rounded-2xl p-6 shadow-sm transition-all duration-300"
                                :class="{ 'opacity-50 grayscale cursor-not-allowed': bookingType === 'paket' }">

                                <div class="flex items-center gap-2 text-blue-600 font-bold mb-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Pilih Harga Reguler
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="text-xs font-bold text-gray-500">Jumlah Orang</label>
                                        <input type="number" name="jumlah_orang" x-model="jumlahOrang"
                                            @input="inputRegular()" :disabled="bookingType === 'paket'"
                                            min="1" placeholder="-"
                                            class="w-full bg-gray-200 rounded p-2 border-none mt-1 text-center focus:ring-2 focus:ring-blue-400 disabled:cursor-not-allowed">
                                    </div>
                                    <div>
                                        <label class="text-xs font-bold text-gray-500">Total Harga</label>
                                        <div
                                            class="bg-gray-200 rounded p-2 mt-1 text-gray-700 font-semibold text-center">
                                            Rp <span x-text="jumlahOrang ? totalReguler : '-'"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-white rounded-2xl p-6 shadow-sm transition-all duration-300"
                                :class="{ 'opacity-50 grayscale pointer-events-none': bookingType === 'regular' }">

                                <div class="flex items-center gap-2 text-green-600 font-bold mb-4">
                                    <span>🎁</span> Pilih Paket Bundling
                                    @if ($availablePakets->count() > 0)
                                        <span class="text-sm font-normal text-gray-600">
                                            ({{ $availablePakets->count() }} paket tersedia)
                                        </span>
                                    @endif
                                </div>

                                @if ($availablePakets->count() === 0)
                                    <div class="text-center text-gray-500 py-4">
                                        Tidak ada paket bundling untuk wisata ini
                                    </div>
                                @else
                                    <div
                                        class="grid grid-cols-1 sm:grid-cols-2 gap-4 max-h-[450px] overflow-y-auto pr-1">
                                        @foreach ($availablePakets as $paket)
                                            <div @click="bookingType !== 'regular' && selectPaket({{ $paket->id }})"
                                                class="cursor-pointer group relative h-full">

                                                <div class="bg-white rounded-xl border transition-all duration-200 h-full flex flex-col shadow-sm"
                                                    :class="selectedPaketId === {{ $paket->id }} ?
                                                        'border-green-500 ring-1 ring-green-500' :
                                                        'border-gray-200 hover:border-green-300 hover:shadow-md'">

                                                    <div class="relative h-32 w-full">
                                                        <img src="{{ $paket->gambar ? asset('storage/' . $paket->gambar) : 'https://via.placeholder.com/400x200' }}"
                                                            alt="{{ $paket->name }}"
                                                            class="w-full h-full object-cover rounded-t-xl">
                                                        <div
                                                            class="absolute top-2 right-2 bg-white/90 px-2 py-0.5 rounded text-[10px] font-bold shadow-sm text-gray-600">
                                                            {{ $paket->jumlah_orang }} Orang
                                                        </div>
                                                    </div>

                                                    <div class="p-3 flex flex-col flex-1 justify-between"
                                                        :class="selectedPaketId === {{ $paket->id }} ?
                                                            'bg-green-50 rounded-b-xl' : 'bg-white rounded-b-xl'">

                                                        <div class="flex items-start gap-3">
                                                            <div class="mt-0.5 flex-shrink-0 w-5 h-5 rounded-full border-2 flex items-center justify-center transition-colors"
                                                                :class="selectedPaketId === {{ $paket->id }} ?
                                                                    'border-green-500 bg-green-500' :
                                                                    'border-gray-300 bg-white group-hover:border-green-400'">
                                                                <div x-show="selectedPaketId === {{ $paket->id }}"
                                                                    class="w-2 h-2 bg-white rounded-full shadow-sm"
                                                                    x-transition.scale.origin.center></div>
                                                            </div>

                                                            <div class="flex-1 min-w-0">
                                                                <h4
                                                                    class="font-bold text-gray-800 text-sm leading-tight line-clamp-2">
                                                                    {{ $paket->name }}
                                                                </h4>
                                                            </div>
                                                        </div>

                                                        <div class="mt-3 pl-8">
                                                            <p class="text-sm font-bold text-green-600">
                                                                Rp
                                                                {{ number_format($paket->harga_paket, 0, ',', '.') }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            <div class="flex justify-end">
                                <button type="submit" :disabled="!bookingType || !tanggalKunjungan"
                                    :class="(!bookingType || !tanggalKunjungan) ? 'bg-gray-400 cursor-not-allowed' :
                                    'bg-[#10b981] hover:bg-emerald-600 shadow-lg'"
                                    class="text-white px-8 py-2 rounded-full font-bold text-sm transition">
                                    Booking Sekarang
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </main>
    </div>
</x-layouts.user>

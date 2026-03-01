@extends('layouts.user')
@section('content')
    <div class="min-h-screen bg-gray-100 font-sans max-w-7xl mx-auto">
        {{-- Toast Notification Pop-up --}}
        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-full"
                x-transition:enter-end="opacity-100 translate-x-0" x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-x-0" x-transition:leave-end="opacity-0 translate-x-full"
                class="fixed top-5 right-5 z-[100] max-w-sm w-full bg-white border-l-4 border-green-500 rounded-lg shadow-2xl p-4 flex items-start gap-3">

                <div class="text-green-500 mt-0.5">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
                <div class="flex-1">
                    <h3 class="font-bold text-gray-900 text-sm">Sukses!</h3>
                    <p class="text-gray-600 text-sm mt-1">{{ session('success') }}</p>
                    <a href="{{ route('history.bookings') }}"
                        class="text-blue-600 text-xs font-semibold mt-2 inline-block hover:underline">
                        Lihat History Pemesanan &rarr;
                    </a>
                </div>
                <button @click="show = false" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif
        <main class="mx-auto mt-5 p-4">
            <div class="inline-block bg-[#60a5fa] text-white px-6 py-2 rounded-2xl shadow-md mb-2">
                <h1 class="text-xl font-bold italic">Form Pemesanan</h1>
            </div>

            {{-- Booking Form --}}
            <div class="flex flex-col md:flex-row rounded-3xl shadow-xl" x-data="bookingData()" x-init="init()">

                <div class="w-full md:w-1/3 bg-[#69db86] p-8">
                    <h2 class="text-2xl font-bold mb-6">Pilih Wisata</h2>

                    <form method="GET" action="{{ route('booking.index') }}" id="bookingForm">
                        <div class="space-y-4">
                            {{-- Custom Dropdown Kota --}}
                            <div>
                                <label class="block font-semibold mb-1">Kota</label>
                                <div class="relative" @click.away="openKota = false">
                                    {{-- Tombol Dropdown --}}
                                    <button type="button" @click="openKota = !openKota"
                                        class="w-full p-3 rounded-full border-none focus:ring-2 focus:ring-green-400 bg-white text-left flex justify-between items-center shadow-sm">
                                        <span x-text="kotaName || '-- Cari & Pilih Kota --'"
                                            :class="kotaName ? 'text-gray-900' : 'text-gray-500'"></span>
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </button>

                                    {{-- Menu Dropdown --}}
                                    <div x-show="openKota" x-transition style="display: none;"
                                        class="absolute z-50 w-full mt-2 bg-white rounded-xl shadow-xl border border-gray-100 overflow-hidden">
                                        <div class="p-2 border-b border-gray-100">
                                            <input type="text" x-model="searchKota" placeholder="Ketik nama kota..."
                                                class="w-full p-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-400 focus:border-green-400 text-sm">
                                        </div>
                                        <div class="max-h-48 overflow-y-auto py-1">
                                            <template x-for="kota in filteredKotas" :key="kota.id">
                                                <div @click="selectKota(kota)"
                                                    class="px-4 py-2 hover:bg-green-50 cursor-pointer text-gray-700 text-sm transition-colors"
                                                    :class="selectedKota == kota.id ? 'bg-green-50 font-bold text-green-700' :
                                                        ''"
                                                    x-text="kota.name"></div>
                                            </template>
                                            <div x-show="filteredKotas.length === 0"
                                                class="px-4 py-3 text-gray-500 text-sm text-center">
                                                Kota tidak ditemukan
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Custom Dropdown Wisata --}}
                            <div>
                                <label class="block font-semibold mb-1">Wisata</label>
                                <div class="relative" @click.away="openWisata = false">
                                    {{-- Tombol Dropdown --}}
                                    <button type="button" @click="selectedKota ? openWisata = !openWisata : null"
                                        :disabled="!selectedKota"
                                        :class="!selectedKota ? 'bg-gray-200 cursor-not-allowed' : 'bg-white'"
                                        class="w-full p-3 rounded-full border-none focus:ring-2 focus:ring-green-400 text-left flex justify-between items-center shadow-sm transition-colors">

                                        <span x-text="wisataName || '-- Cari & Pilih Wisata --'"
                                            :class="wisataName ? 'text-gray-900' : 'text-gray-500'"></span>

                                        <div class="flex items-center">
                                            {{-- Animasi Loading --}}
                                            <svg x-show="loadingWisata" class="animate-spin h-5 w-5 text-green-600 mr-2"
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                                    stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor"
                                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                </path>
                                            </svg>
                                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </div>
                                    </button>

                                    {{-- Menu Dropdown --}}
                                    <div x-show="openWisata && selectedKota" x-transition style="display: none;"
                                        class="absolute z-50 w-full mt-2 bg-white rounded-xl shadow-xl border border-gray-100 overflow-hidden">
                                        <div class="p-2 border-b border-gray-100">
                                            <input type="text" x-model="searchWisata" placeholder="Cari nama wisata..."
                                                class="w-full p-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-400 focus:border-green-400 text-sm">
                                        </div>
                                        <div class="max-h-48 overflow-y-auto py-1">
                                            <template x-for="wisata in filteredWisatas" :key="wisata.id">
                                                <div @click="selectWisata(wisata)"
                                                    class="px-4 py-2 hover:bg-green-50 cursor-pointer text-gray-700 text-sm transition-colors"
                                                    :class="selectedWisata == wisata.id ?
                                                        'bg-green-50 font-bold text-green-700' : ''"
                                                    x-text="wisata.name"></div>
                                            </template>
                                            <div x-show="filteredWisatas.length === 0"
                                                class="px-4 py-3 text-gray-500 text-sm text-center">
                                                Wisata tidak ditemukan
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div id="wisata-info-card" class="bg-white rounded-lg p-4 mt-4" x-show="selectedWisata"
                        style="display: none;">
                    </div>
                </div>

                <div class="w-full md:w-2/3 bg-[#c98d3f] p-8 relative">
                    <h2 class="text-2xl font-bold mb-6">Tipe Pemesanan</h2>

                    <div x-show="!selectedWisata" class="bg-white rounded-2xl p-6 text-center text-gray-500">
                        Silakan pilih kota dan wisata terlebih dahulu
                    </div>

                    <form method="POST" action="{{ route('booking.store') }}" class="space-y-6"
                        x-show="selectedWisata">
                        @csrf
                        <input type="hidden" name="wisata_id" :value="selectedWisata">
                        <input type="hidden" name="paket_wisata_id" :value="selectedPaketId">
                        <input type="hidden" name="jumlah_orang" :value="bookingType === 'paket' ? 1 : jumlahOrang">

                        <div class="bg-white rounded-2xl p-6 shadow-sm">
                            <div class="flex items-center gap-2 text-blue-600 font-bold mb-4">
                                Pilih Tanggal Kunjungan <span class="text-red-500">*</span>
                            </div>
                            <input type="date" name="tanggal_kunjungan" x-model="tanggalKunjungan"
                                @change="checkTicketAvailability()" min="{{ now()->format('Y-m-d') }}"
                                class="w-full p-3 rounded-full border-none bg-gray-100 focus:ring-2 focus:ring-blue-400">

                            <div x-show="tanggalKunjungan && checkingTicket"
                                class="mt-3 p-3 bg-blue-50 border border-blue-200 rounded-lg text-sm text-blue-700">
                                Mengecek ketersediaan tiket...
                            </div>

                            <div x-show="tanggalKunjungan && !checkingTicket && ticketMessage"
                                :class="ticketAvailable ? 'bg-green-50 border-green-200 text-green-700' :
                                    'bg-red-50 border-red-200 text-red-700'"
                                class="mt-3 p-3 border rounded-lg text-sm">
                                <p class="font-semibold" x-text="ticketMessage"></p>
                                <p x-show="ticketAvailable && sisaTiket > 0" class="text-xs mt-1">
                                    Sisa kuota: <span x-text="sisaTiket"></span> tiket
                                </p>
                            </div>
                        </div>

                        <div class="bg-white rounded-2xl p-6 shadow-sm transition-all duration-300"
                            :class="{ 'opacity-50 grayscale cursor-not-allowed': bookingType === 'paket' }">
                            <div class="flex items-center gap-2 text-blue-600 font-bold mb-4">Pilih Harga Reguler</div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="text-xs font-bold text-gray-500">Jumlah Orang</label>
                                    <input type="number" x-model="jumlahOrang" @input="inputRegular()"
                                        @change="checkTicketAvailability()" :disabled="bookingType === 'paket'"
                                        min="1" placeholder="-"
                                        class="w-full bg-gray-200 rounded p-2 border-none mt-1 text-center focus:ring-2 focus:ring-blue-400 disabled:cursor-not-allowed">
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-gray-500">Total Harga</label>
                                    <div class="bg-gray-200 rounded p-2 mt-1 text-gray-700 font-semibold text-center">
                                        Rp <span
                                            x-text="jumlahOrang ? (jumlahOrang * hargaTiket).toLocaleString('id-ID') : '-'"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-2xl p-6 shadow-sm transition-all duration-300"
                            :class="{ 'opacity-50 grayscale pointer-events-none': bookingType === 'regular' }">
                            <div class="flex items-center gap-2 text-green-600 font-bold mb-4">
                                <span>🎁</span> Pilih Paket Bundling
                            </div>

                            <div id="paket-grid"
                                class="grid grid-cols-1 sm:grid-cols-2 gap-4 max-h-[450px] overflow-y-auto pr-1"
                                style="display: none;"></div>
                            <div id="paket-empty" class="text-center text-gray-500 py-4">Pilih wisata untuk melihat paket
                                bundling tersedia</div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" :disabled="!bookingType || !tanggalKunjungan"
                                :class="(!bookingType || !tanggalKunjungan) ?
                                'bg-gray-400 cursor-not-allowed' : 'bg-[#10b981] hover:bg-emerald-600 shadow-lg'"
                                class="text-white px-8 py-2 rounded-full font-bold text-sm transition">
                                Booking Sekarang
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Paket Wisata Populer Section --}}
            <div class="mt-16">
                <div class="inline-block bg-[#8B6F47] text-white px-6 py-2 rounded-2xl shadow-md mb-4">
                    <h2 class="text-xl font-bold italic">Paket Wisata Populer</h2>
                </div>

                @if ($paketWisataPopuler->count() > 0)
                    <div id="paket-populer-grid"
                        class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                        @foreach ($paketWisataPopuler as $paket)
                            <div
                                class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-200 flex flex-col">
                                {{-- Paket Image --}}
                                <div class="h-48 overflow-hidden bg-gray-300">
                                    <img src="{{ $paket->gambar ? asset('storage/' . $paket->gambar) : 'https://via.placeholder.com/400x200' }}"
                                        alt="{{ $paket->nama_paket }}"
                                        class="w-full h-full object-cover hover:scale-110 transition-transform duration-300">
                                </div>

                                {{-- Paket Info --}}
                                <div class="p-5 flex flex-col flex-grow">
                                    <h3 class="text-sm font-semibold text-gray-600 mb-2">
                                        <i class="fas fa-map-marker-alt text-[#8B6F47] mr-1"></i>
                                        {{ $paket->wisata->kota->name ?? 'Kota' }}
                                    </h3>
                                    <h4
                                        class="text-lg font-bold text-gray-800 mb-2 line-clamp-2 hover:text-[#8B6F47] transition-colors">
                                        {{ $paket->name }}
                                    </h4>
                                    <p class="text-sm text-gray-600 mb-3 line-clamp-2 flex-grow">
                                        {{ $paket->wisata->name }}
                                    </p>

                                    {{-- Paket Details --}}
                                    <div class="space-y-2 text-sm text-gray-700 mb-4">
                                        <div class="flex items-center justify-between">
                                            <span class="text-gray-600">
                                                <i class="fas fa-users text-[#8B6F47] mr-1"></i>Kapasitas:
                                            </span>
                                            <span class="font-semibold">{{ $paket->jumlah_orang }} Orang</span>
                                        </div>
                                    </div>

                                    {{-- Price --}}
                                    <div class="border-t pt-3 mb-4">
                                        <p class="text-xs text-gray-500 mb-1">Harga Paket</p>
                                        <p class="text-xl font-bold text-[#8B6F47]">
                                            Rp {{ number_format($paket->harga_paket, 0, ',', '.') }}
                                        </p>
                                    </div>

                                    {{-- Action Button --}}
                                    <button type="button"
                                        onclick="window.state_ref.autoFillFromPopuler({{ $paket->wisata->kota_id }}, {{ $paket->wisata_id }}, {{ $paket->id }})"
                                        class="w-full bg-gradient-to-r from-[#8B6F47] to-[#D4AF37] text-white py-2 rounded-lg font-semibold hover:shadow-lg transition-all duration-300 text-center text-sm">
                                        <i class="fas fa-shopping-cart mr-2"></i>Pesan Paket
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-white rounded-xl shadow-md p-12 text-center">
                        <i class="fas fa-box-open text-gray-300 text-6xl mb-4"></i>
                        <h3 class="text-xl font-semibold text-gray-600 mb-2">Belum Ada Paket Wisata</h3>
                        <p class="text-gray-500">Paket wisata akan segera tersedia</p>
                    </div>
                @endif
            </div>
        </main>
    </div>
    <script>
        function bookingData() {
            return {
                // -- DATA KOTA --
                kotasList: @json($kotas->map(fn($k) => ['id' => $k->id, 'name' => $k->name])),
                selectedKota: '{{ $selectedKotaId ?? '' }}',
                kotaName: '{{ $selectedKota->name ?? '' }}',
                searchKota: '',
                openKota: false,

                // -- DATA WISATA --
                selectedWisata: '{{ $selectedWisataId ?? '' }}',
                wisataName: '{{ $selectedWisata->name ?? '' }}',
                wisataList: [],
                searchWisata: '',
                openWisata: false,
                loadingWisata: false,

                // Data Kanan (Tipe Pemesanan)
                bookingType: '{{ old('paket_wisata_id') ? 'paket' : (old('jumlah_orang') ? 'regular' : '') }}',
                jumlahOrang: '{{ old('jumlah_orang') }}',
                tanggalKunjungan: '{{ old('tanggal_kunjungan') }}',
                selectedPaketId: {{ old('paket_wisata_id', 'null') }},
                hargaTiket: {{ $selectedWisata->harga_tiket ?? 0 }},
                wisataId: '{{ $selectedWisataId ?? '' }}',
                ticketAvailable: true,
                ticketMessage: '',
                sisaTiket: 0,
                checkingTicket: false,

                get filteredKotas() {
                    if (this.searchKota === '') return this.kotasList;
                    return this.kotasList.filter(k => k.name.toLowerCase().includes(this.searchKota.toLowerCase()));
                },

                get filteredWisatas() {
                    if (this.searchWisata === '') return this.wisataList;
                    return this.wisataList.filter(w => w.name.toLowerCase().includes(this.searchWisata.toLowerCase()));
                },

                selectKota(kota) {
                    this.selectedKota = kota.id;
                    this.kotaName = kota.name;
                    this.openKota = false;
                    this.searchKota = ''; // Reset form cari
                    this.fetchWisatasByKota(kota.id);
                },

                selectWisata(wisata) {
                    this.selectedWisata = wisata.id;
                    this.wisataName = wisata.name;
                    this.wisataId = wisata.id;
                    this.openWisata = false;
                    this.searchWisata = ''; // Reset form cari
                    this.fetchPaketByWisata(wisata.id);
                },

                init() {
                    window.state_ref = this;
                    if (this.selectedKota) {
                        this.fetchWisatasByKota(this.selectedKota);
                    }
                    if (this.selectedWisata) {
                        this.fetchPaketByWisata(this.selectedWisata);
                    }
                },

                // Metode untuk input reguler
                inputRegular() {
                    if (this.jumlahOrang > 0) {
                        this.bookingType = 'regular';
                        this.selectedPaketId = null;
                        // Re-check ticket availability saat jumlah orang berubah
                        if (this.tanggalKunjungan) {
                            this.checkTicketAvailability();
                        }
                    } else {
                        this.bookingType = '';
                    }
                    this.updatePaketGridVisual();
                },
                async autoFillFromPopuler(kotaId, wisataId, paketId) {
                    const formContainer = document.getElementById('bookingForm');
                    if (formContainer) {
                        // Offset 80px agar judul "Form Pemesanan" tidak tertutup batas atas layar
                        const y = formContainer.getBoundingClientRect().top + window.scrollY - 80;
                        window.scrollTo({
                            top: y,
                            behavior: 'smooth'
                        });
                    }

                    // 2. Pilih Kota (Hanya fetch jika kota berbeda)
                    if (this.selectedKota != kotaId) {
                        const kota = this.kotasList.find(k => k.id == kotaId);
                        if (kota) {
                            this.selectedKota = kota.id;
                            this.kotaName = kota.name;
                            await this.fetchWisatasByKota(kota.id);
                        }
                    }

                    // 3. Pilih Wisata (Hanya fetch jika wisata berbeda)
                    if (this.selectedWisata != wisataId) {
                        const wisata = this.wisataList.find(w => w.id == wisataId);
                        if (wisata) {
                            this.selectedWisata = wisata.id;
                            this.wisataName = wisata.name;
                            await this.fetchPaketByWisata(wisata.id);
                        }
                    }

                    // 4. Set State Paket Bundling
                    setTimeout(() => {
                        this.selectedPaketId = parseInt(paketId);
                        this.bookingType = 'paket';
                        this.jumlahOrang = ''; // Kosongkan input reguler
                        this.updatePaketGridVisual(); // Highlight paket warna hijau

                        // TAMBAHAN BARU: Auto-focus ke input tanggal agar user sadar
                        const dateInput = document.querySelector('input[name="tanggal_kunjungan"]');
                        if (dateInput) {
                            dateInput.focus();
                            // Beri efek kedip merah sebentar agar mata user tertuju ke kolom tanggal
                            dateInput.classList.add('ring-2', 'ring-red-500');
                            setTimeout(() => dateInput.classList.remove('ring-2', 'ring-red-500'), 1500);
                        }
                    }, 200);
                },
                // Metode saat memilih paket bundling
                selectPaket(id) {
                    if (this.selectedPaketId === id) {
                        this.selectedPaketId = null;
                        this.bookingType = '';
                    } else {
                        this.selectedPaketId = id;
                        this.bookingType = 'paket';
                        this.jumlahOrang = '';
                    }
                    this.updatePaketGridVisual();
                },

                async checkTicketAvailability() {
                    if (!this.wisataId) {
                        this.ticketAvailable = false;
                        this.ticketMessage = 'Pilih wisata terlebih dahulu';
                        return;
                    }
                    if (!this.tanggalKunjungan) {
                        this.ticketAvailable = true;
                        this.ticketMessage = '';
                        this.checkingTicket = false;
                        return;
                    }

                    this.checkingTicket = true;
                    try {
                        let jumlahOrang = 1; // default
                        if (this.bookingType === 'regular' && this.jumlahOrang) {
                            jumlahOrang = parseInt(this.jumlahOrang);
                        }

                        const url = new URL('/api/check-ticket-availability', window.location.origin);
                        url.searchParams.append('wisata_id', this.wisataId);
                        url.searchParams.append('tanggal', this.tanggalKunjungan);
                        url.searchParams.append('jumlah_orang', jumlahOrang);

                        const response = await fetch(url.toString());
                        const data = await response.json();

                        this.ticketAvailable = data.available;
                        this.ticketMessage = data.message;
                        this.sisaTiket = data.sisaTiket;
                    } catch (error) {
                        console.error('Error checking availability:', error);
                        this.ticketAvailable = false;
                        this.ticketMessage = 'Gagal mengecek ketersediaan tiket';
                    } finally {
                        this.checkingTicket = false;
                    }
                },
                async fetchWisatasByKota(kotaId) {
                    // Reset data wisata dan paket saat kota berubah
                    this.selectedWisata = '';
                    this.wisataName = ''; // Ditambahkan untuk mereset teks di dropdown wisata
                    this.wisataId = '';
                    this.wisataList = [];
                    this.bookingType = '';
                    this.updateWisataCard(null);
                    this.updatePaketGrid([], this);

                    if (!kotaId) return;
                    this.loadingWisata = true;

                    try {
                        const response = await fetch(`/api/wisatas-by-kota/${kotaId}`);
                        const data = await response.json();
                        this.wisataList = data.wisatas || [];
                    } catch (error) {
                        console.error('Error fetching wisata:', error);
                    } finally {
                        this.loadingWisata = false;
                    }
                },

                async fetchPaketByWisata(wisataId) {
                    if (!wisataId) {
                        this.updateWisataCard(null);
                        return;
                    }
                    this.wisataId = wisataId;
                    try {
                        const response = await fetch(`/api/paket-by-wisata/${wisataId}`);
                        const data = await response.json();

                        // Update harga tiket reguler dari API
                        if (data.wisata) this.hargaTiket = data.wisata.harga_tiket || 0;

                        this.updateWisataCard(data.wisata);
                        this.updatePaketGrid(data.pakets, this);
                    } catch (error) {
                        console.error('Error fetching paket:', error);
                    }
                },

                updateWisataCard(wisata) {
                    const card = document.getElementById('wisata-info-card');
                    if (!wisata) {
                        if (card) card.style.display = 'none';
                        return;
                    }
                    if (card) {
                        card.innerHTML = `
                            <img src="${wisata.image}" alt="${wisata.name}"
                                class="w-full h-48 object-cover rounded-md">
                            <h3 class="font-bold mb-2 mt-4">${wisata.name}</h3>
                            <p class="text-sm text-gray-600 mb-2">${wisata.description.substring(0, 100)}${wisata.description.length > 100 ? '...' : ''}</p>
                        `;
                        card.style.display = 'block';
                    }
                },

                updatePaketGrid(pakets, state) {
                    const grid = document.getElementById('paket-grid');
                    const emptyMsg = document.getElementById('paket-empty');

                    if (!grid || !pakets || pakets.length === 0) {
                        if (grid) grid.style.display = 'none';
                        if (emptyMsg) emptyMsg.style.display = 'block';
                        return;
                    }

                    let html = '';
                    pakets.forEach(paket => {
                        const formattedPrice = parseInt(paket.harga_paket).toLocaleString('id-ID');
                        const isSelected = state.selectedPaketId === paket.id;
                        html += `
                            <div onclick="state_ref.selectPaket(${paket.id})"
                                class="cursor-pointer group relative h-full paket-item"
                                data-paket-id="${paket.id}">

                                <div class="bg-white rounded-xl border transition-all duration-200 h-full flex flex-col shadow-sm"
                                    style="border-color: ${isSelected ? '#22c55e' : '#e5e7eb'};
                                            box-shadow: ${isSelected ? '0 0 0 1px #22c55e' : 'none'};">

                                    <div class="relative h-32 w-full">
                                        <img src="${paket.gambar}" alt="${paket.name}"
                                            class="w-full h-full object-cover rounded-t-xl">
                                        <div class="absolute top-2 right-2 bg-white/90 px-2 py-0.5 rounded text-[10px] font-bold shadow-sm text-gray-600">
                                            ${paket.jumlah_orang} Orang
                                        </div>
                                    </div>

                                    <div class="p-3 flex flex-col flex-1 justify-between"
                                        style="background-color: ${isSelected ? '#f0fdf4' : '#ffffff'};
                                                border-radius: 0 0 0.75rem 0.75rem;">

                                        <div class="flex items-start gap-3">
                                            <div class="mt-0.5 flex-shrink-0 w-5 h-5 rounded-full border-2 flex items-center justify-center transition-colors"
                                                style="border-color: ${isSelected ? '#22c55e' : '#d1d5db'};
                                                        background-color: ${isSelected ? '#22c55e' : '#ffffff'};">
                                                ${isSelected ? '<div class="w-2 h-2 bg-white rounded-full shadow-sm"></div>' : ''}
                                            </div>

                                            <div class="flex-1 min-w-0">
                                                <h4 class="font-bold text-gray-800 text-sm leading-tight line-clamp-2">
                                                    ${paket.name}
                                                </h4>
                                            </div>
                                        </div>

                                        <div class="mt-3 pl-8">
                                            <p class="text-sm font-bold text-green-600">
                                                Rp ${formattedPrice}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                    });

                    grid.innerHTML = html;
                    grid.style.display = 'grid';
                    if (emptyMsg) emptyMsg.style.display = 'none';
                },

                updatePaketGridVisual() {
                    const grid = document.getElementById('paket-grid');
                    if (!grid || grid.style.display === 'none') return;

                    const items = grid.querySelectorAll('.paket-item');
                    items.forEach(item => {
                        const paketId = parseInt(item.getAttribute('data-paket-id'));
                        const isSelected = this.selectedPaketId === paketId;

                        const card = item.querySelector('.bg-white');
                        if (card) {
                            card.style.borderColor = isSelected ? '#22c55e' : '#e5e7eb';
                            card.style.boxShadow = isSelected ? '0 0 0 1px #22c55e' : 'none';
                        }

                        const content = item.querySelector('.p-3');
                        if (content) {
                            content.style.backgroundColor = isSelected ? '#f0fdf4' : '#ffffff';
                        }

                        const radio = item.querySelector('.w-5.h-5.rounded-full');
                        if (radio) {
                            radio.style.borderColor = isSelected ? '#22c55e' : '#d1d5db';
                            radio.style.backgroundColor = isSelected ? '#22c55e' : '#ffffff';

                            const checkmark = radio.querySelector('.w-2.h-2');
                            if (checkmark) {
                                checkmark.style.display = isSelected ? 'block' : 'none';
                            } else if (isSelected) {
                                radio.innerHTML = '<div class="w-2 h-2 bg-white rounded-full shadow-sm"></div>';
                            }
                        }
                    });
                }
            }
        }
    </script>
@endsection

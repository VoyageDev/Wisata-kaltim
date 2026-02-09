<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingsRequest;
use App\Http\Requests\UpdateBookingsRequest;
use App\Models\Bookings;
use App\Models\Kota;
use App\Models\PaketWisata;
use App\Models\Payments_channels;
use App\Models\Wisata;
use App\Models\WisataKuota;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class BookingsController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $bookings = Bookings::with(['user', 'wisata', 'paketWisata'])
            ->orderByRaw("FIELD(status, 'pending', 'done', 'paid', 'cancelled')")
            ->latest()
            ->paginate(10);

        return view('admin.booking.index', compact('bookings'));
    }

    public function memberIndex(): View
    {
        // Get all kota with their wisatas and paket wisatas
        $kotas = Kota::with(['wisatas.paketWisatas'])
            ->get();

        // Get selected data from request
        $selectedKotaId = request('kota_id');
        $selectedWisataId = request('wisata_id');

        $selectedKota = null;
        $selectedWisata = null;
        $availableWisatas = [];
        $availablePakets = [];

        if ($selectedKotaId) {
            $selectedKota = $kotas->firstWhere('id', $selectedKotaId);
            if ($selectedKota) {
                $availableWisatas = $selectedKota->wisatas;
            }
        }

        if ($selectedWisataId && $selectedKota) {
            $selectedWisata = $availableWisatas->firstWhere('id', $selectedWisataId);
            if ($selectedWisata) {
                $availablePakets = $selectedWisata->paketWisatas;
            }
        }

        return view('member.booking.index', compact(
            'kotas',
            'selectedKotaId',
            'selectedWisataId',
            'selectedKota',
            'selectedWisata',
            'availableWisatas',
            'availablePakets'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('member.booking.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBookingsRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        // Get wisata for price calculation
        $wisata = Wisata::find($validated['wisata_id']);

        // Calculate total price
        if ($validated['paket_wisata_id']) {
            $paket = PaketWisata::find($validated['paket_wisata_id']);
            $totalHarga = $paket->harga_paket;
            $jumlahOrang = $paket->jumlah_orang;
        } else {
            $jumlahOrang = $validated['jumlah_orang'] ?? 1;
            $totalHarga = $jumlahOrang * $wisata->harga_tiket;
        }

        // Generate tiket code
        $prefix = 'TRV';
        $date = now()->format('Ymd');
        $kodeAwal = $prefix.$date;

        $lastBooking = Bookings::where('kode_tiket', 'like', $kodeAwal.'%')
            ->orderBy('id', 'desc')
            ->first();

        if ($lastBooking) {
            $lastNo = (int) substr($lastBooking->kode_tiket, -3);
            $nextNo = $lastNo + 1;
        } else {
            $nextNo = 1;
        }
        $urutan = str_pad($nextNo, 3, '0', STR_PAD_LEFT);
        $kodeTicket = $kodeAwal.$urutan;

        // Create booking with pending status
        $booking = Bookings::create([
            'user_id' => Auth::id(),
            'wisata_id' => $validated['wisata_id'],
            'paket_wisata_id' => $validated['paket_wisata_id'] ?? null,
            'tanggal_kunjungan' => $validated['tanggal_kunjungan'],
            'jumlah_orang' => $jumlahOrang,
            'total_harga' => $totalHarga,
            'kode_tiket' => $kodeTicket,
            'status' => 'pending',
        ]);

        return redirect()->route('booking.invoice', $booking->id);
    }

    /**
     * Show invoice for the booking
     */
    public function invoice(Bookings $booking): View|RedirectResponse
    {
        // Check if user is authorized to see this booking
        $this->authorize('view', $booking);

        // Cek jika booking sudah dibayar/ada payment aktif, langsung lempar ke halaman pembayaran
        if ($booking->payments()->where('status', 'pending')->exists()) {
            $payment = $booking->payments()->where('status', 'pending')->first();

            return redirect()->route('payment.show', $payment->id);
        }

        $booking->load('user', 'wisata', 'paketWisata');

        $channels = Payments_channels::where('is_active', 1)->get();

        // Kirim $channels ke view
        return view('member.booking.invoice', compact('booking', 'channels'));
    }

    /**
     * Continue payment for existing booking
     * Smart redirect: to payment.show if payment exists, to invoice if not
     */
    public function continuePayment(Bookings $booking): RedirectResponse
    {
        // Check if user is authorized to see this booking
        $this->authorize('view', $booking);

        // Only allow for pending bookings
        if ($booking->status !== 'pending') {
            return redirect()->route('history.index')
                ->with('error', 'Booking ini tidak dapat dilanjutkan pembayaran.');
        }

        // Check if there's an existing pending payment
        $pendingPayment = $booking->payments()->where('status', 'pending')->first();

        if ($pendingPayment) {
            // Redirect to payment instruction page
            return redirect()->route('payment.show', $pendingPayment->id);
        }

        // No payment yet, redirect to invoice to choose payment method
        return redirect()->route('booking.invoice', $booking->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(Bookings $booking): View
    {
        $booking->load('user', 'wisata', 'paketWisata', 'payments');

        return view('bookings.show', compact('booking'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Bookings $booking): View
    {
        return view('bookings.edit', compact('booking'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBookingsRequest $request, Bookings $booking): RedirectResponse
    {
        $booking->update($request->validated());

        return redirect()->route('bookings.index')->with('success', 'Booking updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bookings $booking): RedirectResponse
    {
        $booking->delete();

        return redirect()->route('bookings.index')->with('success', 'Booking deleted successfully.');
    }

    public function cancel(Bookings $booking): RedirectResponse
    {
        // Authorize user
        $this->authorize('cancel', $booking);

        // Only allow cancellation for pending or paid bookings
        if (! in_array($booking->status, ['pending', 'paid'])) {
            return redirect()->route('history.index')
                ->with('error', 'Booking dengan status '.$booking->status.' tidak dapat dibatalkan.');
        }

        // If booking is already paid, refund the ticket
        if ($booking->status === 'paid') {
            $kuota = WisataKuota::where('wisata_id', $booking->wisata_id)
                ->where('tanggal', $booking->tanggal_kunjungan)
                ->first();

            if ($kuota && $kuota->kuota_terpakai > 0) {
                // Mengembalikan tiket berdasarkan jumlah orang pada booking
                $jumlahOrang = $booking->jumlah_orang ?? 1;
                $decrementQty = min($jumlahOrang, $kuota->kuota_terpakai);
                $kuota->decrement('kuota_terpakai', $decrementQty);
            }
        }

        // Update booking status to cancelled
        $booking->status = 'cancelled';
        $booking->save();

        return redirect()->route('history.bookings')
            ->with('success', 'Booking berhasil dibatalkan. '.($booking->status === 'paid' ? 'Tiket Anda telah di-refund.' : ''));
    }

    public function print(Bookings $booking): View|RedirectResponse
    {
        // Authorize user
        $this->authorize('view', $booking);

        $booking->load('user', 'wisata', 'paketWisata');

        return view('admin.booking.print', compact('booking'));
    }

    public function markDone(Bookings $booking): RedirectResponse
    {
        // Authorize user
        $this->authorize('view', $booking);

        // Only allow marking done for pending bookings
        if ($booking->status !== 'pending') {
            return redirect()->route('admin.bookings.index')
                ->with('error', 'Only pending bookings can be marked as done.');
        }

        // Update booking status to done
        $booking->status = 'done';
        $booking->save();

        return redirect()->route('admin.bookings.index')
            ->with('success', 'Booking berhasil ditandai sebagai selesai.');
    }
}

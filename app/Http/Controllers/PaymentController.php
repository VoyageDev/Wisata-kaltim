<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePaymentRequest;
use App\Models\Bookings;
use App\Models\Payments;
use App\Models\Payments_channels;
use App\Models\WisataKuota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'payment_channel_id' => 'required|exists:payments_channels,id',
        ]);

        $booking = Bookings::findOrFail($request->booking_id);
        $channel = Payments_channels::findOrFail($request->payment_channel_id);

        // jika sudah ada payment pending, redirect ke halaman payment tersebut
        if ($booking->payments()->where('status', 'pending')->exists()) {
            $payment = $booking->payments()->where('status', 'pending')->first();

            return redirect()->route('payment.show', $payment->id);
        }

        // Create Payment
        $payment = Payments::create([
            'booking_id' => $booking->id,
            'payment_channel_id' => $channel->id,
            'metode' => $channel->name,
            'jumlah' => $booking->total_harga,
            'status' => 'pending',
        ]);

        // Redirect ke halaman instruksi bayar
        return redirect()->route('payment.show', $payment->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(Payments $payment)
    {
        // hanya user yang memiliki booking ini yang bisa mengakses
        if ($payment->booking->user_id !== Auth::id()) {
            abort(403);
        }

        // Load relasi untuk ditampilkan di view
        $payment->load('booking.wisata', 'paymentChannel');

        return view('member.payment.show', compact('payment'));
    }

    /**
     * Confirm payment
     * Logika sama dengan API checkAvailability() - gunakan kuota_default jika tidak ada override
     */
    public function confirm(Payments $payment)
    {
        // Verify user ownership
        if ($payment->booking->user_id !== Auth::id()) {
            abort(403);
        }

        // Only pending payments can be confirmed
        if ($payment->status !== 'pending') {
            return redirect()->route('history.index')
                ->with('error', 'Pembayaran ini tidak dapat dikonfirmasi. Status: '.$payment->status);
        }

        // Check if booking is still pending
        if ($payment->booking->status !== 'pending') {
            return redirect()->route('history.index')
                ->with('error', 'Pemesanan ini sudah tidak aktif.');
        }

        $booking = $payment->booking;
        $wisata = $booking->wisata;
        $jumlahOrang = $booking->jumlah_orang ?? 1;

        // Get or create WisataKuota record for this date
        // Use kuota_default if no override exists (sama seperti API logic)
        $kuota = WisataKuota::firstOrCreate(
            [
                'wisata_id' => $booking->wisata_id,
                'tanggal' => $booking->tanggal_kunjungan,
            ],
            [
                'kuota_total' => null, // null = gunakan kuota_default
                'kuota_terpakai' => 0,
                'status' => 1, // open
            ]
        );

        // Determine actual kuota total (override or default)
        $kuotaTotal = $kuota->kuota_total !== null ? $kuota->kuota_total : $wisata->kuota_default;

        // Calculate available tickets
        $sisaTiket = $kuotaTotal - $kuota->kuota_terpakai;

        // Check if kuota is closed
        if (!$kuota->status) {
            return redirect()->route('history.index')
                ->with('error', 'Wisata tutup untuk tanggal tersebut.');
        }

        // Check if enough tickets are still available
        if ($sisaTiket < $jumlahOrang) {
            return redirect()->route('history.index')
                ->with('error', "Maaf, hanya tersisa {$sisaTiket} tiket untuk tanggal tersebut, sementara Anda membutuhkan {$jumlahOrang} tiket. Silakan hubungi admin.");
        }

        // Update kuota_terpakai with the number of people in this booking
        $kuota->increment('kuota_terpakai', $jumlahOrang);

        // Update payment status
        $payment->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        // Update booking status to PAID
        $booking->update([
            'status' => 'paid',
        ]);

        return redirect()->route('history.index')->with('success', 'Pembayaran Berhasil! Tiket sudah terbit.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payments $payment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePaymentRequest $request, Payments $payment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payments $payment)
    {
        //
    }
}

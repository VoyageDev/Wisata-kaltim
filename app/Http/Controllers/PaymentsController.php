<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePaymentsRequest;
use App\Models\Bookings;
use App\Models\Payments;
use App\Models\Payments_channels;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PaymentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $payments = Payments::with('booking', 'paymentChannel')->paginate(15);

        return view('payments.index', compact('payments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('payments.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'payment_channel_id' => 'required|exists:payments_channels,id', // Sesuaikan nama tabel
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

    // 3. CONFIRM: Saat user klik "Saya Sudah Bayar"
    public function confirm(Payments $payment)
    {
        $payment->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        // Update status Booking jadi PAID juga
        $payment->booking->update([
            'status' => 'paid',
        ]);

        return redirect()->route('history.index')->with('success', 'Pembayaran Berhasil! Tiket sudah terbit.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payments $payment): View
    {
        return view('payments.edit', compact('payment'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePaymentsRequest $request, Payments $payment): RedirectResponse
    {
        $payment->update($request->validated());

        return redirect()->route('payments.index')->with('success', 'Payment updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payments $payment): RedirectResponse
    {
        $payment->delete();

        return redirect()->route('payments.index')->with('success', 'Payment deleted successfully.');
    }
}

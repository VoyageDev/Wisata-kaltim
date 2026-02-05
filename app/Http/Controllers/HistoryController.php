<?php

namespace App\Http\Controllers;

use App\Models\Bookings;
use App\Models\Ulasan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{
    /**
     * Display history index with all tabs
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $tab = $request->get('tab', 'bookings');

        // Get bookings statistics
        $bookingsStats = [
            'all' => Bookings::where('user_id', $user->id)->count(),
            'pending' => Bookings::where('user_id', $user->id)->where('status', 'pending')->count(),
            'paid' => Bookings::where('user_id', $user->id)->where('status', 'paid')->count(),
            'done' => Bookings::where('user_id', $user->id)->where('status', 'done')->count(),
            'cancelled' => Bookings::where('user_id', $user->id)->where('status', 'cancelled')->count(),
        ];

        // Get ulasan count
        $ulasanCount = Ulasan::where('user_id', $user->id)->count();

        // Get bookings data with pagination
        $bookings = Bookings::where('user_id', $user->id)
            ->with(['wisata', 'paketWisata', 'payments'])
            ->latest()
            ->paginate(10);

        // Get ulasans data with pagination
        $ulasans = Ulasan::where('user_id', $user->id)
            ->with(['reviewable', 'user'])
            ->latest()
            ->paginate(10);

        return view('member.history.index', compact('bookingsStats', 'ulasanCount', 'tab', 'bookings', 'ulasans'));
    }

    /**
     * Get bookings list (all or filtered by status)
     */
    public function bookings(Request $request)
    {
        $user = Auth::user();
        $status = $request->get('status'); // pending, paid, done, cancelled, or null for all

        $query = Bookings::where('user_id', $user->id)
            ->with(['wisata', 'paketWisata', 'payments']);

        // Filter by status if provided
        if ($status && in_array($status, ['pending', 'paid', 'done', 'cancelled'])) {
            $query->where('status', $status);
        }

        // Search by kode tiket or wisata name
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode_tiket', 'like', "%{$search}%")
                    ->orWhereHas('wisata', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Sort by latest
        $bookings = $query->latest()->paginate(10)->appends($request->query());

        return view('member.history.bookings', compact('bookings', 'status'));
    }

    /**
     * Get ulasan list
     */
    public function ulasans(Request $request)
    {
        $user = Auth::user();

        $query = Ulasan::where('user_id', $user->id)
            ->with(['reviewable', 'user']);

        // Filter by reviewable type (Wisata or Artikel)
        if ($request->has('type') && $request->type) {
            $query->where('reviewable_type', $request->type);
        }

        // Filter by rating
        if ($request->has('rating') && $request->rating) {
            $query->where('rating', (int) $request->rating);
        }

        // Search by komentar
        if ($request->has('search') && $request->search) {
            $query->where('komentar', 'like', "%{$request->search}%");
        }

        $ulasans = $query->latest()->paginate(10)->appends($request->query());

        return view('member.history.ulasans', compact('ulasans'));
    }

    /**
     * Show single booking detail
     */
    public function showBooking(Bookings $booking)
    {
        // Check if booking belongs to authenticated user
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access');
        }

        $booking->load(['wisata', 'paketWisata', 'payments.paymentChannel']);

        return view('member.history.booking-detail', compact('booking'));
    }

    /**
     * Cancel booking
     */
    public function cancelBooking(Bookings $booking)
    {
        // Check if booking belongs to authenticated user
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access');
        }

        // Only pending bookings can be cancelled
        if ($booking->status !== 'pending') {
            return back()->with('error', 'Hanya pemesanan dengan status pending yang dapat dibatalkan');
        }

        $booking->update(['status' => 'cancelled']);

        return back()->with('success', 'Pemesanan berhasil dibatalkan');
    }

    /**
     * Delete ulasan
     */
    public function deleteUlasan(Ulasan $ulasan)
    {
        // Check if ulasan belongs to authenticated user
        if ($ulasan->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access');
        }

        $ulasan->delete();

        return back()->with('success', 'Ulasan berhasil dihapus');
    }

    /**
     * Update ulasan
     */
    public function updateUlasan(Request $request, Ulasan $ulasan)
    {
        // Check if ulasan belongs to authenticated user
        if ($ulasan->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access');
        }

        $validated = $request->validate([
            'komentar' => 'required|string|max:1000',
            'rating' => 'nullable|integer|min:1|max:5',
        ]);

        $ulasan->update($validated);

        return back()->with('success', 'Ulasan berhasil diperbarui');
    }
}

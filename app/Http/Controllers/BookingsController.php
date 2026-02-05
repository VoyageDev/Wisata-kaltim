<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingsRequest;
use App\Http\Requests\UpdateBookingsRequest;
use App\Models\Bookings;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BookingsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $bookings = Bookings::with('user', 'wisata', 'paketWisata', 'payments')->paginate(15);

        return view('bookings.index', compact('bookings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('bookings.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBookingsRequest $request): RedirectResponse
    {
        Bookings::create($request->validated());

        return redirect()->route('bookings.index')->with('success', 'Booking created successfully.');
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
}

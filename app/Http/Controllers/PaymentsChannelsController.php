<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePayments_channelsRequest;
use App\Http\Requests\UpdatePayments_channelsRequest;
use App\Models\Payments_channels;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PaymentsChannelsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $payments_channels = Payments_channels::paginate(15);

        return view('payments-channels.index', compact('payments_channels'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('payments-channels.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePayments_channelsRequest $request): RedirectResponse
    {
        Payments_channels::create($request->validated());

        return redirect()->route('payments-channels.index')->with('success', 'Payment channel created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Payments_channels $payments_channel): View
    {
        $payments_channel->load('payments');

        return view('payments-channels.show', compact('payments_channel'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payments_channels $payments_channel): View
    {
        return view('payments-channels.edit', compact('payments_channel'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePayments_channelsRequest $request, Payments_channels $payments_channel): RedirectResponse
    {
        $payments_channel->update($request->validated());

        return redirect()->route('payments-channels.index')->with('success', 'Payment channel updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payments_channels $payments_channel): RedirectResponse
    {
        $payments_channel->delete();

        return redirect()->route('payments-channels.index')->with('success', 'Payment channel deleted successfully.');
    }
}

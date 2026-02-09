<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWisataKuotaRequest;
use App\Http\Requests\UpdateWisataKuotaRequest;
use App\Models\WisataKuota;

class WisataKuotaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $wisatas = WisataKuota::with('wisata')->latest()->paginate(10);

        return view('admin.wisata.tiket', compact('wisatas'));
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
    public function store(StoreWisataKuotaRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(WisataKuota $wisataKuota)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WisataKuota $wisataKuota)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWisataKuotaRequest $request, WisataKuota $wisataKuota)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WisataKuota $wisataKuota)
    {
        //
    }
}

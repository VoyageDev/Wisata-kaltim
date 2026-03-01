<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWisataKuotaRequest;
use App\Http\Requests\UpdateWisataKuotaRequest;
use App\Models\Wisata;
use App\Models\WisataKuota;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class WisataKuotaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = WisataKuota::with('wisata.kota');

        if ($request->filled('search')) {
            $query->whereHas('wisata', function ($q) use ($request) {
                $q->where('name', 'LIKE', '%'.$request->search.'%');
            });
        }

        if ($request->filled('filter_date')) {
            switch ($request->filter_date) {
                case 'besok':
                    $query->whereDate('tanggal', Carbon::tomorrow());
                    break;
                case 'minggu_depan':
                    $query->whereBetween('tanggal', [
                        Carbon::now()->addWeek()->startOfWeek(),
                        Carbon::now()->addWeek()->endOfWeek(),
                    ]);
                    break;
                case 'bulan_ini':
                    $query->whereMonth('tanggal', Carbon::now()->month)
                        ->whereYear('tanggal', Carbon::now()->year);
                    break;
            }
        }

        $wisatas = $query->orderBy('tanggal', 'asc')->paginate(10)->appends($request->all());

        return view('admin.wisata.tiket', compact('wisatas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $wisatas = Wisata::orderBy('name')->get();

        return view('admin.wisata.tiket-create', compact('wisatas'));
    }

    /**
     * Store a newly created resource in storage.
     * Untuk override kuota default atau tutup tanggal tertentu
     */
    public function store(StoreWisataKuotaRequest $request)
    {
        // Cek duplikasi secara manual agar kodenya lebih bersih dibanding try-catch
        $isDuplicate = WisataKuota::where('wisata_id', $request->wisata_id)
            ->where('tanggal', $request->tanggal)
            ->exists();

        if ($isDuplicate) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['tanggal' => 'Kuota untuk wisata ini pada tanggal tersebut sudah ada.']);
        }

        WisataKuota::create([
            'wisata_id' => $request->wisata_id,
            'tanggal' => $request->tanggal,
            'kuota_total' => $request->kuota_total,
            'kuota_terpakai' => 0,
            'status' => $request->status ?? 1,
        ]);

        return redirect()->route('admin.wisata.tiket')->with('success', 'Kuota wisata berhasil ditambahkan.');
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
        $wisatas = Wisata::orderBy('name')->get();

        return view('admin.wisata.tiket-edit', compact('wisataKuota', 'wisatas'));
    }

    public function update(UpdateWisataKuotaRequest $request, WisataKuota $wisataKuota)
    {
        $wisataKuota->update([
            'kuota_total' => $request->kuota_total,
            'status' => $request->status ?? 1,
        ]);

        return redirect()->route('admin.wisata.tiket')->with('success', 'Kuota wisata berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     * Menghapus override kuota (kembali ke default)
     */
    public function destroy(WisataKuota $wisataKuota)
    {
        $wisataKuota->delete();

        return redirect()->route('admin.wisata.tiket')->with('success', 'Kuota override berhasil dihapus, kembali menggunakan kuota default.');
    }

    /**
     * Check ticket availability via API
     * GET /api/check-ticket-availability?wisata_id=1&tanggal=2026-02-15&jumlah_orang=2
     *
     * Logic:
     * 1. Cek apakah ada override kuota untuk tanggal ini
     * 2. Jika status = 0 (tutup), return tidak tersedia
     * 3. Jika kuota_total = null, gunakan kuota_default dari wisata
     * 4. Jika kuota_total ada, gunakan itu (override)
     * 5. Cek apakah sisa tiket >= jumlah_orang yang diminta
     */
    public function checkAvailability(Request $request)
    {
        $request->validate([
            'wisata_id' => 'required|exists:wisatas,id',
            'tanggal' => 'required|date',
            'jumlah_orang' => 'nullable|integer|min:1',
        ]);

        $wisata = Wisata::findOrFail($request->wisata_id);
        $jumlahOrang = $request->input('jumlah_orang', 1);

        // Cek apakah ada override kuota untuk tanggal ini
        $kuota = WisataKuota::where('wisata_id', $request->wisata_id)
            ->where('tanggal', $request->tanggal)
            ->first();

        // Cek status (jika ada override dan statusnya tutup)
        if ($kuota && ! $kuota->status) {
            return response()->json([
                'available' => false,
                'message' => 'Wisata tutup untuk tanggal ini',
                'sisaTiket' => 0,
            ], 200);
        }

        // Tentukan kuota total (override atau default)
        $kuotaTotal = $kuota && $kuota->kuota_total !== null
            ? $kuota->kuota_total
            : $wisata->kuota_default;

        // Hitung kuota terpakai
        $kuotaTerpakai = $kuota ? $kuota->kuota_terpakai : 0;

        // Hitung sisa tiket
        $sisaTiket = $kuotaTotal - $kuotaTerpakai;

        // Cek apakah tersedia untuk jumlah orang yang diminta
        if ($sisaTiket <= 0) {
            return response()->json([
                'available' => false,
                'message' => 'Tiket untuk tanggal ini sudah terjual habis',
                'sisaTiket' => 0,
                'kuota_total' => $kuotaTotal,
                'kuota_terpakai' => $kuotaTerpakai,
                'jumlah_orang' => $jumlahOrang,
            ], 200);
        }

        if ($sisaTiket < $jumlahOrang) {
            return response()->json([
                'available' => false,
                'message' => "Tiket tidak cukup untuk {$jumlahOrang} orang. Hanya tersedia {$sisaTiket} tiket.",
                'sisaTiket' => $sisaTiket,
                'kuota_total' => $kuotaTotal,
                'kuota_terpakai' => $kuotaTerpakai,
                'jumlah_orang' => $jumlahOrang,
            ], 200);
        }

        return response()->json([
            'available' => true,
            'message' => "Tersedia {$sisaTiket} tiket untuk {$jumlahOrang} orang pada tanggal ini",
            'sisaTiket' => $sisaTiket,
            'kuota_total' => $kuotaTotal,
            'kuota_terpakai' => $kuotaTerpakai,
            'jumlah_orang' => $jumlahOrang,
            'is_override' => $kuota && $kuota->kuota_total !== null,
        ], 200);
    }
}

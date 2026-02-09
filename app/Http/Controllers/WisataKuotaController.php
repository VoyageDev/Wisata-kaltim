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
            $search = $request->search;
            $query->whereHas('wisata', function ($q) use ($search) {
                $q->where('name', 'LIKE', '%'.$search.'%');
            });
        }

        //  Filter Tanggal (Besok & Minggu Depan)
        if ($request->filled('filter_date')) {
            switch ($request->filter_date) {
                case 'besok':
                    // Filter khusus tanggal besok
                    $query->whereDate('tanggal', Carbon::tomorrow());
                    break;
                case 'minggu_depan':
                    // filter minggu depan dimulai dari senin
                    $startNextWeek = Carbon::now()->addWeek()->startOfWeek();
                    $endNextWeek = Carbon::now()->addWeek()->endOfWeek();
                    $query->whereBetween('tanggal', [$startNextWeek, $endNextWeek]);
                    break;
                case 'bulan_ini':
                    $query->whereMonth('tanggal', Carbon::now()->month)
                        ->whereYear('tanggal', Carbon::now()->year);
                    break;
            }
        }

        $wisatas = $query->orderBy('tanggal', 'asc')->paginate(10);

        // mempertahankan query search pada saat pindah halaman
        $wisatas->appends($request->all());

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
     */
    public function store(StoreWisataKuotaRequest $request)
    {
        try {
            WisataKuota::create([
                'wisata_id' => $request->wisata_id,
                'tanggal' => $request->tanggal,
                'kuota_total' => $request->kuota_total,
                'kuota_terpakai' => 0,
            ]);

            return redirect()
                ->route('admin.wisata.tiket')
                ->with('success', 'Kuota wisata berhasil ditambahkan');
        } catch (\Exception $e) {
            // Handle unique constraint violation
            if (str_contains($e->getMessage(), 'Duplicate entry') || str_contains($e->getMessage(), 'UNIQUE constraint')) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->withErrors(['tanggal' => 'Kuota untuk wisata ini pada tanggal tersebut sudah ada']);
            }

            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan saat menyimpan data']);
        }
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

    /**
     * Check ticket availability via API
     * GET /api/check-ticket-availability?wisata_id=1&tanggal=2026-02-15
     */
    public function checkAvailability(Request $request)
    {
        $request->validate([
            'wisata_id' => 'required|exists:wisatas,id',
            'tanggal' => 'required|date',
        ]);

        $kuota = WisataKuota::where('wisata_id', $request->wisata_id)
            ->where('tanggal', $request->tanggal)
            ->first();

        if (! $kuota) {
            return response()->json([
                'available' => false,
                'message' => 'Tiket tidak tersedia untuk tanggal ini',
                'sisaTiket' => 0,
            ], 200);
        }

        $sisaTiket = $kuota->kuota_total - $kuota->kuota_terpakai;

        if ($sisaTiket <= 0) {
            return response()->json([
                'available' => false,
                'message' => 'Tiket untuk tanggal ini sudah terjual habis',
                'sisaTiket' => 0,
                'kuota_total' => $kuota->kuota_total,
            ], 200);
        }

        return response()->json([
            'available' => true,
            'message' => "Tersedia {$sisaTiket} tiket untuk tanggal ini",
            'sisaTiket' => $sisaTiket,
            'kuota_total' => $kuota->kuota_total,
            'kuota_terpakai' => $kuota->kuota_terpakai,
        ], 200);
    }
}

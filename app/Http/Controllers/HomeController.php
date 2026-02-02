<?php

namespace App\Http\Controllers;

use App\Models\Artikel;
use App\Models\Kota;
use App\Models\Ulasan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public static function getNavKotas()
    {
        return Kota::orderBy('name')->limit(10)->get();
    }

    public function index()
    {
        // Ambil artikel unggulan
        $artikels = Artikel::with(['kota', 'user'])
            ->latest()
            ->take(6)
            ->get();

        // nampilin kota dengan wisata unggulan
        $kotaWithWisata = Kota::with(['wisatas' => function ($query) {
            $query->where('status', 'Open')
                ->latest()
                ->take(3);
        }])
            ->has('wisatas')
            ->withCount('wisatas')
            ->orderBy('wisatas_count', 'desc')
            ->take(4)
            ->get();

        // format jam buka dna tutup
        $kotaWithWisata->each(function($kota) {
            $kota->wisatas->each(function ($wisata) {
                $wisata->jam_buka_format = Carbon::parse($wisata->jam_buka)->format('H:i');
                $wisata->jam_tutup_format = Carbon::parse($wisata->jam_tutup)->format('H:i');
            });
        });

        return view('welcome', compact('artikels', 'kotaWithWisata'));
    }

    public function showHistory()
    {
        $user = Auth::user();

        // ambil ulasan user dengan relationship reviewable
        $ulasans = Ulasan::where('user_id', $user->id)
            ->with('reviewable')
            ->latest()
            ->paginate(12);

        return view('member.history', compact('ulasans'));
    }
}

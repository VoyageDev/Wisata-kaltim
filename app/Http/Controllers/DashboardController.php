<?php

namespace App\Http\Controllers;

use App\Models\Artikel;
use App\Models\Bookings;
use App\Models\Kota;
use App\Models\Ulasan;
use App\Models\Wisata;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        $artikelCount = Artikel::count();
        $wisataCount = Wisata::count();
        $kotaCount = Kota::count();
        $ulasanCount = Ulasan::count();
        $bookingsCount = Bookings::count();

        return view('dashboard', compact('artikelCount', 'wisataCount', 'kotaCount', 'ulasanCount', 'bookingsCount'));
    }
}

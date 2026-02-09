<?php

namespace App\Http\Controllers;

use App\Models\Artikel;
use App\Models\Kota;
use App\Models\Ulasan;
use App\Models\Wisata;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ArtikelController extends Controller
{
    public function index()
    {
        $artikels = Artikel::with(['wisata.kota', 'user'])->latest()->paginate(10);

        return view('admin.artikel.index', compact('artikels'));
    }

    public function memberIndex()
    {
        // Berita Terbaru - latest articles
        $beritaTerbaru = Artikel::with(['wisata.kota', 'user'])
            ->latest()
            ->take(6)
            ->get();
        $totalBeritaTerbaru = Artikel::count();

        // Populer Bulan Ini - most viewed this month
        $populerBulanIni = Artikel::with(['wisata.kota', 'user'])
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->orderBy('views', 'desc')
            ->take(5)
            ->get();
        $totalPopulerBulanIni = Artikel::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Top Wisata - highest viewed articles about wisata
        $topWisata = Artikel::with(['wisata.kota', 'user'])
            ->orderBy('views', 'desc')
            ->take(6)
            ->get();
        $totalTopWisata = Artikel::count();

        return view('member.artikel', compact(
            'beritaTerbaru',
            'totalBeritaTerbaru',
            'populerBulanIni',
            'totalPopulerBulanIni',
            'topWisata',
            'totalTopWisata'
        ));
    }

    public function show(Artikel $artikel)
    {
        return view('admin.artikel.show', compact('artikel'));
    }

    // memuat detail artikel beserta ulasan berdasarkan slug
    public function detail($slug)
    {
        $artikel = Artikel::with([
            'wisata.kota',
            'user',
            'ulasans' => function ($query) {
                $query->whereNull('parent_id')->with('user');
            },
        ])->where('slug', $slug)->firstOrFail();

        // Get all replies for nested comments
        $allReplies = Ulasan::where('reviewable_id', $artikel->id)
            ->where('reviewable_type', 'App\Models\Artikel')
            ->whereNotNull('parent_id')
            ->with('user')
            ->get();

        // Get related articles from same wisata
        $artikelTerkait = Artikel::with(['wisata.kota', 'user'])
            ->where('wisata_id', $artikel->wisata_id)
            ->where('id', '!=', $artikel->id)
            ->withCount('ulasans')
            ->latest()
            ->take(6)
            ->get();

        return view('member.artikel-detail', compact('artikel', 'artikelTerkait', 'allReplies'));
    }

    public function create()
    {
        $kotas = Kota::all();

        return view('admin.artikel.create', compact('kotas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'kota_id' => 'required|exists:kotas,id',
        ]);

        $thumbnail = $request->file('thumbnail')->store('artikel', 'public');

        Artikel::create([
            'judul' => $validated['judul'],
            'slug' => Str::slug($validated['judul']),
            'isi' => $validated['isi'],
            'thumbnail' => $thumbnail,
            'kota_id' => $validated['kota_id'],
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('admin.artikel.index')->with('success', 'Artikel berhasil dibuat');
    }

    public function edit(Artikel $artikel)
    {
        $kotas = Kota::all();

        return view('admin.artikel.edit', compact('artikel', 'kotas'));
    }

    public function update(Request $request, Artikel $artikel)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'kota_id' => 'required|exists:kotas,id',
        ]);

        $data = $validated;
        $data['slug'] = Str::slug($validated['judul']);

        if ($request->hasFile('thumbnail')) {
            $thumbnail = $request->file('thumbnail')->store('artikel', 'public');
            $data['thumbnail'] = $thumbnail;
        }

        $artikel->update($data);

        return redirect()->route('admin.artikel.index')->with('success', 'Artikel berhasil diperbarui');
    }

    public function destroy(Artikel $artikel)
    {
        $artikel->delete();

        return redirect()->route('admin.artikel.index')->with('success', 'Artikel berhasil dihapus');
    }

    public function loadMoreTerbaru($offset)
    {
        $offset = max(0, (int) $offset);

        $artikels = Artikel::with(['wisata.kota', 'user'])
            ->latest()
            ->skip($offset)
            ->take(6)
            ->get();

        return response()->json($this->buildArtikelPayload($artikels));
    }

    public function loadMorePopuler($offset)
    {
        $offset = max(0, (int) $offset);

        $artikels = Artikel::with(['wisata.kota', 'user'])
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->orderBy('views', 'desc')
            ->skip($offset)
            ->take(5)
            ->get();

        return response()->json($this->buildArtikelPayload($artikels));
    }

    public function loadMoreTopWisata($offset)
    {
        $offset = max(0, (int) $offset);

        $artikels = Artikel::with(['wisata.kota', 'user'])
            ->orderBy('views', 'desc')
            ->skip($offset)
            ->take(6)
            ->get();

        return response()->json($this->buildArtikelPayload($artikels));
    }

    private function buildArtikelPayload($artikels)
    {
        return $artikels->map(function (Artikel $artikel) {
            $isi = $artikel->isi;
            if (is_array($isi)) {
                $isi = implode(' ', $isi);
            }

            return [
                'judul' => $artikel->judul,
                'slug' => $artikel->slug,
                'thumbnail' => $artikel->thumbnail,
                'isi' => $isi ?? '',
                'created_at' => $artikel->created_at,
                'views' => $artikel->views ?? 0,
                'user' => [
                    'name' => optional($artikel->user)->name ?? '',
                ],
                'kota' => [
                    'name' => optional(optional($artikel->wisata)->kota)->name ?? '',
                ],
            ];
        });
    }
}

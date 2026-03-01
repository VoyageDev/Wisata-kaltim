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
    public function index(Request $request)
    {
        $search = $request->get('search');

        $query = Artikel::with(['wisata.kota', 'user'])->latest();

        if ($search) {
            $query->where(function ($q) use ($search) {
                // Cari berdasarkan judul artikel
                $q->where('judul', 'like', "%{$search}%")
                  // ATAU cari berdasarkan nama wisata terkait
                    ->orWhereHas('wisata', function ($w) use ($search) {
                        $w->where('name', 'like', "%{$search}%");
                    })
                  // ATAU cari berdasarkan nama penulis
                    ->orWhereHas('user', function ($u) use ($search) {
                        $u->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $artikels = $query->paginate(10)->appends($request->query());

        // Tambahkan 'search' ke compact agar bisa ditangkap oleh view
        return view('admin.artikel.index', compact('artikels', 'search'));
    }

    public function memberIndex(Request $request)
    {
        $search = $request->get('search');

        if ($search) {
            $searchResults = Artikel::with(['wisata.kota', 'user'])
                ->where('judul', 'like', "%{$search}%")
                ->latest()
                ->paginate(10)
                ->appends($request->query());

            return view('member.berita.index', [
                'search' => $search,
                'searchResults' => $searchResults,
                'beritaTerbaru' => collect(),
                'totalBeritaTerbaru' => 0,
                'populerBulanIni' => collect(),
                'totalPopulerBulanIni' => 0,
                'topWisata' => collect(),
                'totalTopWisata' => 0,
            ]);
        }

        // Berita Terbaru - latest articles
        $beritaQuery = Artikel::with(['wisata.kota', 'user'])->latest();
        if ($search) {
            $beritaQuery->where('judul', 'like', "%{$search}%");
        }
        $totalBeritaTerbaru = (clone $beritaQuery)->count();
        $beritaTerbaru = $beritaQuery->take(6)->get();

        // Populer Bulan Ini - most viewed this month
        $populerQuery = Artikel::with(['wisata.kota', 'user'])
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->orderBy('views', 'desc');
        if ($search) {
            $populerQuery->where('judul', 'like', "%{$search}%");
        }
        $totalPopulerBulanIni = (clone $populerQuery)->count();
        $populerBulanIni = $populerQuery->take(5)->get();

        // Top Wisata - highest viewed articles about wisata
        $topWisataQuery = Artikel::with(['wisata.kota', 'user'])->orderBy('views', 'desc');
        if ($search) {
            $topWisataQuery->where('judul', 'like', "%{$search}%");
        }
        $totalTopWisata = (clone $topWisataQuery)->count();
        $topWisata = $topWisataQuery->take(6)->get();

        $searchResults = null;

        return view('member.berita.index', compact(
            'beritaTerbaru',
            'totalBeritaTerbaru',
            'populerBulanIni',
            'totalPopulerBulanIni',
            'topWisata',
            'totalTopWisata',
            'search',
            'searchResults'
        ));
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

        return view('member.berita.detail', compact('artikel', 'artikelTerkait', 'allReplies'));
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

    public function loadMoreTerbaru(Request $request, $offset)
    {
        $offset = max(0, (int) $offset);

        $search = $request->get('search');

        $query = Artikel::with(['wisata.kota', 'user'])->latest();
        if ($search) {
            $query->where('judul', 'like', "%{$search}%");
        }
        $artikels = $query->skip($offset)->take(6)->get();

        return response()->json($this->buildArtikelPayload($artikels));
    }

    public function loadMorePopuler(Request $request, $offset)
    {
        $offset = max(0, (int) $offset);

        $search = $request->get('search');

        $query = Artikel::with(['wisata.kota', 'user'])
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->orderBy('views', 'desc');
        if ($search) {
            $query->where('judul', 'like', "%{$search}%");
        }
        $artikels = $query->skip($offset)->take(5)->get();

        return response()->json($this->buildArtikelPayload($artikels));
    }

    public function loadMoreTopWisata(Request $request, $offset)
    {
        $offset = max(0, (int) $offset);

        $search = $request->get('search');

        $query = Artikel::with(['wisata.kota', 'user'])->orderBy('views', 'desc');
        if ($search) {
            $query->where('judul', 'like', "%{$search}%");
        }
        $artikels = $query->skip($offset)->take(6)->get();

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

<?php

namespace App\Http\Controllers;

use App\Models\Kota;
use App\Models\Ulasan;
use App\Models\Wisata;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class WisataController extends Controller
{
    public function index()
    {
        $wisatas = Wisata::with('kota')->latest()->paginate(10);

        return view('admin.wisata.index', compact('wisatas'));
    }

    public function memberIndex()
    {
        $wisatas = Wisata::with('kota')->latest()->paginate(12);

        return view('member.wisata', compact('wisatas'));
    }

    public function create()
    {
        $kotas = Kota::all();

        return view('admin.wisata.create', compact('kotas'));
    }

    public function show(Wisata $wisata)
    {
        $kotas = Kota::all();

        return view('admin.wisata.show', compact('wisata', 'kotas'));
    }

    public function detail($slug)
    {
        $wisata = Wisata::with(['kota', 'ulasans' => function ($query) {
            $query->whereNull('parent_id')->with('user');
        }])->where('slug', $slug)->firstOrFail();

        // Get all replies for nested comments
        $allReplies = Ulasan::where('reviewable_id', $wisata->id)
            ->where('reviewable_type', 'App\Models\Wisata')
            ->whereNotNull('parent_id')
            ->with('user')
            ->get();

        return view('member.wisata-detail', compact('wisata', 'allReplies'));
    }

    public function store(Request $request)
    {
        if ($request->has('harga_tiket')) {
            $request->merge([
                'harga_tiket' => str_replace('.', '', $request->harga_tiket),
            ]);
        }
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'alamat' => 'required|string',
            'gambar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'jam_buka' => 'required|date_format:H:i',
            'jam_tutup' => 'required|date_format:H:i',
            'harga_tiket' => 'required|numeric|min:0',
            'status' => 'required|in:Open,Closed',
            'kota_id' => 'required|exists:kotas,id',
            'links_maps' => 'nullable|url',
        ]);

        // buat nama file gambar berdasarkan slug nama wisata
        $fileName = Str::slug($validated['name']).'.'.$request->file('gambar')->getClientOriginalExtension();
        $request->file('gambar')->move(public_path('images/seed/wisata'), $fileName);

        Wisata::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'],
            'alamat' => $validated['alamat'],
            'gambar' => $fileName,
            'jam_buka' => $validated['jam_buka'],
            'jam_tutup' => $validated['jam_tutup'],
            'harga_tiket' => $validated['harga_tiket'],
            'status' => $validated['status'],
            'kota_id' => $validated['kota_id'],
            'links_maps' => $validated['links_maps'] ?? null,
        ]);

        return redirect()->route('admin.wisata.index')->with('success', 'Wisata berhasil dibuat');
    }

    public function edit(Wisata $wisata)
    {
        $kotas = Kota::all();

        return view('admin.wisata.edit', compact('wisata', 'kotas'));
    }

    public function update(Request $request, Wisata $wisata)
    {
        if ($request->has('harga_tiket')) {
            $request->merge([
                'harga_tiket' => str_replace('.', '', $request->harga_tiket),

            ]);
        }
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'alamat' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'jam_buka' => 'required|date_format:H:i',
            'jam_tutup' => 'required|date_format:H:i',
            'harga_tiket' => 'required|numeric|min:0',
            'status' => 'required|in:Open,Close',
            'kota_id' => 'required|exists:kotas,id',
            'links_maps' => 'nullable|url',
        ]);

        $data = $validated;
        $data['slug'] = Str::slug($validated['name']);

        if ($request->hasFile('gambar')) {
            if ($wisata->gambar) {
                File::delete(public_path('images/seed/wisata/'.$wisata->gambar));
            }

            $fileName = $data['slug'].'.'.$request->file('gambar')->getClientOriginalExtension();
            $request->file('gambar')->move(public_path('images/seed/wisata'), $fileName);
            $data['gambar'] = $fileName;
        }
        $wisata->update($data);

        return redirect()->route('admin.wisata.index')->with('success', 'Wisata berhasil diperbarui');
    }

    public function destroy(Wisata $wisata)
    {
        $wisata->delete();

        return redirect()->route('admin.wisata.index')->with('success', 'Wisata berhasil dihapus');
    }
}

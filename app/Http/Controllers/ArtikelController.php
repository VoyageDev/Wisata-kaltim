<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Artikel;
use App\Models\Kota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ArtikelController extends Controller
{
    public function index()
    {
        $artikels = Artikel::with(['kota', 'user'])->latest()->paginate(10);

        return view('admin.artikel.index', compact('artikels'));
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
}

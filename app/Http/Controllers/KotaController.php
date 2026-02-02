<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Kota;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class KotaController extends Controller
{
    public function index()
    {
        $kotas = Kota::withCount(['wisatas', 'artikels'])->latest()->paginate(10);

        return view('admin.kota.index', compact('kotas'));
    }

    public function create()
    {
        return view('admin.kota.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:kotas,name',
            'deskripsi' => 'required|string',
        ]);

        Kota::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'deskripsi' => $validated['deskripsi'],
        ]);

        return redirect()->route('admin.kota.index')->with('success', 'Kota berhasil dibuat');
    }

    public function edit(Kota $kota)
    {
        return view('admin.kota.edit', compact('kota'));
    }

    public function update(Request $request, Kota $kota)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:kotas,name,'.$kota->id,
            'deskripsi' => 'required|string',
        ]);

        $data = $validated;
        $data['slug'] = Str::slug($validated['name']);

        $kota->update($data);

        return redirect()->route('admin.kota.index')->with('success', 'Kota berhasil diperbarui');
    }

    public function destroy(Kota $kota)
    {
        $kota->delete();

        return redirect()->route('admin.kota.index')->with('success', 'Kota berhasil dihapus');
    }
}

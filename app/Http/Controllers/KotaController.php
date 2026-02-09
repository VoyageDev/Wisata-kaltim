<?php

namespace App\Http\Controllers;

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

    public function memberIndex()
    {
        $kotas = Kota::withCount(['wisatas', 'artikels'])->latest()->paginate(12);

        return view('member.kota', compact('kotas'));
    }

    public function show(Kota $kota)
    {
        return view('admin.kota.show', compact('kota'));
    }

    public function detail($slug)
    {
        $kota = Kota::with([
            'wisatas' => function ($query) {
                $query->with(['ulasans' => function ($q) {
                    $q->with('user')->latest()->take(10);
                }]);
            },
            'artikels' => function ($query) {
                $query->with('user')->withCount('ulasans');
            },
        ])->where('slug', $slug)->firstOrFail();

        // Get related articles for this kota
        $artikelTerkait = $kota->artikels()->with('user')->latest()->take(6)->get();

        return view('member.kota-detail', compact('kota', 'artikelTerkait'));
    }

    public function create()
    {
        return view('admin.kota.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:kotas,name',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $file = $request->file('image');
        $fileName = Str::slug($validated['name']).'.'.$file->getClientOriginalExtension();
        $file->move(public_path('images/seed/kota'), $fileName);

        Kota::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'image' => $fileName,
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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
        ];

        if ($request->hasFile('image')) {
            // Hapus gambar lama dari folder
            if ($kota->image && file_exists(public_path('images/seed/kota/'.$kota->image))) {
                unlink(public_path('images/seed/kota/'.$kota->image));
            }

            $file = $request->file('image');
            $fileName = Str::slug($validated['name']).'.'.$file->getClientOriginalExtension();
            $file->move(public_path('images/seed/kota'), $fileName);
            $data['image'] = $fileName;
        }

        $kota->update($data);

        return redirect()->route('admin.kota.index')->with('success', 'Kota berhasil diperbarui');
    }

    public function destroy(Kota $kota)
    {
        $kota->delete();

        return redirect()->route('admin.kota.index')->with('success', 'Kota berhasil dihapus');
    }
}

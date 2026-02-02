<?php

namespace App\Http\Controllers;

use App\Models\Ulasan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UlasanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Ulasan::with(['reviewable', 'user']);

        // Search by komentar
        if (request('search')) {
            $search = request('search');
            $query->where('komentar', 'like', "%{$search}%");
        }

        // Filter type
        if (request('type') && request('type') !== '') {
            $query->where('reviewable_type', request('type'));
        }

        // Filter rating
        if (request('rating') && request('rating') !== '') {
            $query->where('rating', (int) request('rating'));
        }

        $ulasans = $query->latest()->paginate(10)->appends(request()->query());

        return view('admin.ulasan.index', compact('ulasans'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Ulasan $ulasan)
    {
        $ulasan->load(['reviewable', 'user']);

        return view('admin.ulasan.show', compact('ulasan'));
    }

    /**
     * Store a newly created ulasan (review/comment) from member
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'reviewable_type' => 'required|in:App\Models\Artikel,App\Models\Wisata',
            'reviewable_id' => 'required|integer',
            'komentar' => 'required|string|max:1000',
            'rating' => 'nullable|integer|min:1|max:5',
            'parent_id' => 'nullable|exists:ulasans,id',
        ]);

        $ulasan = Ulasan::create([
            'user_id' => Auth::id(),
            'reviewable_type' => $validated['reviewable_type'],
            'reviewable_id' => $validated['reviewable_id'],
            'komentar' => $validated['komentar'],
            'rating' => $validated['rating'] ?? null,
            'parent_id' => $validated['parent_id'] ?? null,
        ]);

        // Load relationships for response
        $ulasan->load(['user', 'reviewable']);

        return back()->with('success', 'Ulasan berhasil ditambahkan');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ulasan $ulasan)
    {
        $ulasan->delete();

        return redirect()
            ->route('admin.ulasan.index')
            ->with('success', 'Ulasan telah dihapus');
    }
}

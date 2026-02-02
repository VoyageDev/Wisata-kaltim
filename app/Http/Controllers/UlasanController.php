<?php

namespace App\Http\Controllers;

use App\Models\Ulasan;

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

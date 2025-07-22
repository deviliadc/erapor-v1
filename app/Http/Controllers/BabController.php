<?php

namespace App\Http\Controllers;

use App\Models\Bab;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class BabController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $perPage = $request->input('per_page', 10);
        // $sortBy = $request->input('sortBy', 'id'); // default sorting
        // $sortDirection = $request->input('sortDirection', 'asc'); // default direction

        // $query = Bab::query();

        // if ($search = $request->input('search')) {
        //     $query->where('nama', 'like', "%$search%");
        // }

        // // Validasi nama kolom yang boleh di-sort (whitelist)
        // $allowedSorts = ['id', 'nama', 'created_at'];
        // if (in_array($sortBy, $allowedSorts)) {
        //     $query->orderBy($sortBy, $sortDirection);
        // }

        // $totalCount = $query->count();
        // $paginator = $query->paginate($perPage)->withQueryString();

        // // data untuk tampilan
        // $bab = $paginator;

        // $breadcrumbs = [
        //     ['label' => 'Manage Bab', 'url' => route('bab.index')]
        // ];

        // $title = 'Manage Bab';

        // return view('bab.index',  compact('bab', 'totalCount', 'breadcrumbs', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $bab = new Bab();

        return view('bab.create', [
            'bab' => $bab,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|unique:bab,nama',
        ]);

        try {
            Bab::create([
                'nama' => $request->nama,
            ]);

            return redirect()->route('mapel.index', ['tab' => $request->tab ?? 'bab'])->with('success', 'Bab berhasil ditambahkan.');
        } catch (QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                // Duplikat entry
                return back()->withErrors(['nama' => 'Nama bab sudah ada. Silakan gunakan nama lain.'])->withInput();
            }

            // Error lain
            return back()->withErrors(['error' => 'Terjadi kesalahan saat menyimpan data.'])->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $bab = Bab::findOrFail($id);
        
        $item = [
            'id' => $bab->id,
            'nama' => $bab->nama,
        ];

        return view('bab.edit', compact('bab', 'item'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $bab = Bab::findOrFail($id);

        $validated = $request->validate([
            'nama'       => 'required|string|max:100',
        ]);

        $bab->update($validated);

        return redirect()->route('mapel.index', ['tab' => $request->tab ?? 'bab'])->with('success', 'Bab berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $bab = Bab::findOrFail($id);
            $bab->delete();
            return redirect()->route('mapel.index', ['tab' => $request->tab ?? 'bab'])->with('success', 'Bab berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('mapel.index', ['tab' => $request->tab ?? 'bab'])->with('error', 'Gagal menghapus bab. Pastikan tidak sedang digunakan.');
        }
    }
}

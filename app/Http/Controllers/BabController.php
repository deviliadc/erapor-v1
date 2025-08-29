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
        // 
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // $bab = new Bab();

        // return view('bab.create', [
        //     'bab' => $bab,
        // ]);
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

            return redirect()->to(role_route('mapel.index', ['tab' => $request->tab ?? 'bab']))->with('success', 'Bab berhasil ditambahkan.');
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
        // $bab = Bab::findOrFail($id);

        // $item = [
        //     'id' => $bab->id,
        //     'nama' => $bab->nama,
        // ];

        // return view('bab.edit', compact('bab', 'item'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $bab = Bab::findOrFail($id);
        $validated = $request->validate([
            'nama' => 'required|string|max:100|unique:bab,nama,' . $bab->id,
        ]);
        $bab->update($validated);
        return redirect()->to(role_route('mapel.index', ['tab' => $request->tab ?? 'bab']))->with('success', 'Bab berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $bab = Bab::findOrFail($id);

            // Cek relasi ke LingkupMateri, TujuanPembelajaran, dll
            $related = (
                $bab->lingkupMateri()->exists() ||
                $bab->tujuanPembelajaran()->exists()
            );

            if ($related) {
                return redirect()->to(role_route('mapel.index', ['tab' => request('tab', 'bab')]))
                    ->with('error', 'Bab tidak bisa dihapus karena masih digunakan pada data lain.');
            }

            $bab->delete();
            return redirect()->to(role_route('mapel.index', ['tab' => request('tab', 'bab')]))
                ->with('success', 'Bab berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->to(role_route('mapel.index', ['tab' => request('tab', 'bab')]))
                ->with('error', 'Gagal menghapus bab. Pastikan tidak sedang digunakan.');
        }
    }
}

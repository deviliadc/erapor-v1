<?php

namespace App\Http\Controllers;

use App\Models\P5Dimensi;
use Illuminate\Http\Request;

class P5DimensiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $p5_dimensi = new P5Dimensi();

        return view('p5-dimensi.create', [
            'p5_dimensi' => $p5_dimensi,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
        'nama_dimensi' => 'required|string|max:255',
        'deskripsi_dimensi' => 'nullable|string|max:1000', // ganti ke deskripsi_tema
        ]);

        P5Dimensi::create([
            'nama_dimensi' => $validated['nama_dimensi'],
            'deskripsi' => $validated['deskripsi_dimensi'] ?? null, // mapping ke field database 'deskripsi'
        ]);

        return redirect()->to(role_route('p5.index', ['tab' => $request->tab ?? 'dimensi']))->with('success', 'Dimensi P5 berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $p5_dimensi = P5Dimensi::findOrFail($id);

        // Mapping agar konsisten dengan form
        $item = [
            'id' => $p5_dimensi->id,
            'nama_dimensi' => $p5_dimensi->nama_dimensi,
            'deskripsi_dimensi' => $p5_dimensi->deskripsi,
        ];

        return view('p5-dimensi.edit', compact('p5_dimensi', 'item'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $p5_dimensi = P5Dimensi::findOrFail($id);

        $validated = $request->validate([
            'nama_dimensi' => 'required|string|max:255',
            'deskripsi_dimensi' => 'nullable|string|max:1000',
        ]);

        $p5_dimensi->update([
            'nama_dimensi' => $validated['nama_dimensi'],
            'deskripsi' => $validated['deskripsi_dimensi'] ?? null,
        ]);

        return redirect()->to(role_route('p5.index', ['tab' => $request->tab ?? 'dimensi']))->with('success', 'Dimensi P5 berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        try {
            $p5_dimensi = P5Dimensi::findOrFail($id);

            // Cek apakah masih digunakan di tabel p5_elemen
            if ($p5_dimensi->elemen()->exists()) {
                return redirect()->to(role_route('p5.index', ['tab' => $request->tab ?? 'dimensi']))
                    ->with('error', 'Dimensi tidak dapat dihapus karena masih digunakan pada elemen.');
            }

            // Cek apakah masih digunakan di tabel p5_proyek_detail
            if ($p5_dimensi->proyekDetail()->exists()) {
                return redirect()->to(role_route('p5.index', ['tab' => $request->tab ?? 'dimensi']))
                    ->with('error', 'Dimensi tidak dapat dihapus karena masih digunakan pada proyek.');
            }

            $p5_dimensi->delete();
            return redirect()->to(role_route('p5.index', ['tab' => $request->tab ?? 'dimensi']))
                ->with('success', 'Dimensi P5 berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->to(role_route('p5.index', ['tab' => $request->tab ?? 'dimensi']))
                ->with('error', 'Gagal menghapus Dimensi P5. Pastikan tidak sedang digunakan.');
        }
    }
}

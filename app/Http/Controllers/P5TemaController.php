<?php

namespace App\Http\Controllers;

use App\Models\P5Tema;
use Illuminate\Http\Request;

class P5TemaController extends Controller
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
        $p5_tema = new P5Tema();

        return view('p5-tema.create', [
            'p5_tema' => $p5_tema,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
        'nama_tema' => 'required|string|max:255',
        'deskripsi_tema' => 'nullable|string|max:1000', // ganti ke deskripsi_tema
        ]);

        P5Tema::create([
            'nama_tema' => $validated['nama_tema'],
            'deskripsi' => $validated['deskripsi_tema'] ?? null, // mapping ke field database 'deskripsi'
        ]);

        return redirect()->to(role_route('p5.index', ['tab' => $request->tab ?? 'tema']))->with('success', 'Tema P5 berhasil ditambahkan.');
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
        $p5_tema = P5Tema::findOrFail($id);

        // Mapping agar konsisten dengan form
        $item = [
            'id' => $p5_tema->id,
            'nama_tema' => $p5_tema->nama_tema,
            'deskripsi_tema' => $p5_tema->deskripsi,
        ];

        return view('p5-tema.edit', compact('p5_tema', 'item'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $p5_tema = P5Tema::findOrFail($id);

        $validated = $request->validate([
            'nama_tema' => 'required|string|max:255',
            'deskripsi_tema' => 'nullable|string|max:1000',
        ]);

        $p5_tema->update([
            'nama_tema' => $validated['nama_tema'],
            'deskripsi' => $validated['deskripsi_tema'] ?? null,
        ]);

        return redirect()->to(role_route('p5.index', ['tab' => $request->tab ?? 'tema']))->with('success', 'Tema P5 berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $p5_tema = P5Tema::findOrFail($id);

            // Cek apakah tema masih digunakan di tabel p5_proyek
            if ($p5_tema->proyek()->exists()) {
                return redirect()->to(role_route('p5.index', ['tab' => request('tab') ?? 'tema']))
                    ->with('error', 'Tema tidak dapat dihapus karena masih digunakan pada proyek.');
            }

            $p5_tema->delete();
            return redirect()->to(role_route('p5.index', ['tab' => request('tab') ?? 'tema']))
                ->with('success', 'Tema P5 berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->to(role_route('p5.index', ['tab' => request('tab') ?? 'tema']))
                ->with('error', 'Gagal menghapus Tema P5. Tema masih digunakan pada data lain.');
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\P5SubElemen;
use Illuminate\Http\Request;

class P5SubElemenController extends Controller
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
        $p5_subelemen = new P5SubElemen();

        return view('p5-subelemen.create', [
            'p5_subelemen' => $p5_subelemen,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'p5_elemen_id' => 'required|exists:p5_elemen,id',
            'nama_sub_elemen' => 'required|string|max:255',
            'deskripsi_sub_elemen' => 'nullable|string|max:1000',
        ]);

        P5SubElemen::create([
            'p5_elemen_id' => $validated['p5_elemen_id'],
            'nama_sub_elemen' => $validated['nama_sub_elemen'],
            'deskripsi' => $validated['deskripsi_sub_elemen'] ?? null,
        ]);

        return redirect()->route('p5.index', ['tab' => $request->tab ?? 'subelemen'])->with('success', 'Sub Elemen P5 berhasil ditambahkan.');
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
        $p5_subelemen = P5SubElemen::findOrFail($id);

        $item = [
            'id' => $p5_subelemen->id,
            'p5_elemen_id' => $p5_subelemen->p5_elemen_id,
            'nama_sub_elemen' => $p5_subelemen->nama_sub_elemen,
            'deskripsi_sub_elemen' => $p5_subelemen->deskripsi,
        ];

        return view('p5-subelemen.edit', compact('p5_subelemen', 'item'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $p5_subelemen = P5SubElemen::findOrFail($id);

        $validated = $request->validate([
            'p5_elemen_id' => 'required|exists:p5_elemen,id',
            'nama_sub_elemen' => 'required|string|max:255',
            'deskripsi_sub_elemen' => 'nullable|string|max:1000',
        ]);

        $p5_subelemen->update([
            'p5_elemen_id' => $validated['p5_elemen_id'],
            'nama_sub_elemen' => $validated['nama_sub_elemen'],
            'deskripsi' => $validated['deskripsi_sub_elemen'] ?? null,
        ]);

        return redirect()->route('p5.index', ['tab' => $request->tab ?? 'subelemen'])->with('success', 'Sub Elemen P5 berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $p5_subelemen = P5SubElemen::findOrFail($id);
            $p5_subelemen->delete();
            return redirect()->route('p5.index', ['tab' => $request->tab ?? 'subelemen'])->with('success', 'Sub Elemen P5 berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('p5.index', ['tab' => $request->tab ?? 'subelemen'])->with('error', 'Gagal menghapus Sub Elemen P5. Pastikan tidak sedang digunakan.');
        }
    }
}

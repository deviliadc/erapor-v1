<?php

namespace App\Http\Controllers;

use App\Models\P5Elemen;
use Illuminate\Http\Request;

class P5ElemenController extends Controller
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
        $p5_elemen = new P5Elemen();

        return view('p5-elemen.create', [
            'p5_elemen' => $p5_elemen,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'p5_dimensi_id' => 'required|exists:p5_dimensi,id',
            'nama_elemen' => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:1000',
        ]);

        P5Elemen::create([
            'p5_dimensi_id' => $validated['p5_dimensi_id'],
            'nama_elemen' => $validated['nama_elemen'],
            'deskripsi' => $validated['deskripsi_elemen'] ?? null,
        ]);

        return redirect()->to(role_route('p5.index', ['tab' => $request->tab ?? 'elemen']))->with('success', 'Elemen P5 berhasil ditambahkan.');
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
        $p5_elemen = P5Elemen::findOrFail($id);

        $item = [
            'id' => $p5_elemen->id,
            'nama_elemen' => $p5_elemen->nama_elemen,
            'deskripsi_elemen' => $p5_elemen->deskripsi,
            'p5_dimensi_id' => $p5_elemen->p5_dimensi_id,
        ];

        return view('p5-elemen.edit', compact('p5_elemen', 'item'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $p5_elemen = P5Elemen::findOrFail($id);

        $validated = $request->validate([
            'p5_dimensi_id' => 'required|exists:p5_dimensi,id',
            'nama_elemen' => 'required|string|max:255',
            'deskripsi_elemen' => 'nullable|string|max:1000',
        ]);

        $p5_elemen->update([
            'p5_dimensi_id' => $validated['p5_dimensi_id'],
            'nama_elemen' => $validated['nama_elemen'],
            'deskripsi' => $validated['deskripsi_elemen'] ?? null,
        ]);

        return redirect()->to(role_route('p5.index', ['tab' => $request->tab ?? 'elemen']))->with('success', 'Elemen P5 berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $p5_elemen = P5Elemen::findOrFail($id);

            // Cek apakah elemen masih digunakan di tabel p5_sub_elemen
            // if ($p5_elemen->subElemen()->exists()) {
            //     return redirect()->to(role_route('p5.index', ['tab' => request('tab') ?? 'elemen']))
            //         ->with('error', 'Elemen tidak dapat dihapus karena masih digunakan pada sub elemen.');
            // }

            // Cek apakah elemen masih digunakan di tabel p5_proyek_detail
            // if ($p5_elemen->proyekDetail()->exists()) {
            //     return redirect()->to(role_route('p5.index', ['tab' => request('tab') ?? 'elemen']))
            //         ->with('error', 'Elemen tidak dapat dihapus karena masih digunakan pada proyek.');
            // }

            $p5_elemen->delete();
            return redirect()->to(role_route('p5.index', ['tab' => request('tab') ?? 'elemen']))
                ->with('success', 'Elemen P5 berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->to(role_route('p5.index', ['tab' => request('tab') ?? 'elemen']))
                ->with('error', 'Gagal menghapus Elemen P5. Pastikan tidak sedang digunakan.');
        }
    }
}

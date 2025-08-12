<?php

namespace App\Http\Controllers;

use App\Models\P5CapaianFase;
use Illuminate\Http\Request;

class P5CapaianController extends Controller
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
        $p5_capaian = new P5CapaianFase();

        return view('p5-capaian.create', [
            'p5_capaian' => $p5_capaian,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'fase_id' => 'required|exists:fase,id',
            'sub_elemen_id' => 'required|exists:p5_sub_elemen,id',
            'capaian' => 'required|string|max:1000',
        ]);

        P5CapaianFase::create([
            'fase_id' => $validated['fase_id'],
            'p5_sub_elemen_id' => $validated['sub_elemen_id'],
            'capaian' => $validated['capaian'],
        ]);

        return redirect()->to(role_route('p5.index', ['tab' => $request->tab ?? 'capaian']))->with('success', 'Capaian P5 berhasil ditambahkan.');
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
        $p5_capaian = P5CapaianFase::findOrFail($id);

        $item = [
            'id' => $p5_capaian->id,
            'fase_id' => $p5_capaian->fase_id,
            'sub_elemen_id' => $p5_capaian->sub_elemen_id,
            'capaian' => $p5_capaian->capaian,
        ];

        return view('p5-capaian.edit', compact('p5_capaian', 'item'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $p5_capaian = P5CapaianFase::findOrFail($id);

        $validated = $request->validate([
            'fase_id' => 'required|exists:fase,id',
            'sub_elemen_id' => 'required|exists:p5_sub_elemen,id',
            'capaian' => 'required|string|max:1000',
        ]);

        $p5_capaian->update([
            'fase_id' => $validated['fase_id'],
            'p5_sub_elemen_id' => $validated['sub_elemen_id'],
            'capaian' => $validated['capaian'],
        ]);

        return redirect()->to(role_route('p5.index', ['tab' => $request->tab ?? 'capaian']))->with('success', 'Capaian P5 berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        try {
            $p5_capaian = P5CapaianFase::findOrFail($id);

            // Contoh: jika ada relasi ke tabel lain, misal NilaiP5Detail
            if (method_exists($p5_capaian, 'nilaiP5Detail') && $p5_capaian->nilaiP5Detail()->exists()) {
                return redirect()->to(role_route('p5.index', ['tab' => $request->tab ?? 'capaian']))
                    ->with('error', 'Capaian tidak dapat dihapus karena masih digunakan pada data lain.');
            }

            $p5_capaian->delete();
            return redirect()->to(role_route('p5.index', ['tab' => $request->tab ?? 'capaian']))
                ->with('success', 'Capaian P5 berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->to(role_route('p5.index', ['tab' => $request->tab ?? 'capaian']))
                ->with('error', 'Gagal menghapus Capaian P5. Pastikan tidak sedang digunakan.');
        }
    }
}

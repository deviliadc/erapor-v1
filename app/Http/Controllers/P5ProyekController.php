<?php

namespace App\Http\Controllers;

use App\Models\P5Proyek;
use Illuminate\Http\Request;

class P5ProyekController extends Controller
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
        $p5_proyek = new P5Proyek();

        return view('p5-proyek.create', [
            'p5_proyek' => $p5_proyek,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'p5_tema_id' => 'required|exists:p5_tema,id',
            'nama_proyek' => 'required|string|max:255',
            'deskripsi_proyek' => 'nullable|string|max:1000',
            'tahun_semester_id' => 'required|exists:tahun_semester,id',
            'dimensi_id' => 'required|array',
            'dimensi_id.*' => 'exists:p5_dimensi,id',
            'sub_elemen_id' => 'required|array',
            'sub_elemen_id.*' => 'exists:p5_sub_elemen,id',
            'guru_id' => 'required|exists:guru,id',
        ]);

        // Ambil guru_id dari sesi jika diperlukan, atau sesuaikan
        // $guruId = auth()->user()->guru->id ?? null;

        // Simpan data proyek
        $proyek = P5Proyek::create([
            'kelas_id' => $validated['kelas_id'],
            'guru_id' => $validated['guru_id'],
            'p5_tema_id' => $validated['p5_tema_id'],
            'nama_proyek' => $validated['nama_proyek'],
            'deskripsi' => $validated['deskripsi_proyek'] ?? null,
            'tahun_semester_id' => $validated['tahun_semester_id'],
        ]);

        // Simpan ke pivot dimensi dan sub elemen
        $proyek->dimensi()->sync($validated['dimensi_id']);
        $proyek->subElemen()->sync($validated['sub_elemen_id']);

        return redirect()->route('p5.index', ['tab' => $request->tab ?? 'proyek'])
            ->with('success', 'Proyek P5 berhasil ditambahkan.');
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
        $p5_proyek = P5Proyek::findOrFail($id);

        $item = [
            'id' => $p5_proyek->id,
            'nama_proyek' => $p5_proyek->nama_proyek,
            'deskripsi_proyek' => $p5_proyek->deskripsi,
            'kelas_id' => $p5_proyek->kelas_id,
            'guru_id' => $p5_proyek->guru_id,
            'p5_tema_id' => $p5_proyek->p5_tema_id,
            'tahun_semester_id' => $p5_proyek->tahun_semester_id
        ];

        return view('p5-proyek.edit', compact('p5_proyek', 'item'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $p5_proyek = P5Proyek::findOrFail($id);

        $validated = $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'guru_id' => 'required|exists:guru,id',
            'p5_tema_id' => 'required|exists:p5_tema,id',
            'nama_proyek' => 'required|string|max:255',
            'deskripsi_proyek' => 'nullable|string|max:1000',
            'tahun_semester_id' => 'required|exists:tahun_semester,id',
        ]);

        $p5_proyek->update([
            'p5_tema_id' => $validated['p5_tema_id'],
            'kelas_id' => $validated['kelas_id'],
            'guru_id' => $validated['guru_id'],
            'nama_proyek' => $validated['nama_proyek'],
            'deskripsi' => $validated['deskripsi_proyek'] ?? null,
            'tahun_semester_id' => $validated['tahun_semester_id'],
        ]);

        return redirect()->route('p5.index', ['tab' => $request->tab ?? 'proyek'])->with('success', 'Proyek P5 berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $p5_proyek = P5Proyek::findOrFail($id);
            $p5_proyek->delete();
            return redirect()->route('p5.index', ['tab' => 'proyek'])->with('success', 'Proyek P5 berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('p5.index', ['tab' => 'proyek'])->with('error', 'Gagal menghapus proyek. Pastikan tidak sedang digunakan.');
        }
    }
}

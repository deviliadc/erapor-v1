<?php

namespace App\Http\Controllers;

use App\Models\PengaturanRapor;
use App\Models\TahunSemester;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class PengaturanRaporController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tahunAktif = TahunSemester::where('is_active', true)->first();
        $data = PengaturanRapor::where('tahun_semester_id', $tahunAktif?->id)->first();

        $breadcrumbs = [
            ['label' => 'Pengaturan Rapor'],
        ];
        $title = 'Pengaturan Rapor';
        return view('pengaturan-rapor.index', [
            'data' => $data,
            'tahunAktif' => $tahunAktif,
            'breadcrumbs' => $breadcrumbs,
            'title' => $title,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_kepala_sekolah' => 'required|string|max:255',
            'nip_kepala_sekolah' => 'nullable|string|max:50',
            'tempat' => 'required|string|max:100',
            'tanggal_cetak_uts' => 'nullable|date',
            'tanggal_cetak_uas' => 'nullable|date',
        ]);

        $tahunAktif = TahunSemester::where('is_active', true)->first();

        $data = PengaturanRapor::firstOrNew(['tahun_semester_id' => $tahunAktif?->id]);

        $data->fill($request->only([
            'nama_kepala_sekolah',
            'nip_kepala_sekolah',
            'tempat',
            'tanggal_cetak_uts',
            'tanggal_cetak_uas',
        ]));
        $data->tahun_semester_id = $tahunAktif?->id;
        $data->save();

        return redirect()->back()->with('success', 'Pengaturan berhasil disimpan.');
    }

    public function hapusTtd(Request $request)
    {
        $tahunAktif = TahunSemester::where('is_active', true)->first();

        $data = PengaturanRapor::where('tahun_semester_id', $tahunAktif?->id)->first();

        if (!$data || !$data->ttd) {
            return back()->with('error', 'Tanda tangan tidak ditemukan.');
        }

        // Hapus file dari storage
        Storage::disk('public')->delete($data->ttd);

        // Kosongkan kolom di database
        // $data->ttd = null;
        $data->save();

        return back()->with('success', 'Tanda tangan berhasil dihapus.');
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

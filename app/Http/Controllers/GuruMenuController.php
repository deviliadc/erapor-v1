<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Siswa;
use App\Models\Mapel;
use App\Models\Bab;
use App\Models\LingkupMateri;
use App\Models\TujuanPembelajaran;
use App\Models\Ekstra;
use App\Models\ParamEkstra;
use App\Models\P5Dimensi;
use App\Models\P5Elemen;
use App\Models\P5SubElemen;
use App\Models\P5Proyek;
use App\Models\P5ProyekDetail;
use App\Models\PresensiHarian;
use App\Models\RekapAbsensi;
use App\Models\NilaiMapel;
use App\Models\NilaiEkstra;
use App\Models\NilaiP5;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuruMenuController extends Controller
{
    // Data Siswa yang diampu guru
    public function dataSiswa(Request $request)
    {
        $guru = Auth::user()->guru;
        // Ambil kelas yang diampu guru (sebagai wali atau pengajar)
        $kelasIds = $guru->kelasDiampuIds();
        $siswa = Siswa::whereHas('kelasSiswa', function($q) use ($kelasIds) {
            $q->whereIn('kelas_id', $kelasIds);
        })->get();

        return view('menu-guru.data-siswa', compact('siswa'));
    }

    // Data Mapel yang diampu guru
    public function dataMapel(Request $request)
    {
        $guru = Auth::user()->guru;
        $mapel = $guru->mapelDiampu()->get();
        return view('menu-guru.data-mapel', compact('mapel'));
    }

    public function dataBab(Request $request)
    {
        $guru = Auth::user()->guru;
        $bab = Bab::whereIn('mapel_id', $guru->mapelDiampu()->pluck('id'))->get();
        return view('menu-guru.data-bab', compact('bab'));
    }

    public function dataLingkup(Request $request)
    {
        $guru = Auth::user()->guru;
        $lingkup = LingkupMateri::whereIn('mapel_id', $guru->mapelDiampu()->pluck('id'))->get();
        return view('menu-guru.data-lingkup', compact('lingkup'));
    }

    public function dataTujuan(Request $request)
    {
        $guru = Auth::user()->guru;
        $tujuan = TujuanPembelajaran::whereIn('mapel_id', $guru->mapelDiampu()->pluck('id'))->get();
        return view('menu-guru.data-tujuan', compact('tujuan'));
    }

    public function dataEkstra(Request $request)
    {
        $guru = Auth::user()->guru;
        $ekstra = Ekstra::where('guru_id', $guru->id)->get();
        return view('menu-guru.data-ekstra', compact('ekstra'));
    }

    public function dataParamEkstra(Request $request)
    {
        $guru = Auth::user()->guru;
        $paramEkstra = ParamEkstra::where('guru_id', $guru->id)->get();
        return view('menu-guru.data-param-ekstra', compact('paramEkstra'));
    }

    public function dataP5Dimensi(Request $request)
    {
        $dimensi = P5Dimensi::all();
        return view('menu-guru.data-p5-dimensi', compact('dimensi'));
    }

    public function dataP5Elemen(Request $request)
    {
        $elemen = P5Elemen::all();
        return view('menu-guru.data-p5-elemen', compact('elemen'));
    }

    public function dataP5SubElemen(Request $request)
    {
        $subelemen = P5SubElemen::all();
        return view('menu-guru.data-p5-subelemen', compact('subelemen'));
    }

    public function dataP5Proyek(Request $request)
    {
        $guru = Auth::user()->guru;
        $proyek = P5Proyek::where('guru_id', $guru->id)->get();
        return view('menu-guru.data-p5-proyek', compact('proyek'));
    }

    public function dataP5ProyekDetail(Request $request)
    {
        $guru = Auth::user()->guru;
        $detail = P5ProyekDetail::whereHas('proyek', function($q) use ($guru) {
            $q->where('guru_id', $guru->id);
        })->get();
        return view('menu-guru.data-p5-proyek-detail', compact('detail'));
    }

    public function dataPresensi(Request $request)
    {
        $guru = Auth::user()->guru;
        $presensi = PresensiHarian::where('guru_id', $guru->id)->get();
        return view('menu-guru.data-presensi', compact('presensi'));
    }

    public function dataRekapAbsensi(Request $request)
    {
        $guru = Auth::user()->guru;
        $rekap = RekapAbsensi::where('guru_id', $guru->id)->get();
        return view('menu-guru.data-rekap-absensi', compact('rekap'));
    }

    public function dataNilaiMapel(Request $request)
    {
        $guru = Auth::user()->guru;
        $nilai = NilaiMapel::where('guru_id', $guru->id)->get();
        return view('menu-guru.data-nilai-mapel', compact('nilai'));
    }

    public function dataNilaiEkstra(Request $request)
    {
        $guru = Auth::user()->guru;
        $nilai = NilaiEkstra::where('guru_id', $guru->id)->get();
        return view('menu-guru.data-nilai-ekstra', compact('nilai'));
    }

    public function dataNilaiP5(Request $request)
    {
        $guru = Auth::user()->guru;
        $nilai = NilaiP5::where('guru_id', $guru->id)->get();
        return view('menu-guru.data-nilai-p5', compact('nilai'));
    }
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
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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

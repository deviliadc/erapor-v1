<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Siswa;
use App\Models\WaliMurid;
use App\Models\RekapAbsensi;
use App\Models\NilaiMapel;
use App\Models\NilaiEkstra;
use App\Models\NilaiP5;
use App\Models\KelasSiswa;
use App\Models\TahunAjaran;
use App\
use App\Models\Rapor;
use Illuminate\Http\Request;

class KepalaSekolahMenuController extends Controller
{
    public function dataGuru()
    {
        $data = Guru::all();
        return view('menu-kepsek.data-guru', compact('data'));
    }

    public function dataSiswa()
    {
        $data = Siswa::all();
        return view('menu-kepsek.data-siswa', compact('data'));
    }

    public function dataWaliMurid()
    {
        $data = WaliMurid::all();
        return view('menu-kepsek.data-wali-murid', compact('data'));
    }

    public function rekapAbsensi()
    {
        $data = RekapAbsensi::with('siswa')->get();
        return view('menu-kepsek.rekap-absensi', compact('data'));
    }

    public function rekapPresensi(Request $request)
{
    $tahunAjaranId = $request->input('tahun_ajaran_filter') ?? TahunAjaran::where('is_active', 1)->first()?->id;
    $kelasList = Kelas::orderBy('nama')->get();

    // Ambil jumlah siswa per kelas
    $kelasData = [];
    foreach ($kelasList as $kelas) {
        $jumlahSiswa = KelasSiswa::where('kelas_id', $kelas->id)
            ->where('tahun_ajaran_id', $tahunAjaranId)
            ->count();

        $waliKelas = $kelas->guruKelas->first()?->guru?->nama ?? '-';

        $kelasData[] = [
            'id' => $kelas->id,
            'kelas' => $kelas->nama,
            'wali_kelas' => $waliKelas,
            'jumlah_siswa' => $jumlahSiswa,
        ];
    }

    $tahunAjaranAktif = TahunAjaran::find($tahunAjaranId);

    return view('menu-kepsek.rekap-presensi', [
        'kelas' => $kelasData,
        'totalCount' => count($kelasData),
        'tahunAjaranAktif' => $tahunAjaranAktif,
        'tahunAjaranSelect' => TahunAjaran::orderByDesc('tahun')->get(),
        'breadcrumbs' => [['label' => 'Rekap Presensi']],
        'title' => 'Rekap Presensi Siswa',
        'tahunAjaranId' => $tahunAjaranId,
    ]);
}

public function rekapPresensiDetail(Request $request, $kelas_id)
{
    $tahunAjaranId = $request->input('tahun_ajaran_filter') ?? TahunAjaran::where('is_active', 1)->first()?->id;
    $kelas = Kelas::findOrFail($kelas_id);
    $waliKelas = $kelas->guruKelas->first()?->guru?->nama ?? '-';

    // Ambil semua siswa di kelas ini pada tahun ajaran
    $kelasSiswaList = KelasSiswa::with('siswa')
        ->where('kelas_id', $kelas_id)
        ->where('tahun_ajaran_id', $tahunAjaranId)
        ->get();

    $presensiData = [];
    foreach ($kelasSiswaList as $ks) {
        $presensi = PresensiDetail::where('siswa_id', $ks->siswa_id)
            ->where('tahun_ajaran_id', $tahunAjaranId)
            ->get();

        $presensiData[] = [
            'no_absen' => $ks->no_absen,
            'nama' => $ks->siswa->nama ?? '-',
            'hadir' => $presensi->sum('hadir'),
            'sakit' => $presensi->sum('sakit'),
            'izin' => $presensi->sum('izin'),
            'alfa' => $presensi->sum('alfa'),
        ];
    }

    return view('menu-kepsek.rekap-presensi-detail', [
        'presensiData' => $presensiData,
        'kelas' => $kelas,
        'waliKelas' => $waliKelas,
        'tahunAjaranAktif' => TahunAjaran::find($tahunAjaranId),
    ]);
}

    public function nilaiMapel()
    {
        $data = NilaiMapel::with('siswa', 'mapel')->get();
        return view('menu-kepsek.nilai-mapel', compact('data'));
    }

    public function nilaiEkstra()
    {
        $data = NilaiEkstra::with('siswa', 'ekstra')->get();
        return view('menu-kepsek.nilai-ekstra', compact('data'));
    }

    public function nilaiP5()
    {
        $data = NilaiP5::with('siswa')->get();
        return view('menu-kepsek.nilai-p5', compact('data'));
    }

    // public function rapor()
    // {
    //     $data = Rapor::with('siswa')->get();
    //     return view('menu-kepsek.rapor', compact('data'));
    // }

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

<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\GuruKelas;
use App\Models\Siswa;
use App\Models\WaliMurid;
use App\Models\RekapAbsensi;
use App\Models\NilaiMapel;
use App\Models\NilaiEkstra;
use App\Models\NilaiP5;
use App\Models\KelasSiswa;
use App\Models\TahunAjaran;
use App\Models\PresensiDetail;
use App\Models\Kelas;
use App\Models\Rapor;
use App\Models\TahunSemester;
use Illuminate\Http\Request;

class KepalaSekolahMenuController extends Controller
{
    // public function dataGuru()
    // {
    //     $data = Guru::all();
    //     return view('menu-kepsek.data-guru', compact('data'));
    // }

    // public function dataSiswa()
    // {
    //     $data = Siswa::all();
    //     return view('menu-kepsek.data-siswa', compact('data'));
    // }

    // public function dataWaliMurid()
    // {
    //     $data = WaliMurid::all();
    //     return view('menu-kepsek.data-wali-murid', compact('data'));
    // }

    // public function rekapAbsensi()
    // {
    //     $data = RekapAbsensi::with('siswa')->get();
    //     return view('menu-kepsek.rekap-absensi', compact('data'));
    // }

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
            'kelas' => collect($kelasData),
            'totalCount' => count($kelasData),
            'tahunAjaranAktif' => $tahunAjaranAktif,
            'tahunAjaranSelect' => TahunAjaran::orderByDesc('tahun')->get(),
            'breadcrumbs' => [['label' => 'Rekap Presensi']],
            'title' => 'Rekap Presensi Siswa',
            'tahunAjaranId' => $tahunAjaranId,
            'tahunSemesterSelect' => TahunSemester::with('tahunAjaran')->get()
                ->map(function ($ts) {
                    return [
                        'id' => $ts->id,
                        'name' => $ts->tahunAjaran->tahun . ' - ' . ucfirst($ts->semester),
                    ];
                }),
        ]);
    }

    public function rekapPresensiDetail(Request $request, $kelas_id)
    {
        $tahunSemesterId = $request->input('tahun_semester_filter')
            ?? TahunSemester::where('is_active', 1)->first()?->id;

        $tahunSemester = TahunSemester::with('tahunAjaran')->findOrFail($tahunSemesterId);
        $tahunAjaranId = $tahunSemester->tahun_ajaran_id;

        $kelas = Kelas::findOrFail($kelas_id);

        // Cari wali kelas sesuai tahun ajaran
        $waliKelas = GuruKelas::where('kelas_id', $kelas_id)
            ->where('tahun_ajaran_id', $tahunAjaranId)
            ->where('peran', 'wali')
            ->with('guru')
            ->first()?->guru?->nama ?? '-';

        // Ambil semua siswa di kelas ini pada tahun ajaran
        $kelasSiswaList = KelasSiswa::with('siswa')
            ->where('kelas_id', $kelas_id)
            ->where('tahun_ajaran_id', $tahunAjaranId)
            ->get();

        $presensiData = [];
        foreach ($kelasSiswaList as $ks) {
            $presensi = PresensiDetail::where('kelas_siswa_id', $ks->id)
                ->whereHas('presensiHarian', function ($q) use ($tahunSemesterId) {
                    $q->where('tahun_semester_id', $tahunSemesterId);
                })
                ->get();

            $presensiData[] = [
                'no_absen' => $ks->no_absen,
                'nama' => $ks->siswa->nama ?? '-',
                'hadir' => $presensi->where('status', 'Hadir')->count(),
                'sakit' => $presensi->where('status', 'Sakit')->count(),
                'izin'  => $presensi->where('status', 'Izin')->count(),
                'alfa'  => $presensi->where('status', 'Alpha')->count(),
            ];
        }

        return view('menu-kepsek.rekap-presensi-detail', [
            'presensiData' => $presensiData,
            'kelas' => $kelas,
            'waliKelas' => $waliKelas,
            'tahunSemesterAktif' => $tahunSemester,
            'tahunSemesterSelect' => TahunSemester::with('tahunAjaran')->get()
                ->map(function ($ts) {
                    return [
                        'id' => $ts->id,
                        'name' => $ts->tahunAjaran->tahun . ' - ' . ucfirst($ts->semester),
                    ];
                }),
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

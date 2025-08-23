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
        $tahunSemesterId = $request->input('tahun_semester_filter')
            ?? TahunSemester::where('is_active', 1)->first()?->id;

        $tahunSemesterAktif = TahunSemester::with('tahunAjaran')->find($tahunSemesterId);
        $tahunAjaranId = $tahunSemesterAktif?->tahun_ajaran_id;

        $kelasList = Kelas::orderBy('nama')->get();

        // Ambil jumlah siswa per kelas
        $kelasData = [];
        foreach ($kelasList as $kelas) {
            $jumlahSiswa = KelasSiswa::where('kelas_id', $kelas->id)
                ->where('tahun_ajaran_id', $tahunAjaranId)
                ->count();

            $waliKelas = GuruKelas::where('kelas_id', $kelas->id)
                ->where('tahun_ajaran_id', $tahunAjaranId)
                ->where('peran', 'wali')
                ->with('guru')
                ->first()?->guru?->nama ?? '-';

            $kelasData[] = [
                'id' => $kelas->id,
                'kelas' => $kelas->nama,
                'wali_kelas' => $waliKelas,
                'jumlah_siswa' => $jumlahSiswa,
            ];
        }

        return view('menu-kepsek.rekap-presensi', [
    'kelas' => collect($kelasData),
    'totalCount' => count($kelasData),
    'tahunSemesterAktif' => $tahunSemesterAktif,
    'tahunSemesterSelect' => TahunSemester::with('tahunAjaran')
        ->join('tahun_ajaran', 'tahun_ajaran.id', '=', 'tahun_semester.tahun_ajaran_id')
        ->orderBy('tahun_ajaran.tahun', 'desc')
        ->orderByRaw("FIELD(semester, 'ganjil', 'genap')") // ganjil dulu baru genap
        ->select('tahun_semester.*')
        ->get()
        ->map(fn ($ts) => [
            'id' => $ts->id,
            'name' => $ts->tahunAjaran->tahun . ' - ' . ucfirst($ts->semester) . ($ts->is_active ? ' (Aktif)' : ''),
        ]),
    'breadcrumbs' => [['label' => 'Rekap Presensi']],
    'title' => 'Rekap Presensi Siswa',
    'tahunSemesterId' => $tahunSemesterId,
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
            'presensiData' => collect($presensiData),
            'totalCount' => count($presensiData),
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

    public function nilaiEkstra(Request $request)
{
    $tahunSemesterId = $request->input('tahun_semester_filter')
        ?? TahunSemester::where('is_active', 1)->first()?->id;

    $tahunSemesterAktif = TahunSemester::with('tahunAjaran')->find($tahunSemesterId);
    $tahunAjaranId = $tahunSemesterAktif?->tahun_ajaran_id;

    $kelasList = Kelas::orderBy('nama')->get();
    $kelasData = [];

    foreach ($kelasList as $kelas) {
        $jumlahSiswa = KelasSiswa::where('kelas_id', $kelas->id)
            ->where('tahun_ajaran_id', $tahunAjaranId)
            ->count();

        $waliKelas = GuruKelas::where('kelas_id', $kelas->id)
            ->where('tahun_ajaran_id', $tahunAjaranId)
            ->where('peran', 'wali')
            ->with('guru')
            ->first()?->guru?->nama ?? '-';

        $kelasData[] = [
            'id' => $kelas->id,
            'kelas' => $kelas->nama,
            'wali_kelas' => $waliKelas,
            'jumlah_siswa' => $jumlahSiswa,
        ];
    }

    return view('menu-kepsek.rekap-nilai-ekstra', [
        'kelas' => collect($kelasData),
        'totalCount' => count($kelasData),
        'tahunSemesterAktif' => $tahunSemesterAktif,
        'tahunSemesterSelect' => TahunSemester::with('tahunAjaran')
            ->join('tahun_ajaran', 'tahun_ajaran.id', '=', 'tahun_semester.tahun_ajaran_id')
            ->orderBy('tahun_ajaran.tahun', 'desc')
            ->orderByRaw("FIELD(semester, 'ganjil', 'genap')")
            ->select('tahun_semester.*')
            ->get()
            ->map(fn ($ts) => [
                'id' => $ts->id,
                'name' => $ts->tahunAjaran->tahun . ' - ' . ucfirst($ts->semester) . ($ts->is_active ? ' (Aktif)' : ''),
            ]),
        'breadcrumbs' => [['label' => 'Rekap Nilai Ekstrakurikuler']],
        'title' => 'Rekap Nilai Ekstrakurikuler',
        'tahunSemesterId' => $tahunSemesterId,
    ]);
}

public function nilaiEkstraDetail(Request $request, $kelas_id)
{
    $tahunSemesterId = $request->input('tahun_semester_filter')
        ?? TahunSemester::where('is_active', 1)->first()?->id;

    $tahunSemester = TahunSemester::with('tahunAjaran')->findOrFail($tahunSemesterId);
    $tahunAjaranId = $tahunSemester->tahun_ajaran_id;

    $kelas = Kelas::findOrFail($kelas_id);

    $waliKelas = GuruKelas::where('kelas_id', $kelas_id)
        ->where('tahun_ajaran_id', $tahunAjaranId)
        ->where('peran', 'wali')
        ->with('guru')
        ->first()?->guru?->nama ?? '-';

    $kelasSiswaList = KelasSiswa::with('siswa')
        ->where('kelas_id', $kelas_id)
        ->where('tahun_ajaran_id', $tahunAjaranId)
        ->get();

    $nilaiEkstra = [];
    foreach ($kelasSiswaList as $ks) {
        $nilai = \App\Models\NilaiEkstra::where('kelas_siswa_id', $ks->id)
            ->where('tahun_semester_id', $tahunSemesterId)
            ->with('ekstra')
            ->get();

        $nilaiEkstra[] = [
            'id' => $ks->id,
            'no_absen' => $ks->no_absen,
            'nama' => $ks->siswa->nama ?? '-',
            'nis' => $n->nilai_akhir ?? '-',
            
            'nilai' => $nilai->map(fn ($n) => [
                'ekstra' => $n->ekstra->nama ?? '-',
                'nilai_akhir' => $n->nilai_akhir ?? '-',
                'periode' => ucfirst($n->periode),
                'deskripsi' => $n->deskripsi ?? '-',
            ]),
        ];
    }

    return view('menu-kepsek.rekap-nilai-ekstra-detail', [
        'nilaiEkstra' => collect($nilaiEkstra),
        'totalCount' => count($nilaiEkstra),
        'kelas' => $kelas,
        'waliKelas' => $waliKelas,
        'tahunSemesterAktif' => $tahunSemester,
        'tahunSemesterSelect' => TahunSemester::with('tahunAjaran')
            ->join('tahun_ajaran', 'tahun_ajaran.id', '=', 'tahun_semester.tahun_ajaran_id')
            ->orderBy('tahun_ajaran.tahun', 'desc')
            ->orderByRaw("FIELD(semester, 'ganjil', 'genap')")
            ->select('tahun_semester.*')
            ->get()
            ->map(fn ($ts) => [
                'id' => $ts->id,
                'name' => $ts->tahunAjaran->tahun . ' - ' . ucfirst($ts->semester) . ($ts->is_active ? ' (Aktif)' : ''),
            ]),
    ]);
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

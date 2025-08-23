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
use App\Models\PresensiHarian;
use App\Models\Mapel;
use App\Models\Ekstra;
use App\Models\P5Dimensi;
use App\Models\Rapor;
use App\Models\TahunSemester;
use Illuminate\Http\Request;

class KepalaSekolahMenuController extends Controller
{
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
                ->map(fn($ts) => [
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
            ->paginate(10);

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

        $presensiData = collect($presensiData);

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
            'breadcrumbs' => [
                ['label' => 'Rekap Presensi', 'url' => route('kepsek.rekap-presensi')],
                ['label' => 'Detail Presensi'],
        ]);
    }

    public function nilaiMapel(Request $request)
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

        return view('menu-kepsek.rekap-nilai-mapel', [
            'kelas' => collect($kelasData),
            'totalCount' => count($kelasData),
            'tahunSemesterAktif' => $tahunSemesterAktif,
            'tahunSemesterSelect' => TahunSemester::with('tahunAjaran')
                ->join('tahun_ajaran', 'tahun_ajaran.id', '=', 'tahun_semester.tahun_ajaran_id')
                ->orderBy('tahun_ajaran.tahun', 'desc')
                ->orderByRaw("FIELD(semester, 'ganjil', 'genap')")
                ->select('tahun_semester.*')
                ->get()
                ->map(fn($ts) => [
                    'id' => $ts->id,
                    'name' => $ts->tahunAjaran->tahun . ' - ' . ucfirst($ts->semester) . ($ts->is_active ? ' (Aktif)' : ''),
                ]),
            'breadcrumbs' => [['label' => 'Rekap Nilai Mata Pelajaran']],
            'title' => 'Rekap Nilai Mata Pelajaran',
            'tahunSemesterId' => $tahunSemesterId,
        ]);
    }

    public function nilaiMapelDetail(Request $request, $kelas_id)
    {
        $tahunSemesterId = $request->input('tahun_semester_filter')
            ?? TahunSemester::where('is_active', 1)->first()?->id;
        $mapelId = $request->input('mapel_filter');

        $tahunSemester = TahunSemester::with('tahunAjaran')->findOrFail($tahunSemesterId);
        $tahunAjaranId = $tahunSemester->tahun_ajaran_id;

        $kelas = Kelas::findOrFail($kelas_id);

        $waliKelas = GuruKelas::where('kelas_id', $kelas_id)
            ->where('tahun_ajaran_id', $tahunAjaranId)
            ->where('peran', 'wali')
            ->with('guru')
            ->first()?->guru?->nama ?? '-';

            // Ambil semua mapel yang diajarkan di kelas ini pada tahun ajaran
            $mapelRaw = GuruKelas::with('mapel')
                ->where('kelas_id', $kelas_id)
                ->where('tahun_ajaran_id', $tahunAjaranId)
                ->get()
                ->pluck('mapel')
                ->unique('id')
                ->filter(); // pastikan tidak null

            $mapelOptions = $mapelRaw->map(fn($m) => [
                'id' => $m->id,
                'name' => $m->nama,
            ])->values();

            // Jika tidak ada mapel, $mapelAktif = null
            if ($mapelOptions->isEmpty()) {
                $mapelAktif = null;
                $mapelFilterEnabled = false;
            } else {
                $mapelAktif = $mapelId
                    ? \App\Models\Mapel::find($mapelId)
                    : \App\Models\Mapel::find($mapelOptions->first()['id']);
                $mapelFilterEnabled = true;
            }

            $kelasSiswaList = KelasSiswa::with('siswa')
                ->where('kelas_id', $kelas_id)
                ->where('tahun_ajaran_id', $tahunAjaranId)
                ->get();

            $nilaiMapel = [];
            foreach ($kelasSiswaList as $ks) {
                $nilai = $mapelAktif
                    ? \App\Models\NilaiMapel::where('kelas_siswa_id', $ks->id)
                        ->where('tahun_semester_id', $tahunSemesterId)
                        ->where('mapel_id', $mapelAktif->id)
                        ->first()
                    : null;

                $nilaiMapel[] = [
                    'id' => $ks->id,
                    'no_absen' => $ks->no_absen,
                    'nama' => $ks->siswa->nama ?? '-',
                    'uts' => $nilai?->uts ?? '-',
                    'uas' => $nilai?->uas ?? '-',
                ];
            }

            return view('menu-kepsek.rekap-nilai-mapel-detail', [
                'nilaiMapel' => collect($nilaiMapel),
                'totalCount' => count($nilaiMapel),
                'kelas' => $kelas,
                'waliKelas' => $waliKelas,
                'tahunSemesterAktif' => $tahunSemester,
                'tahunSemesterSelect' => TahunSemester::with('tahunAjaran')
                    ->join('tahun_ajaran', 'tahun_ajaran.id', '=', 'tahun_semester.tahun_ajaran_id')
                    ->orderBy('tahun_ajaran.tahun', 'desc')
                    ->orderByRaw("FIELD(semester, 'ganjil', 'genap')")
                    ->select('tahun_semester.*')
                    ->get()
                    ->map(fn($ts) => [
                        'id' => $ts->id,
                        'name' => $ts->tahunAjaran->tahun . ' - ' . ucfirst($ts->semester) . ($ts->is_active ? ' (Aktif)' : ''),
                    ]),
                'mapelOptions' => $mapelOptions,
                'mapelAktif' => $mapelAktif,
                'mapelFilterEnabled' => $mapelFilterEnabled,
                'breadcrumbs' => [
                    ['label' => 'Rekap Nilai Mata Pelajaran',  'url' => route('kepala-sekolah.nilai-mapel.index')],
                    ['label' => 'Detail Nilai Mata Pelajaran']
                ],
                'title' => 'Rekap Nilai Mata Pelajaran',
            ]);
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
                ->map(fn($ts) => [
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
        $ekstraId = $request->input('ekstra_filter');

        $tahunSemester = TahunSemester::with('tahunAjaran')->findOrFail($tahunSemesterId);
        $tahunAjaranId = $tahunSemester->tahun_ajaran_id;

        $kelas = Kelas::findOrFail($kelas_id);

        $waliKelas = GuruKelas::where('kelas_id', $kelas_id)
            ->where('tahun_ajaran_id', $tahunAjaranId)
            ->where('peran', 'wali')
            ->with('guru')
            ->first()?->guru?->nama ?? '-';

        // Ambil semua ekstra yang tersedia di tahun semester ini

        $ekstraOptions = \App\Models\Ekstra::orderBy('nama')->get()
            ->map(fn($e) => [
                'id' => $e->id,
                'name' => $e->nama,
            ]);
        $ekstraAktif = $ekstraId ? \App\Models\Ekstra::find($ekstraId) : \App\Models\Ekstra::orderBy('nama')->first();
        $kelasSiswaList = KelasSiswa::with('siswa')
            ->where('kelas_id', $kelas_id)
            ->where('tahun_ajaran_id', $tahunAjaranId)
            ->get();

        $nilaiEkstra = [];
        foreach ($kelasSiswaList as $ks) {
            $nilai = \App\Models\NilaiEkstra::where('kelas_siswa_id', $ks->id)
                ->where('tahun_semester_id', $tahunSemesterId)
                ->when($ekstraId, fn($q) => $q->where('ekstra_id', $ekstraId))
                ->with('ekstra')
                ->first();

            $nilaiEkstra[] = [
                'id' => $ks->id,
                'no_absen' => $ks->no_absen,
                'nama' => $ks->siswa->nama ?? '-',
                'nilai' => $nilai?->nilai_akhir ?? '-',
                'deskripsi' => $nilai?->deskripsi ?? '-',
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
                ->map(fn($ts) => [
                    'id' => $ts->id,
                    'name' => $ts->tahunAjaran->tahun . ' - ' . ucfirst($ts->semester) . ($ts->is_active ? ' (Aktif)' : ''),
                ]),
            'ekstraOptions' => $ekstraOptions,
            'ekstraAktif' => $ekstraAktif,
            'breadcrumbs' => [
                ['label' => 'Rekap Nilai Ekstrakurikuler',  'url' => route('kepala-sekolah.nilai-ekstra.index')],
                ['label' => 'Detail Nilai Ekstrakurikuler']
            ],
            'title' => 'Rekap Nilai Ekstrakurikuler',
        ]);
    }


    public function nilaiP5(Request $request)
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

        return view('menu-kepsek.rekap-nilai-p5', [
            'kelas' => collect($kelasData),
            'totalCount' => count($kelasData),
            'tahunSemesterAktif' => $tahunSemesterAktif,
            'tahunSemesterSelect' => TahunSemester::with('tahunAjaran')
                ->join('tahun_ajaran', 'tahun_ajaran.id', '=', 'tahun_semester.tahun_ajaran_id')
                ->orderBy('tahun_ajaran.tahun', 'desc')
                ->orderByRaw("FIELD(semester, 'ganjil', 'genap')")
                ->select('tahun_semester.*')
                ->get()
                ->map(fn($ts) => [
                    'id' => $ts->id,
                    'name' => $ts->tahunAjaran->tahun . ' - ' . ucfirst($ts->semester) . ($ts->is_active ? ' (Aktif)' : ''),
                ]),
            'breadcrumbs' => [['label' => 'Rekap Nilai P5']],
            'title' => 'Rekap Nilai P5',
            'tahunSemesterId' => $tahunSemesterId,
        ]);
    }

    public function nilaiP5Detail(Request $request, $kelas_id)
    {
        $tahunSemesterId = $request->input('tahun_semester_filter')
            ?? TahunSemester::where('is_active', 1)->first()?->id;
        $proyekId = $request->input('proyek_filter');

        $tahunSemester = TahunSemester::with('tahunAjaran')->findOrFail($tahunSemesterId);
        $tahunAjaranId = $tahunSemester->tahun_ajaran_id;

        $kelas = Kelas::findOrFail($kelas_id);

        $waliKelas = GuruKelas::where('kelas_id', $kelas_id)
            ->where('tahun_ajaran_id', $tahunAjaranId)
            ->where('peran', 'wali')
            ->with('guru')
            ->first()?->guru?->nama ?? '-';

        // Ambil semua proyek P5 di semester ini
        $proyekOptions = \App\Models\P5Proyek::where('tahun_semester_id', $tahunSemesterId)
            ->orderBy('nama_proyek')
            ->get()
            ->map(fn($p) => [
                'id' => $p->id,
                'name' => $p->nama_proyek,
            ]);
        $proyekAktif = $proyekId
            ? \App\Models\P5Proyek::find($proyekId)
            : (\App\Models\P5Proyek::where('tahun_semester_id', $tahunSemesterId)->orderBy('nama_proyek')->first());

        // Ambil semua dimensi dari proyek aktif
        $dimensiList = $proyekAktif
            ? $proyekAktif->p5Dimensi()->orderBy('nama_dimensi')->get()
            : collect();

        // Ambil semua siswa di kelas ini pada tahun ajaran
        $kelasSiswaList = KelasSiswa::with('siswa')
            ->where('kelas_id', $kelas_id)
            ->where('tahun_ajaran_id', $tahunAjaranId)
            ->get();

        $result = [];
        foreach ($kelasSiswaList as $ks) {
            $nilaiSiswa = [];
            // Ambil satu nilai_p5 (proyek) untuk siswa ini di semester ini dan proyek aktif
            $nilaiP5 = $proyekAktif
                ? \App\Models\NilaiP5::where('kelas_siswa_id', $ks->id)
                    ->where('tahun_semester_id', $tahunSemesterId)
                    ->where('p5_proyek_id', $proyekAktif->id)
                    ->first()
                : null;

            foreach ($dimensiList as $dimensi) {
                $nilaiDetail = null;
                if ($nilaiP5) {
                    $nilaiDetail = \App\Models\NilaiP5Detail::where('nilai_p5_id', $nilaiP5->id)
                        ->where('p5_dimensi_id', $dimensi->id)
                        ->first();
                }
                $nilaiSiswa[$dimensi->nama_dimensi] = $nilaiDetail?->predikat ?? '-';
            }

            $result[] = array_merge([
                'id' => $ks->id,
                'nama' => $ks->siswa->nama ?? '-',
            ], $nilaiSiswa);
        }

        return view('menu-kepsek.rekap-nilai-p5-detail', [
            'nilaiP5' => collect($result),
            'totalCount' => count($result),
            'kelas' => $kelas,
            'waliKelas' => $waliKelas,
            'tahunSemesterAktif' => $tahunSemester,
            'tahunSemesterSelect' => TahunSemester::with('tahunAjaran')
                ->join('tahun_ajaran', 'tahun_ajaran.id', '=', 'tahun_semester.tahun_ajaran_id')
                ->orderBy('tahun_ajaran.tahun', 'desc')
                ->orderByRaw("FIELD(semester, 'ganjil', 'genap')")
                ->select('tahun_semester.*')
                ->get()
                ->map(fn($ts) => [
                    'id' => $ts->id,
                    'name' => $ts->tahunAjaran->tahun . ' - ' . ucfirst($ts->semester) . ($ts->is_active ? ' (Aktif)' : ''),
                ]),
            'proyekOptions' => $proyekOptions,
            'proyekAktif' => $proyekAktif,
            'dimensiList' => $dimensiList,
            'breadcrumbs' => [
                ['label' => 'Rekap Nilai P5',  'url' => route('kepala-sekolah.nilai-p5.index')],
                ['label' => 'Detail Nilai P5']
            ],
            'title' => 'Rekap Nilai P5',
        ]);
    }

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

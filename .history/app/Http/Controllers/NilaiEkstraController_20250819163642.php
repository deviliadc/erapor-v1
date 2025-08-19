<?php

namespace App\Http\Controllers;

use App\Models\Ekstra;
use App\Models\Kelas;
use App\Models\KelasSiswa;
use App\Models\NilaiEkstra;
use App\Models\NilaiEkstraDetail;
use App\Models\ParamEkstra;
use App\Models\TahunSemester;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NilaiEkstraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index(Request $request)
    // {
    //     $user = Auth::user();
    //     $isAdmin = $user->hasRole('admin');
    //     $isWali = false;

    //     if ($isAdmin) {
    //         // Admin: semua tahun semester & semua kelas
    //         // $tahunSemesterList = TahunSemester::orderByDesc('tahun')->orderByDesc('semester')->get();
    //         $tahunSemesterList = \App\Models\TahunSemester::join('tahun_ajaran', 'tahun_semester.tahun_ajaran_id', '=', 'tahun_ajaran.id')
    //             ->orderByDesc('tahun_ajaran.tahun')
    //             ->orderByDesc('tahun_semester.semester')
    //             ->select('tahun_semester.*')
    //             ->get();
    //         $kelasList = Kelas::all();
    //     } elseif ($user->hasRole('guru')) {
    //         $guru = $user->guru;
    //         // Tahun semester di mana guru ini pernah jadi wali
    //         $tahunIds = \App\Models\GuruKelas::where('guru_id', $guru->id)
    //             ->where('peran', 'wali')
    //             ->pluck('tahun_semester_id')
    //             ->unique()
    //             ->values();
    //         $tahunSemesterList = TahunSemester::whereIn('id', $tahunIds)
    //             ->orderByDesc('tahun')
    //             ->orderByDesc('semester')
    //             ->get();
    //         $isWali = $tahunIds->isNotEmpty();
    //     } else {
    //         $tahunSemesterList = collect();
    //         $kelasList = collect();
    //     }

    //     // Default tahun semester: aktif, atau tahun wali pertama
    //     $tahunAktif = $tahunSemesterList->firstWhere('is_active', true) ?? $tahunSemesterList->first();
    //     $tahunSemesterId = $request->tahun_semester_id ?? $tahunAktif?->id;

    //     // Kelas: admin semua kelas, wali hanya kelas yang diampu pada tahun semester terpilih
    //     if ($isAdmin) {
    //         $kelasList = Kelas::all();
    //     } elseif ($user->hasRole('guru')) {
    //         $guru = $user->guru;
    //         $kelasIds = \App\Models\GuruKelas::where('guru_id', $guru->id)
    //             ->where('tahun_semester_id', $tahunSemesterId)
    //             ->where('peran', 'wali')
    //             ->pluck('kelas_id')
    //             ->unique()
    //             ->values();
    //         $kelasList = Kelas::whereIn('id', $kelasIds)->get();
    //     }

    //     $kelasId = $request->kelas_id ?? $kelasList->first()?->id;
    //     $periode = 'akhir';

    //     $daftarEkstra = Ekstra::all();
    //     $ekstraId = $request->ekstra_id ?? $daftarEkstra->first()?->id;

    //     $selectedTahunSemester = TahunSemester::find($tahunSemesterId);

    //     // Siswa per kelas
    //     $siswaKelas = KelasSiswa::with('siswa')
    //         ->where('kelas_id', $kelasId)
    //         ->where('tahun_semester_id', $tahunSemesterId)
    //         ->orderBy('no_absen')
    //         ->get();

    //     // Siapkan parameter dan nilai per ekstra
    //     $daftarParameter = [];
    //     $nilaiMap = [];
    //     foreach ($daftarEkstra as $ekstra) {
    //         $daftarParameter[$ekstra->id] = ParamEkstra::where('ekstra_id', $ekstra->id)->get();

    //         foreach ($siswaKelas as $ks) {
    //             $nilaiEkstra = NilaiEkstra::where('ekstra_id', $ekstra->id)
    //                 ->where('kelas_siswa_id', $ks->id)
    //                 ->where('periode', 'akhir')
    //                 ->first();

    //             if ($nilaiEkstra) {
    //                 $detail = NilaiEkstraDetail::where('nilai_ekstra_id', $nilaiEkstra->id)
    //                     ->where('periode', 'akhir')
    //                     ->pluck('nilai', 'param_ekstra_id')
    //                     ->toArray();

    //                 $nilaiMap[$ekstra->id][$ks->id] = [
    //                     'predikat_param' => $detail,
    //                     'deskripsi' => $nilaiEkstra->deskripsi ?? null,
    //                 ];
    //             } else {
    //                 $nilaiMap[$ekstra->id][$ks->id] = [
    //                     'predikat_param' => [],
    //                     'deskripsi' => null,
    //                 ];
    //             }
    //         }
    //     }

    //     $breadcrumbs = [
    //         ['label' => 'Nilai Ekstrakurikuler'],
    //     ];
    //     $title = 'Nilai Ekstrakurikuler';

    //     return view('nilai-ekstra.index', compact(
    //         'tahunSemesterList',
    //         'tahunAktif',
    //         'kelasList',
    //         'daftarEkstra',
    //         'tahunSemesterId',
    //         'kelasId',
    //         'ekstraId',
    //         'daftarParameter',
    //         'periode',
    //         'selectedTahunSemester',
    //         'nilaiMap',
    //         'siswaKelas',
    //         'breadcrumbs',
    //         'title'
    //     ));
    // }

    public function index(Request $request)
    {
        $user = Auth::user();
        $isAdmin = $user->hasRole('admin');
        $isWali = false;

        // Ambil daftar tahun semester, urut berdasarkan tahun ajaran dan semester
        if ($isAdmin) {
            $tahunSemesterList = \App\Models\TahunSemester::with('tahunAjaran')
                ->get()
                ->sortByDesc(fn($ts) => $ts->tahunAjaran->tahun)
                ->sortByDesc('semester')
                ->values();
            $kelasList = Kelas::all();
        } elseif ($user->hasRole('guru')) {
            $guru = $user->guru;
            $tahunIds = \App\Models\GuruKelas::where('guru_id', $guru->id)
                ->where('peran', 'wali')
                ->pluck('tahun_semester_id')
                ->unique()
                ->values();
            $tahunSemesterList = TahunSemester::with('tahunAjaran')
                ->whereIn('id', $tahunIds)
                ->get()
                ->sortByDesc(fn($ts) => $ts->tahunAjaran->tahun)
                ->sortByDesc('semester')
                ->values();
            $isWali = $tahunIds->isNotEmpty();
        } else {
            $tahunSemesterList = collect();
            $kelasList = collect();
        }

        // Default tahun semester: aktif, atau tahun wali pertama
        $tahunAktif = $tahunSemesterList->firstWhere('is_active', true) ?? $tahunSemesterList->first();
        $tahunSemesterId = $request->tahun_semester_id ?? $tahunAktif?->id;
        $selectedTahunSemester = TahunSemester::with('tahunAjaran')->find($tahunSemesterId);

        // Kelas: admin semua kelas, wali hanya kelas yang diampu pada tahun semester terpilih
        if ($isAdmin) {
            $kelasList = Kelas::all();
        } elseif ($user->hasRole('guru')) {
            $guru = $user->guru;
            $kelasIds = \App\Models\GuruKelas::where('guru_id', $guru->id)
                ->where('tahun_semester_id', $tahunSemesterId)
                ->where('peran', 'wali')
                ->pluck('kelas_id')
                ->unique()
                ->values();
            $kelasList = Kelas::whereIn('id', $kelasIds)->get();
        }

        $kelasId = $request->kelas_id ?? $kelasList->first()?->id;
        $periode = 'akhir';

        $daftarEkstra = Ekstra::all();
        $ekstraId = $request->ekstra_id ?? $daftarEkstra->first()?->id;

        $daftarTahunAjaran = \App\Models\TahunAjaran::orderByDesc('tahun')->get();
        // Siswa per kelas di tahun ajaran (bukan tahun semester)
        $tahunAjaranId = $selectedTahunSemester?->tahun_ajaran_id;
        $siswaKelas = KelasSiswa::with('siswa')
            ->where('kelas_id', $kelasId)
            ->where('tahun_ajaran_id', $tahunAjaranId)
            ->orderBy('no_absen')
            ->get();

        // Siapkan parameter dan nilai per ekstra
        $daftarParameter = [];
        $nilaiMap = [];
        foreach ($daftarEkstra as $ekstra) {
            $daftarParameter[$ekstra->id] = ParamEkstra::where('ekstra_id', $ekstra->id)->get();

            foreach ($siswaKelas as $ks) {
                $nilaiEkstra = NilaiEkstra::where('ekstra_id', $ekstra->id)
                    ->where('kelas_siswa_id', $ks->id)
                    ->where('tahun_semester_id', $tahunSemesterId)
                    ->where('periode', 'akhir')
                    ->first();

                if ($nilaiEkstra) {
                    $detail = NilaiEkstraDetail::where('nilai_ekstra_id', $nilaiEkstra->id)
                        ->where('periode', 'akhir')
                        ->pluck('nilai', 'param_ekstra_id')
                        ->toArray();

                    $nilaiMap[$ekstra->id][$ks->id] = [
                        'predikat_param' => $detail,
                        'deskripsi' => $nilaiEkstra->deskripsi ?? null,
                    ];
                } else {
                    $nilaiMap[$ekstra->id][$ks->id] = [
                        'predikat_param' => [],
                        'deskripsi' => null,
                    ];
                }
            }
        }

        $breadcrumbs = [
            ['label' => 'Nilai Ekstrakurikuler'],
        ];
        $title = 'Nilai Ekstrakurikuler';

        return view('nilai-ekstra.index', compact(
            'tahunSemesterList',
            'tahunAktif',
            'kelasList',
            'daftarEkstra',
            'tahunSemesterId',
            'kelasId',
            'ekstraId',
            'daftarParameter',
            'periode',
            'selectedTahunSemester',
            'nilaiMap',
            'siswaKelas',
            'breadcrumbs',
            'title'
        ));
    }

    public function updateBatch(Request $request)
    {
        $user = Auth::user();
        $isAdmin = $user->hasRole('admin');
        $isWali = false;

        if ($user->hasRole('guru')) {
            $guru = $user->guru;
            $isWali = \App\Models\GuruKelas::where('guru_id', $guru->id)
                ->where('kelas_id', $request->kelas_id)
                // ->where('tahun_semester_id', $request->tahun_semester_id)
                ->where('tahun_ajaran_id', $request->tahun_ajaran_id)
                ->where('peran', 'wali')
                ->exists();
        }

        if (!$isAdmin && !$isWali) {
            return abort(403, 'Hanya admin atau wali kelas yang dapat mengisi nilai ekstrakurikuler.');
        }

        $request->validate([
            'tahun_semester_id' => 'required|exists:tahun_semester,id',
            'periode' => 'required|in:akhir',
            'nilai' => 'required|array',
            'nilai.*.kelas_siswa_id' => 'required|exists:kelas_siswa,id',
            'nilai.*.ekstra_id' => 'required|exists:ekstra,id',
            'nilai.*.predikat' => 'required|array',
        ]);

        foreach ($request->nilai as $item) {
            $kelasSiswa = KelasSiswa::find($item['kelas_siswa_id']);
            $siswaId = $kelasSiswa ? $kelasSiswa->siswa_id : null;
            $predikatParam = $item['predikat'] ?? [];

            // Filter hanya nilai yang diisi (numeric dan tidak kosong)
            $predikatParamFiltered = [];
            foreach ($predikatParam as $paramId => $nilai) {
                if ($nilai !== null && $nilai !== '' && is_numeric($nilai)) {
                    $predikatParamFiltered[$paramId] = $nilai;
                }
            }

            // Jika semua nilai kosong/-, hapus data jika ada
            if (empty($predikatParamFiltered)) {
                $nilaiEkstra = NilaiEkstra::where([
                    'kelas_siswa_id' => $item['kelas_siswa_id'],
                    'ekstra_id' => $item['ekstra_id'],
                    'periode' => $request->periode,
                ])->first();

                if ($nilaiEkstra) {
                    NilaiEkstraDetail::where('nilai_ekstra_id', $nilaiEkstra->id)
                        ->where('periode', $request->periode)
                        ->delete();
                    $nilaiEkstra->delete();
                }
                continue;
            }

            $avg = collect($predikatParamFiltered)->avg();
            $nilaiAkhir = $avg !== null ? ceil($avg) : null;

            // Deskripsi hanya dibuat jika nilai akhir ada
            $deskripsi = null;
            if ($nilaiAkhir !== null) {
                $paramIds = array_keys($predikatParam);
                $params = ParamEkstra::whereIn('id', $paramIds)->pluck('parameter', 'id');

                // $max = collect($predikatParam)->max();
                // $min = collect($predikatParam)->min();

                // $tertinggi = collect($predikatParam)->filter(fn($v) => $v == $max)->keys()->first();
                // $terendah = collect($predikatParam)->filter(fn($v) => $v == $min)->keys()->first();

                $max = collect($predikatParam)->max();
                $min = collect($predikatParam)->min();

                $paramKeys = array_keys($predikatParam);

                if ($max === $min && count($paramKeys) > 1) {
                    // Semua nilai sama, pilih dua parameter berbeda secara acak
                    shuffle($paramKeys);
                    $tertinggi = $paramKeys[0];
                    $terendah = $paramKeys[1];
                } else {
                    $tertinggi = collect($predikatParam)->filter(fn($v) => $v == $max)->keys()->first();
                    $terendah = collect($predikatParam)->filter(fn($v) => $v == $min)->keys()->first();
                }

                $namaTertinggi = $params[$tertinggi] ?? '';
                $namaTerendah = $params[$terendah] ?? '';

                $text = [
                    0 => 'masih perlu bimbingan dalam',
                    1 => 'masih perlu bimbingan dalam',
                    2 => 'cukup mahir dalam',
                    3 => 'mahir dalam',
                    4 => 'sangat mahir dalam',
                ];

                $namaSiswa = $kelasSiswa && $kelasSiswa->siswa ? $kelasSiswa->siswa->nama : '';
                $deskripsi = "Ananda " . $namaSiswa . " " .
                    ($text[$max] ?? '') . " " . $namaTertinggi .
                    " dan " .
                    ($text[$min] ?? '') . " " . $namaTerendah . ".";
            }

            $nilaiEkstra = NilaiEkstra::updateOrCreate(
                [
                    'kelas_siswa_id' => $item['kelas_siswa_id'],
                    'ekstra_id' => $item['ekstra_id'],
                    'periode' => $request->periode,
                ],
                [
                    'siswa_id' => $siswaId,
                    'param_nilai' => [],
                    'predikat_param' => $predikatParamFiltered,
                    'nilai_akhir' => $nilaiAkhir,
                    'deskripsi' => $deskripsi,
                ]
            );

            foreach ($predikatParamFiltered as $paramId => $nilai) {
                NilaiEkstraDetail::updateOrCreate(
                    [
                        'nilai_ekstra_id' => $nilaiEkstra->id,
                        'param_ekstra_id' => $paramId,
                        'periode' => $request->periode,
                    ],
                    [
                        'nilai' => $nilai,
                    ]
                );
            }

            // Hapus detail yang tidak diisi
            $paramIds = array_keys($predikatParam);
            $toDelete = array_diff($paramIds, array_keys($predikatParamFiltered));
            if (!empty($toDelete)) {
                NilaiEkstraDetail::where('nilai_ekstra_id', $nilaiEkstra->id)
                    ->whereIn('param_ekstra_id', $toDelete)
                    ->where('periode', $request->periode)
                    ->delete();
            }
        }

        return redirect()->to(role_route('nilai-ekstra.index', [
            'tahun_semester_id' => $request->tahun_semester_id,
            'kelas_id' => $request->kelas_id,
            'periode' => $request->periode,
            'ekstra_id' => $request->ekstra_id, // tambahkan ini
        ]))->with('success', 'Nilai ekstra berhasil disimpan.');
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

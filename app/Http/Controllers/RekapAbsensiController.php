<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\TahunSemester;
use App\Models\KelasSiswa;
use App\Models\RekapAbsensi;
use Illuminate\Http\Request;

class RekapAbsensiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index(Request $request)
    // {
    //     $tahunSemesterList = TahunSemester::orderByDesc('id')->get();
    //     $tahunSemesterId = $request->input('tahun_semester_id') ?? $tahunSemesterList->first()?->id;
    //     $periode = $request->input('periode', 'akhir');

    //     $selectedTahunSemester = TahunSemester::find($tahunSemesterId);
    //     $tahunAjaranId = $selectedTahunSemester?->tahun_ajaran_id;

    //     // Ambil semua kelas yang punya siswa di tahun semester ini
    //     $kelasList = Kelas::orderBy('nama')->get();
    //     $kelasId = $request->input('kelas_id') ?? $kelasList->first()?->id;

    //     // Ambil siswa di kelas dan tahun semester terpilih
    //     $rekapListByKelas = [];
    //     foreach ($kelasList as $kelas) {
    //         $rekapListByKelas[$kelas->id] = KelasSiswa::with(['siswa'])
    //             ->where('kelas_id', $kelas->id)
    //             // ->where('tahun_semester_id', $tahunSemesterId)
    //             ->where('tahun_ajaran_id', $tahunAjaranId)
    //             ->orderBy('no_absen')
    //             ->get()
    //             ->map(function ($item) use ($tahunAjaranId, $periode) {
    //                 // $item->rekapAbsensi = RekapAbsensi::where('siswa_id', $item->siswa_id)
    //                 //     ->where('tahun_semester_id', $tahunSemesterId)
    //                 //     ->where('periode', $periode)
    //                 //     ->first();
    //                 $item->rekapAbsensi = RekapAbsensi::where('kelas_siswa_id', $item->id)
    //                     ->where('periode', $periode)
    //                     ->first();
    //                 return $item;
    //             });
    //     }

    //     $breadcrumbs = [
    //         ['label' => 'Rekap Absensi', 'url' => role_route('rekap-absensi.index')],
    //     ];
    //     $title = 'Rekap Absensi';

    //     return view('rekap-absensi.index', [
    //         'breadcrumbs' => $breadcrumbs,
    //         'title' => $title,
    //         'tahunSemesterList' => $tahunSemesterList,
    //         'selectedTahunSemester' => $selectedTahunSemester,
    //         'kelasList' => $kelasList,
    //         'selectedKelasId' => $kelasId,
    //         'periode' => $periode,
    //         'rekapListByKelas' => $rekapListByKelas,
    //     ]);
    // }
    public function index(Request $request)
    {
        $user = auth()->user();
        $isGuru = $user->hasRole('guru');
        if ($isGuru && $user->guru?->id) {
            $guruId = $user->guru->id;
            // Tahun ajaran di mana guru jadi wali
            $tahunAjaranWaliIds = \App\Models\GuruKelas::where('guru_id', $guruId)
                ->where('peran', 'wali')
                ->pluck('tahun_ajaran_id')
                ->unique()
                ->toArray();
            // Tahun semester di tahun ajaran tersebut
            $daftarTahunSemester = \App\Models\TahunSemester::with('tahunAjaran')
                ->whereIn('tahun_ajaran_id', $tahunAjaranWaliIds)
                ->get()
                ->sortByDesc(fn($ts) => $ts->tahunAjaran->tahun)
                ->sortByDesc('semester')
                ->values();
        } else {
            $daftarTahunSemester = \App\Models\TahunSemester::with('tahunAjaran')
                ->get()
                ->sortByDesc(fn($ts) => $ts->tahunAjaran->tahun)
                ->sortByDesc('semester')
                ->values();
        }

        // Tahun semester aktif
        $tahunSemesterAktif = $daftarTahunSemester->firstWhere('is_active', true);

        // Ambil id tahun semester dari request, jika tidak ada pakai yang aktif
        $tahunSemesterId = $request->input('tahun_semester_id');
        if (!$tahunSemesterId || !$daftarTahunSemester->firstWhere('id', $tahunSemesterId)) {
            $tahunSemesterId = $tahunSemesterAktif?->id;
        }
        $selectedTahunSemester = $daftarTahunSemester->firstWhere('id', $tahunSemesterId);

        // Buat options tahun semester untuk filter
        $tahunSemesterOptions = $daftarTahunSemester->mapWithKeys(function ($ts) use ($daftarTahunSemester) {
            $label = ($ts->tahunAjaran->tahun ?? '-') . ' - ' . ucfirst($ts->semester);
            if ($daftarTahunSemester->firstWhere('is_active', true)?->id === $ts->id) {
                $label .= ' (Aktif)';
            }
            return [$ts->id => $label];
        })->toArray();

        // Periode absensi (default ke akhir)
        $periode = $request->input('periode', 'akhir');

        // Daftar kelas sesuai tahun ajaran dari tahun semester terpilih
        $daftarKelas = collect();
        if ($selectedTahunSemester) {
            if ($isGuru && $user->guru?->id) {
                // Kelas di mana guru jadi wali di tahun ajaran terpilih
                $kelasWaliIds = \App\Models\GuruKelas::where('guru_id', $guruId)
                    ->where('tahun_ajaran_id', $selectedTahunSemester->tahun_ajaran_id)
                    ->where('peran', 'wali')
                    ->pluck('kelas_id')
                    ->unique()
                    ->toArray();
                $daftarKelas = \App\Models\Kelas::whereIn('id', $kelasWaliIds)
                    ->orderBy('nama')
                    ->get();
            } else {
                $daftarKelas = \App\Models\KelasSiswa::with('kelas')
                    ->where('tahun_ajaran_id', $selectedTahunSemester->tahun_ajaran_id)
                    ->get()
                    ->pluck('kelas')
                    ->unique('id')
                    ->sortBy('nama')
                    ->values();
            }
        }

        // Kelas yang dipilih (default kosong)
        $kelasId = $request->kelas_id;

        // Data rekap absensi per kelas
        $rekapListByKelas = [];
        foreach ($daftarKelas as $kelas) {
            $rekapListByKelas[$kelas->id] = \App\Models\KelasSiswa::with(['siswa'])
                ->where('kelas_id', $kelas->id)
                ->where('tahun_ajaran_id', $selectedTahunSemester?->tahun_ajaran_id)
                ->orderBy('no_absen')
                ->get()
                ->map(function ($item) use ($periode, $tahunSemesterId) {
                    $item->rekapAbsensi = \App\Models\RekapAbsensi::where('kelas_siswa_id', $item->id)
                        ->where('tahun_semester_id', $tahunSemesterId)
                        ->where('periode', $periode)
                        ->first();
                    return $item;
                });
        }

        $breadcrumbs = [
            ['label' => 'Rekap Absensi', 'url' => role_route('rekap-absensi.index')],
        ];
        $title = 'Rekap Absensi';

        return view('rekap-absensi.index', compact(
            'breadcrumbs',
            'title',
            'daftarTahunSemester',
            'tahunSemesterId',
            'selectedTahunSemester',
            'daftarKelas',
            'kelasId',
            'periode',
            'rekapListByKelas',
            'tahunSemesterOptions'
        ));
    }


    public function updateBatch(Request $request)
    {
        $validated = $request->validate([
            'tahun_semester_id' => 'required|exists:tahun_semester,id',
            'periode' => 'required|in:tengah,akhir',
            'rekap' => 'required|array',
        ]);
        foreach ($request->rekap as $kelasSiswaId => $data) {
            RekapAbsensi::updateOrCreate(
                [
                    'tahun_semester_id' => $request->tahun_semester_id,
                    'kelas_siswa_id' => $kelasSiswaId,
                    'periode' => $request->periode,
                ],
                [
                    'total_sakit' => $data['sakit'] ?? 0,
                    'total_izin' => $data['izin'] ?? 0,
                    'total_alfa' => $data['alfa'] ?? 0,
                ]
            );
        }
        return redirect()->to(role_route('rekap-absensi.index', [
            'tahun_semester_id' => $request->tahun_semester_id,
            'periode' => $request->periode,
            'kelas_id' => $request->kelas_id, // tambahkan ini
        ]))
            ->with('success', 'Rekap presensi berhasil diperbarui.');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
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
    public function update(Request $request)
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

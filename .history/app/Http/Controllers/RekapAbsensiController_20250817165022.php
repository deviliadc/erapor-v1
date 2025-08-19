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
    public function index(Request $request)
    {
        $tahunSemesterList = TahunSemester::orderByDesc('id')->get();
        $tahunSemesterId = $request->input('tahun_semester_id') ?? $tahunSemesterList->first()?->id;
        // $periode = $request->input('periode', 'akhir');

        $selectedTahunSemester = TahunSemester::find($tahunSemesterId);
        $tahunAjaranId = $selectedTahunSemester?->tahun_ajaran_id;

        // Ambil semua kelas yang punya siswa di tahun semester ini
        $kelasList = Kelas::orderBy('nama')->get();
        $kelasId = $request->input('kelas_id') ?? $kelasList->first()?->id;

        // Ambil siswa di kelas dan tahun semester terpilih
        $rekapListByKelas = [];
        foreach ($kelasList as $kelas) {
            $rekapListByKelas[$kelas->id] = KelasSiswa::with(['siswa'])
                ->where('kelas_id', $kelas->id)
                // ->where('tahun_semester_id', $tahunSemesterId)
                ->where('tahun_ajaran_id', $tahunAjaranId)
                ->orderBy('no_absen')
                ->get()
                ->map(function ($item) use ($tahunAjaranId, $periode) {
                    // $item->rekapAbsensi = RekapAbsensi::where('siswa_id', $item->siswa_id)
                    //     ->where('tahun_semester_id', $tahunSemesterId)
                    //     ->where('periode', $periode)
                    //     ->first();
                    $item->rekapAbsensi = RekapAbsensi::where('kelas_siswa_id', $item->id)
                        ->where('periode', $periode)
                        ->first();
                    return $item;
                });
        }

        $breadcrumbs = [
            ['label' => 'Rekap Absensi', 'url' => role_route('rekap-absensi.index')],
        ];
        $title = 'Rekap Absensi';

        return view('rekap-absensi.index', c
        // [
        //     'breadcrumbs' => $breadcrumbs,
        //     'title' => $title,
        //     'tahunSemesterList' => $tahunSemesterList,
        //     'selectedTahunSemester' => $selectedTahunSemester,
        //     'kelasList' => $kelasList,
        //     'selectedKelasId' => $kelasId,
        //     'periode' => $periode,
        //     'rekapListByKelas' => $rekapListByKelas,
        // ]);
    }

    public function updateBatch(Request $request)
    {
        $validated = $request->validate([
            'tahun_semester_id' => 'required|exists:tahun_semester,id',
            'periode' => 'required|in:tengah,akhir',
            'rekap' => 'required|array',
        ]);

        // foreach ($request->rekap as $siswaId => $data) {
        //     RekapAbsensi::updateOrCreate(
        //         [
        //             'siswa_id' => $siswaId,
        //             'tahun_semester_id' => $request->tahun_semester_id,
        //             'periode' => $request->periode,
        //         ],
        //         [
        //             'total_sakit' => $data['sakit'] ?? 0,
        //             'total_izin' => $data['izin'] ?? 0,
        //             'total_alfa' => $data['alfa'] ?? 0,
        //         ]
        //     );
        // }
        foreach ($request->rekap as $kelasSiswaId => $data) {
            RekapAbsensi::updateOrCreate(
                [
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

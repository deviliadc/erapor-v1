<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\TahunSemester;
use App\Models\Siswa;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class RaporController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $breadcrumbs = [
            ['label' => 'Rapor'],
        ];

        $title = 'Rapor';

        // Ambil tahun ajaran aktif
        $tahunAktif = TahunSemester::where('is_active', true)->first();
        $tahunSemesterId = $request->input('tahun_semester_id', $tahunAktif?->id);
        // Ambil daftar tahun semester untuk filter
        $daftarTahunSemester = TahunSemester::orderByDesc('id')->get();

        $kelas_id = $request->input('kelas_id');

        // Ambil daftar tahun ajaran dan kelas untuk filter
        $tahunList = TahunSemester::orderByDesc('tahun')->get();
        $kelasList = Kelas::orderBy('nama')->get();

        // Filter UTS/US (periode) di atas
        $periode = $request->input('periode', 'uts'); // 'uts' atau 'us'


        // Filter kelas sesuai role
        $user = Auth::user();
        if ($user->hasRole('guru')) {
            $kelasIds = $user->guru?->kelasDiampuIds() ?? [];
            $kelasList = $kelasList->whereIn('id', $kelasIds);
        }

        // Query siswa
        // $query = Siswa::query()
        //     ->whereHas('kelasSiswa', function ($q) use ($tahunSemesterId, $kelas_id) {
        //         $q->where('tahun_semester_id', $tahunSemesterId);
        //         if ($kelas_id) {
        //             $q->where('kelas_id', $kelas_id);
        //         }
        //     });
        $query = Siswa::with(['kelasSiswa.kelas'])
            ->whereHas('kelasSiswa', function ($q) use ($tahunSemesterId, $kelas_id) {
                $q->where('tahun_semester_id', $tahunSemesterId);
                if ($kelas_id) {
                    $q->where('kelas_id', $kelas_id);
                }
            });

        // Urutkan berdasarkan kelas dan nama
        $siswaList = $query->get()->sortBy(function ($siswa) use ($tahunSemesterId) {
            return optional(
                $siswa->kelasSiswa->where('tahun_semester_id', $tahunSemesterId)->first()?->kelas
            )->nama;
        })->sortBy('nama');


        return view('rapor.index', [
            'breadcrumbs' => $breadcrumbs,
            'title' => $title,
            'tahunAktif' => $tahunAktif,
            'tahunSemesterId' => $tahunSemesterId,
            'daftarTahunSemester' => $daftarTahunSemester,
            'kelasList' => $kelasList,
            'kelas_id' => $kelas_id,
            'periode' => $periode,
            'siswaList' => $siswaList,
            'tahunList' => $tahunList,
            'totalCount' => $siswaList->count(),
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $siswa = Siswa::with(['kelasSiswa.kelas'])->findOrFail($id);
        $breadcrumbs = [
            ['label' => 'Rapor', 'url' => role_route('rapor.index')],
            ['label' => 'Detail Rapor'],
        ];
        $title = 'Detail Rapor';

        return view('rapor.show', [
            'breadcrumbs' => $breadcrumbs,
            'title' => $title,
            'siswa' =>' $siswa,'
        ]);
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

    /**
     * Generate a report.
     */
    public function print(Request $request)
    {
        // Validate the request data
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Fase;
use App\Models\Guru;
use App\Models\GuruKelas;
use App\Models\Kelas;
use App\Models\KelasSiswa;
use App\Models\TahunSemester;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);

        // Ambil tahun semester dari filter, default ke tahun aktif
        $tahunAktif = TahunSemester::where('is_active', true)->first();
        $tahunSemesterId = $request->input('tahun_semester_filter', $tahunAktif ? $tahunAktif->id : null);
        $query = Kelas::query()
            ->orderBy('nama', 'asc')
            ->orderBy('fase_id', 'asc');
        $totalCount = $query->count();
        $paginator = $query->paginate($perPage)->withQueryString();

        // Ambil semua tahun semester untuk filter
        $tahunSemesterList = TahunSemester::orderByDesc('tahun')->orderByDesc('semester')->get();

        // Data untuk tampilan
        $kelas = $paginator->through(function ($item) use ($tahunSemesterId) {
            $waliKelas = $item->waliKelas($tahunSemesterId); // Sudah method custom, return Guru atau null

            $fase = $item->fase ? $item->fase->nama : ($item->fase ?? '-');

            $mapelList = $item->getMapel($tahunSemesterId); // Method custom, return collection Mapel

            return [
                'id' => $item->id,
                'nama' => $item->nama,
                'fase_id' => $item->fase_id,
                'fase' => $fase,
                'wali' => $waliKelas ? $waliKelas->nama : '-',
                'wali_kelas_id' => $waliKelas ? $waliKelas->id : null,
                'mapel' => $mapelList ? $mapelList->map(fn($m) => ['nama' => $m->nama])->toArray() : [],
                'mapel_count' => $item->guruKelas()
                    ->where('peran', 'pengajar')
                    ->where('tahun_semester_id', $tahunSemesterId)
                    ->count(),
                'siswa_count' => KelasSiswa::where('kelas_id', $item->id)
                    ->where('tahun_semester_id', $tahunSemesterId)
                    ->count(),
            ];
        });

        $breadcrumbs = [
            ['label' => 'Manage Kelas'],
        ];

        $title = 'Manage Kelas';
        $guru = Guru::pluck('nama', 'id');
        $faseList = Fase::pluck('nama', 'id');

        // Untuk filter select
        $tahunSemesterSelect = $tahunSemesterList->map(function ($ts) {
            return [
                'id' => $ts->id,
                'name' => $ts->tahun . ' - ' . ucfirst($ts->semester)
            ];
        });

        return view('kelas.index', compact(
            'kelas',
            'totalCount',
            'breadcrumbs',
            'title',
            'tahunAktif',
            'guru',
            'faseList',
            'tahunSemesterList',
            'tahunSemesterSelect'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // $breadcrumbs = [
        //     ['label' => 'Manage Kelas', 'url' => route('kelas.index')],
        //     ['label' => 'Create Kelas'],
        // ];

        // $title = 'Create Kelas';

        // return view('kelas.create', compact('breadcrumbs', 'title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:50|unique:kelas,nama',
            'fase_id' => 'required|exists:fase,id',
            // 'wali_kelas_id' => 'required|exists:guru,id',
        ]);

        Kelas::create([
            'nama' => $request->nama,
            'fase_id' => $request->fase_id,
        ]);

        // Ambil tahun semester aktif
        // $tahunAktif = TahunSemester::where('is_active', true)->first();

        // Simpan wali kelas ke tabel guru_kelas
        // GuruKelas::create([
        //     'guru_id' => $request->wali_kelas_id,
        //     'kelas_id' => $kelas->id,
        //     'tahun_semester_id' => $tahunAktif ? $tahunAktif->id : null,
        //     'peran' => 'wali',
        //     'mapel_id' => null,
        // ]);

        return redirect()->to(role_route('kelas.index'))->with('success', 'Data kelas berhasil ditambahkan.');
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
        // $kelas = Kelas::findOrFail($id);

        // $title = 'Edit Kelas';
        // $breadcrumbs = [
        //     ['label' => 'Manage Kelas', 'url' => role_route('kelas.index')],
        //     ['label' => 'Edit Kelas'],
        // ];
        // return view('kelas.edit', compact('kelas', 'title', 'breadcrumbs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama' => 'required|string|max:50|unique:kelas,nama,' . $id,
            'wali_kelas_id' => 'required|exists:guru,id',
            'fase_id' => 'required|exists:fase,id',
        ]);

        $kelas = Kelas::findOrFail($id);

        $kelas->update([
            'nama' => $request->nama,
            'guru_id' => $request->wali_kelas_id,
            'fase_id' => $request->fase_id,
        ]);

        // Ambil tahun semester dari filter, default ke tahun aktif
        $tahunAktif = TahunSemester::where('is_active', true)->first();
        $tahunSemesterId = $request->input('tahun_semester_filter', $tahunAktif ? $tahunAktif->id : null);

        GuruKelas::updateOrCreate(
            [
                'kelas_id' => $kelas->id,
                'tahun_semester_id' => $tahunSemesterId,
                'peran' => 'wali',
            ],
            [
                'guru_id' => $request->wali_kelas_id,
                'mapel_id' => null,
            ]
        );

        return redirect()->to(role_route('kelas.index', ['tahun_semester_filter' => $tahunSemesterId]))
            ->with('success', 'Data kelas berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $kelas = Kelas::findOrFail($id);
        $kelas->delete();

        return redirect()->to(role_route('kelas.index'))->with('success', 'Data kelas berhasil dihapus.');
    }
}

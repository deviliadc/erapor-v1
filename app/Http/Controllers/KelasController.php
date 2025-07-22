<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\GuruKelas;
use App\Models\Kelas;
use App\Models\KelasSiswa;
use App\Models\TahunSemester;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

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

        $query = Kelas::query();
        $totalCount = $query->count();
        $paginator = $query->paginate($perPage)->withQueryString();

        // Ambil semua tahun semester untuk filter
        $tahunSemesterList = TahunSemester::orderByDesc('tahun')->orderByDesc('semester')->get();

        // Data untuk tampilan
        $kelas = $paginator->through(function ($item) use ($tahunSemesterId) {
            $waliKelas = $item->guruKelas()
                ->where('peran', 'wali')
                ->where('tahun_semester_id', $tahunSemesterId)
                ->with('guru')
                ->first();

            return [
                'id' => $item->id,
                'nama' => $item->nama,
                'wali' => $waliKelas && $waliKelas->guru ? $waliKelas->guru->nama : '-',
                'wali_kelas_id' => $waliKelas ? $waliKelas->guru_id : null,
                'mapel' => $item->mapel ? $item->mapel->map(function ($m) {
                    return ['nama' => $m->nama];
                })->toArray() : [],
                'mapel_count' => $item->guruKelas()
                    ->where('peran', 'pengajar')
                    ->where('tahun_semester_id', $tahunSemesterId)
                    ->count(),
                'siswa_count' => \App\Models\KelasSiswa::where('kelas_id', $item->id)
                    ->where('tahun_semester_id', $tahunSemesterId)
                    ->count(),
            ];
        });

        $breadcrumbs = [
            ['label' => 'Manage Kelas', 'url' => route('kelas.index')],
        ];

        $title = 'Manage Kelas';
        $guru = Guru::pluck('nama', 'id');

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
            'tahunSemesterList',
            'tahunSemesterSelect'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $breadcrumbs = [
            ['label' => 'Manage Kelas', 'url' => route('kelas.index')],
            ['label' => 'Create Kelas'],
        ];

        $title = 'Create Kelas';

        return view('kelas.create', compact('breadcrumbs', 'title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:50|unique:kelas,nama',
            'wali_kelas_id' => 'required|exists:guru,id',
        ]);

        $kelas = Kelas::create([
            'nama' => $request->nama,
        ]);

        // Ambil tahun semester aktif
        $tahunAktif = TahunSemester::where('is_active', true)->first();

        // Simpan wali kelas ke tabel guru_kelas
        GuruKelas::create([
            'guru_id' => $request->wali_kelas_id,
            'kelas_id' => $kelas->id,
            'tahun_semester_id' => $tahunAktif ? $tahunAktif->id : null,
            'peran' => 'wali',
            'mapel_id' => null,
        ]);

        return redirect()->route('kelas.index')->with('success', 'Data kelas berhasil ditambahkan.');
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
        $kelas = Kelas::findOrFail($id);

        $title = 'Edit Kelas';
        $breadcrumbs = [
            ['label' => 'Manage Kelas', 'url' => route('kelas.index')],
            ['label' => 'Edit Kelas'],
        ];
        return view('kelas.edit', compact('kelas', 'title', 'breadcrumbs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama' => 'required|string|max:50|unique:kelas,nama,' . $id,
            'wali_kelas_id' => 'required|exists:guru,id',
        ]);

        $kelas = Kelas::findOrFail($id);

        $kelas->update([
            'nama' => $request->nama,
            'guru_id' => $request->wali_kelas_id,
        ]);

        // Ambil tahun ajaran aktif
        $tahunAktif = TahunSemester::where('is_active', true)->first();

        // Update atau insert wali kelas di guru_kelas
        GuruKelas::updateOrCreate(
            [
                'kelas_id' => $kelas->id,
                'tahun_semester_id' => $tahunAktif ? $tahunAktif->id : null,
                'peran' => 'wali',
            ],
            [
                'guru_id' => $request->wali_kelas_id,
                'mapel_id' => null,
            ]
        );

        return redirect()->route('kelas.index')->with('success', 'Data kelas berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $kelas = Kelas::findOrFail($id);
        $kelas->delete();

        return redirect()->route('kelas.index')->with('success', 'Data kelas berhasil dihapus.');
    }
}

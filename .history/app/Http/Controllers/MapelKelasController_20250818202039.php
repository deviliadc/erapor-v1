<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\GuruKelas;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\TahunSemester;
use Illuminate\Http\Request;

class MapelKelasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);

        $kelas = Kelas::findOrFail($kelas);
        $tahunAktif = TahunSemester::where('is_active', true)->first();

        // Semua mapel yang sudah dipakai di kelas ini (peran pengajar)
        $mapelTerpakaiAll = GuruKelas::where('kelas_id', $kelas->id)
            ->where('tahun_semester_id', $tahunAktif?->id)
            ->where('peran', 'pengajar')
            ->pluck('mapel_id')
            ->toArray();

        // Untuk tabel dan modal edit (pakai paginator jika ingin paginasi)
        $mapelList = GuruKelas::with(['guru', 'mapel'])
            ->where('kelas_id', $kelas->id)
            ->where('tahun_semester_id', $tahunAktif?->id)
            ->where('peran', 'pengajar')
            ->paginate($perPage);

        // Siapkan data untuk row dan modal edit
        $mapelList->getCollection()->transform(function ($item) use ($mapelTerpakaiAll) {
            // Mapel yang belum dipakai selain yang sedang diedit
            $mapelTerpakai = array_diff($mapelTerpakaiAll, [$item->mapel_id]);
            $mapelSelect = Mapel::whereNotIn('id', $mapelTerpakai)
                ->orWhere('id', $item->mapel_id)
                ->pluck('nama', 'id')
                ->toArray();
            return [
                'id' => $item->id,
                'mapel_id' => $item->mapel_id,
                'guru_id' => $item->guru_id,
                'nama' => $item->mapel->nama ?? '-',
                'guru_nama' => $item->guru->nama ?? '-',
                'mapelSelect' => $mapelSelect,
            ];
        });

        $totalCount = $mapelList->total();

        // Untuk create: semua mapel yang belum dipakai
        $mapelSelect = Mapel::whereNotIn('id', $mapelTerpakaiAll)
            ->pluck('nama', 'id')
            ->toArray();

        $guruSelect = Guru::pluck('nama', 'id')->toArray();

        $title = 'Mapel Kelas ' . $kelas->nama;
        $breadcrumbs = [
            ['label' => 'Manage Kelas', 'url' => role_route('kelas.index')],
            ['label' => 'Mapel'],
        ];

        return view('kelas.mapel.index', compact(
            'tahunAktif',
            'kelas',
            'mapelList',
            'title',
            'breadcrumbs',
            'totalCount',
            'guruSelect',
            'mapelSelect'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // $kelas = Kelas::findOrFail($kelas);

        // // $breadcrumbs = [
        // //     ['label' => 'Kelas', 'url' => role_route('kelas.index')],
        // //     ['label' => 'Mapel', 'url' => role_route('kelas.mapel.index', $kelas->id)],
        // //     ['label' => 'Tambah Mapel'],
        // // ];

        // // $title = 'Tambah Mapel Kelas';
        // $guruSelect = Guru::pluck('nama', 'id')->toArray();

        // // Ambil tahun ajaran aktif
        // $tahunAjaranAktif = TahunSemester::where('is_active', true)->first();
        // // Ambil semua mapel yang sudah dipakai di kelas & tahun ajaran aktif
        // $mapelTerpakai = GuruKelas::where('kelas_id', $kelas->id)
        //     ->where('tahun_semester_id', $tahunAktif?->id)
        //     ->pluck('mapel_id')
        //     ->toArray();

        // // Ambil mapel yang BELUM dipakai
        // $mapelSelect = Mapel::whereNotIn('id', $mapelTerpakai)
        //     ->pluck('nama', 'id')
        //     ->toArray();

        // return view('kelas.mapel.create', compact(
        //     'kelas',
        //     // 'title',
        //     // 'breadcrumbs',
        //     'guruSelect',
        //     'mapelSelect'
        // ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, string $kelas)
    {
        $request->validate([
            'mapel_id' => 'required|array',
            'mapel_id.*' => 'required|exists:mapel,id',
            'guru_id' => 'required|exists:guru,id',
        ]);

        $tahunAktif = TahunSemester::where('is_active', true)->first();

        foreach ($request->mapel_id as $mapelId) {
            GuruKelas::create([
                'kelas_id' => $kelas,
                'mapel_id' => $mapelId,
                'guru_id' => $request->guru_id,
                'tahun_semester_id' => $tahunAktif?->id,
                'peran' => 'pengajar',
            ]);
        }

        // return redirect()->to(role_route('kelas.mapel.index', $kelas))
        // ->with('success', 'Mapel berhasil ditambahkan.');
        return redirect()->to(role_route('kelas.mapel.index', [$kelas]))
        ->with('success', 'Mapel berhasil ditambahkan.');
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
    public function edit(string $kelas, string $id)
    {
        $kelas = Kelas::findOrFail($kelas);
        $guruKelas = GuruKelas::findOrFail($id);

        // $title = 'Edit Mapel Kelas';
        // $breadcrumbs = [
        //     ['label' => 'Kelas', 'url' => role_route('kelas.index')],
        //     ['label' => 'Mapel', 'url' => role_route('kelas.mapel.index', $kelas->id)],
        //     ['label' => 'Edit Mapel'],
        // ];

        $guruSelect = Guru::pluck('nama', 'id')->toArray();
        $mapelSelect = Mapel::pluck('nama', 'id')->toArray();

        return view('kelas.mapel.edit', compact(
            'kelas',
            'guruKelas',
            'title',
            'breadcrumbs',
            'guruSelect',
            'mapelSelect'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $kelas, string $mapel)
    {
        $request->validate([
            'mapel_id' => 'required|exists:mapel,id',
            'guru_id' => 'required|exists:guru,id',
        ]);

        $guruKelas = GuruKelas::findOrFail($mapel);
        $guruKelas->update([
            'mapel_id' => $request->mapel_id,
            'guru_id' => $request->guru_id,
        ]);

        // return redirect()->route('kelas.mapel.index', $kelas)->with('success', 'Mapel berhasil diperbarui.');
        return redirect()->to(role_route('kelas.mapel.index', [$kelas]))->with('success', 'Mapel berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $kelas, string $id)
    {
        $guruKelas = GuruKelas::findOrFail($id);
        $guruKelas->delete();

        // return redirect()->route('kelas.mapel.index', $kelas)->with('success', 'Mapel berhasil dihapus.');
        return redirect()->to(role_route('kelas.mapel.index', [$kelas]))->with('success', 'Mapel berhasil dihapus.');
    }
}

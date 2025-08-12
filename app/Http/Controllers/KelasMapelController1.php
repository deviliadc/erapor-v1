<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\GuruKelas;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\TahunSemester;
use Illuminate\Http\Request;

class KelasMapelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $kelas)
    {
        $perPage = $request->input('per_page', 10);

        $query = Kelas::query();

        $totalCount = $query->count();
        $paginator = $query->paginate($perPage)->withQueryString();

        // $kelas = Kelas::findOrFail($kelas);
        $tahunAktif = TahunSemester::where('is_active', true)->first();

        $kelasList = $paginator->through(function ($item) use ($tahunAktif) {
            $waliKelas = $item->guruKelas()
                ->where('peran', 'wali')
                ->where('tahun_semester_id', $tahunAktif ? $tahunAktif->id : null)
                ->with('guru')
                ->first();

            // Ambil jumlah mapel per semester aktif
            $mapelCount = $item->guruKelas()
                ->where('peran', 'pengajar')
                ->where('tahun_semester_id', $tahunAktif ? $tahunAktif->id : null)
                ->count();

            return [
                'id' => $item->id,
                'nama' => $item->nama,
                'wali' => $waliKelas && $waliKelas->guru ? $waliKelas->guru->nama : '-',
                'wali_kelas_id' => $waliKelas ? $waliKelas->guru_id : null,
                'mapel_count' => $mapelCount,
            ];
        });

        $mapelList = GuruKelas::with(['guru', 'mapel'])
            // ->where('kelas_id', $kelas->id)
            ->where('tahun_semester_id', $tahunAktif?->id)
            ->where('peran', 'pengajar')
            ->paginate($perPage)
            ->withQueryString();

        $mapel = $mapelList->getCollection()->map(function ($item) {
            return [
                'id' => $item->id,
                'mapel_id' => $item->mapel_id,
                'guru_id' => $item->guru_id,
                'nama' => $item->mapel->nama ?? '-',
                'guru_nama' => $item->guru->nama ?? '-',
            ];
        });

        $mapelList->setCollection($mapel);

        $totalCount = $mapelList->total();

        $title = 'Mapel Kelas ' . $kelas->nama;
        $breadcrumbs = [
            ['label' => 'Kelas', 'url' => role_route('mapel.index')],
            ['label' => 'Mapel', 'url' => role_route('kelas-mapel.index', $kelas->id)],
        ];

        $mapelTerpakai = GuruKelas::where('kelas_id', $kelas->id)
            ->where('tahun_semester_id', $tahunAktif?->id)
            ->pluck('mapel_id')
            ->toArray();

        // Ambil mapel yang BELUM dipakai
        $mapelOptions = Mapel::whereNotIn('id', $mapelTerpakai)
            ->pluck('nama', 'id')
            ->toArray();

        $guruOptions = Guru::pluck('nama', 'id')->toArray();

        return view('mapel.index', compact('kelas', 'mapel', 'mapelList', 'title', 'breadcrumbs', 'tahunAktif', 'guruOptions', 'mapelOptions', 'totalCount'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create(string $kelas)
    {
        $kelas = Kelas::findOrFail($kelas);

        $breadcrumbs = [
            ['label' => 'Kelas', 'url' => role_route('mapel.index')],
            ['label' => 'Mapel', 'url' => role_route('kelas-mapel.index', $kelas->id)],
            ['label' => 'Tambah Mapel'],
        ];

        $title = 'Tambah Mapel Kelas';
        $guruOptions = Guru::pluck('nama', 'id')->toArray();

        // Ambil tahun ajaran aktif
        $tahunAktif = TahunSemester::where('is_active', true)->first();

        // Ambil semua mapel yang sudah dipakai di kelas & tahun ajaran aktif
        $mapelTerpakai = GuruKelas::where('kelas_id', $kelas->id)
            ->where('tahun_semester_id', $tahunAktif?->id)
            ->pluck('mapel_id')
            ->toArray();

        // Ambil mapel yang BELUM dipakai
        $mapelOptions = Mapel::whereNotIn('id', $mapelTerpakai)
            ->pluck('nama', 'id')
            ->toArray();

        return view('kelas-mapel.create', compact('kelas', 'title', 'breadcrumbs', 'guruOptions', 'mapelOptions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, string $id)
    {
        $request->validate([
            'mapel_id' => 'required|array',
            'mapel_id.*' => 'required|exists:mapel,id',
            'guru_id' => 'required|exists:guru,id',
        ]);

        $tahunAktif = TahunSemester::where('is_active', true)->first();

        foreach ($request->mapel_id as $mapelId) {
            GuruKelas::create([
                // 'kelas_id' => $kelas,
                'mapel_id' => $mapelId,
                'guru_id' => $request->guru_id,
                'tahun_semester_id' => $tahunAktif?->id,
                'peran' => 'pengajar',
            ]);
        }

        return redirect()->to(role_route('mapel.index', ['tab' => $request->tab ?? 'kelas']))->with('success', 'Mapel berhasil ditambahkan.');
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
        // $kelas = Kelas::findOrFail($kelas);
        $guruKelas = GuruKelas::findOrFail($id);

        $guruOptions = Guru::pluck('nama', 'id')->toArray();
        $mapelOptions = Mapel::pluck('nama', 'id')->toArray();

        return view('kelas-mapel.edit', compact('kelas', 'guruKelas', 'guruOptions', 'mapelOptions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'mapel_id' => 'required|exists:mapel,id',
            'guru_id' => 'required|exists:guru,id',
        ]);

        $guruKelas = GuruKelas::findOrFail($id);
        $guruKelas->update([
            'mapel_id' => $request->mapel_id,
            'guru_id' => $request->guru_id,
        ]);

        return redirect()->to(role_route('mapel.index', ['tab' => $request->tab ?? 'kelas']))->with('success', 'Mapel berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $guruKelas = GuruKelas::findOrFail($id);
        $guruKelas->delete();

        return redirect()->to(role_route('mapel.index', ['tab' => $request->tab ?? 'kelas']))->with('success', 'Mapel berhasil dihapus.');
    }
}

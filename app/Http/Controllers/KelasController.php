<?php

namespace App\Http\Controllers;

use App\Models\Fase;
use App\Models\Guru;
use App\Models\GuruKelas;
use App\Models\Kelas;
use App\Models\KelasSiswa;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KelasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index(Request $request)
    // {
    //     $perPage = $request->input('per_page', 10);

    //     // Ambil tahun semester dari filter, default ke tahun aktif
    //     $tahunAktif = TahunSemester::where('is_active', true)->first();
    //     $tahunSemesterId = $request->input('tahun_semester_filter', $tahunAktif ? $tahunAktif->id : null);
    //     $tahunAjaranId = $tahunAktif ? $tahunAktif->tahunAjaran->id : null;
    //     $query = Kelas::query()
    //         ->orderBy('nama', 'asc')
    //         ->orderBy('fase_id', 'asc');
    //     $totalCount = $query->count();
    //     $paginator = $query->paginate($perPage)->withQueryString();

    //     // Ambil semua tahun semester untuk filter
    //     // $tahunSemesterList = TahunSemester::orderByDesc('tahun')->orderByDesc('semester')->get();
    //     $tahunSemesterList = TahunSemester::with('tahunAjaran')
    //         ->orderByDesc(
    //             TahunAjaran::select('mulai')
    //                 ->whereColumn('tahun_ajaran_id', 'tahun_ajaran.id')
    //         )
    //         ->orderByDesc('semester')
    //         ->get();

    //     // Data untuk tampilan
    //     $kelas = $paginator->through(function ($item) use ($tahunSemesterId) {
    //         $waliKelas = $item->waliKelas($tahunSemesterId); // Sudah method custom, return Guru atau null
    //         $fase = $item->fase ? $item->fase->nama : ($item->fase ?? '-');
    //         $mapelList = $item->getMapel($tahunSemesterId); // Method custom, return collection Mapel
    //         return [
    //             'id' => $item->id,
    //             'nama' => $item->nama,
    //             'fase_id' => $item->fase_id,
    //             'fase' => $fase,
    //             'wali' => $waliKelas ? $waliKelas->nama : '-',
    //             'wali_kelas_id' => $waliKelas ? $waliKelas->id : null,
    //             'mapel' => $mapelList ? $mapelList->map(fn($m) => ['nama' => $m->nama])->toArray() : [],
    //             'mapel_count' => $item->guruKelas()
    //                 ->where('peran', 'pengajar')
    //                 ->where('tahun_semester_id', $tahunSemesterId)
    //                 ->count(),
    //             'siswa_count' => KelasSiswa::where('kelas_id', $item->id)
    //                 ->where('tahun_semester_id', $tahunAjaranId)
    //                 ->count(),
    //         ];
    //     });

    //     $guru = Guru::pluck('nama', 'id');
    //     $faseList = Fase::pluck('nama', 'id');

    //     // Untuk filter select
    //     $tahunSemesterSelect = $tahunSemesterList->map(function ($ts) {
    //         return [
    //             'id' => $ts->id,
    //             'name' => $ts->tahun . ' - ' . ucfirst($ts->semester)
    //         ];
    //     });

    //     $breadcrumbs = [
    //         ['label' => 'Manage Kelas'],
    //     ];
    //     $title = 'Manage Kelas';

    //     return view('kelas.index', compact(
    //         'kelas',
    //         'totalCount',
    //         'breadcrumbs',
    //         'title',
    //         'tahunAktif',
    //         'guru',
    //         'faseList',
    //         'tahunSemesterList',
    //         'tahunSemesterSelect'
    //     ));
    // }
    // public function index(Request $request)
    // {
    //     $perPage = $request->input('per_page', 10);

    //     // Ambil tahun semester dari filter, default ke tahun aktif
    //     $tahunAktif = TahunSemester::where('is_active', true)->first();
    //     $tahunSemesterId = $request->input('tahun_semester_filter', $tahunAktif ? $tahunAktif->id : null);

    //     // Ambil tahun ajaran id dari tahun semester yang dipilih
    //     $tahunAjaranId = null;
    //     if ($tahunSemesterId) {
    //         $tahunSemester = TahunSemester::with('tahunAjaran')->find($tahunSemesterId);
    //         $tahunAjaranId = $tahunSemester ? $tahunSemester->tahun_ajaran_id : null;
    //     }

    //     // Query kelas
    //     $query = Kelas::query()
    //         ->orderBy('nama', 'asc')
    //         ->orderBy('fase_id', 'asc');
    //     $totalCount = $query->count();
    //     $paginator = $query->paginate($perPage)->withQueryString();

    //     // Ambil semua tahun semester untuk filter
    //     $tahunSemesterList = TahunSemester::with('tahunAjaran')
    //         ->orderByDesc(
    //             TahunAjaran::select('mulai')
    //                 ->whereColumn('tahun_ajaran_id', 'tahun_ajaran.id')
    //         )
    //         ->orderByDesc('semester')
    //         ->get();

    //     // Data untuk tampilan
    //     $kelas = $paginator->through(function ($item) use ($tahunSemesterId, $tahunAjaranId) {
    //         $waliKelas = $item->waliKelas($tahunSemesterId); // method custom, return Guru atau null
    //         $fase = $item->fase ? $item->fase->nama : ($item->fase ?? '-');
    //         $mapelList = $item->getMapel($tahunSemesterId); // method custom, return collection Mapel
    //         return [
    //             'id' => $item->id,
    //             'nama' => $item->nama,
    //             'fase_id' => $item->fase_id,
    //             'fase' => $fase,
    //             'wali' => $waliKelas ? $waliKelas->nama : '-',
    //             'wali_kelas_id' => $waliKelas ? $waliKelas->id : null,
    //             'mapel' => $mapelList ? $mapelList->map(fn($m) => ['nama' => $m->nama])->toArray() : [],
    //             'mapel_count' => $item->guruKelas()
    //                 ->where('peran', 'pengajar')
    //                 ->where('tahun_semester_id', $tahunSemesterId)
    //                 ->count(),
    //             // Perbaikan: siswa_count berdasarkan tahun_ajaran_id
    //             'siswa_count' => $tahunAjaranId
    //                 ? KelasSiswa::where('kelas_id', $item->id)
    //                     ->where('tahun_ajaran_id', $tahunAjaranId)
    //                     ->count()
    //                 : 0,
    //         ];
    //     });

    //     $guru = Guru::pluck('nama', 'id');
    //     $faseList = Fase::pluck('nama', 'id');

    //     // Untuk filter select
    //     $tahunSemesterSelect = $tahunSemesterList->map(function ($ts) {
    //         return [
    //             'id' => $ts->id,
    //             'name' => ($ts->tahunAjaran ? $ts->tahunAjaran->tahun : '-') . ' - ' . ucfirst($ts->semester)
    //         ];
    //     });

    //     $breadcrumbs = [
    //         ['label' => 'Manage Kelas'],
    //     ];
    //     $title = 'Manage Kelas';

    //     return view('kelas.index', compact(
    //         'kelas',
    //         'totalCount',
    //         'breadcrumbs',
    //         'title',
    //         'tahunAktif',
    //         'guru',
    //         'faseList',
    //         'tahunSemesterList',
    //         'tahunSemesterSelect'
    //     ));
    // }

    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);

        // Ambil tahun ajaran dari filter, default ke tahun ajaran aktif
        $tahunAjaranId = $request->input('tahun_ajaran_filter');
        $tahunAjaranAktif = TahunAjaran::where('is_active', true)->first();
        if (!$tahunAjaranId) {
            $tahunAjaranId = $tahunAjaranAktif?->id;
        }

        // Cek apakah user login adalah guru
        $user = Auth::user();
        $guruId = $user->guru?->id; // pastikan relasi `guru()` ada di model User

            $search = $request->input('search');
            $query = Kelas::query()
                ->orderBy('nama', 'asc')
                ->orderBy('fase_id', 'asc');

            // Jika user adalah guru, filter hanya kelas yang dia jadi wali
            if ($guruId) {
                $query->whereHas('guruKelas', function ($q) use ($guruId, $tahunAjaranId) {
                    $q->where('guru_id', $guruId)
                        ->where('peran', 'wali')
                        ->where('tahun_ajaran_id', $tahunAjaranId);
                });
            }

            // Search by nama kelas or fase
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama', 'like', "%$search%")
                      ->orWhereHas('fase', function ($fq) use ($search) {
                          $fq->where('nama', 'like', "%$search%");
                      });
                });
            }

            $totalCount = $query->count();
            $paginator = $query->paginate($perPage)->withQueryString();

        // Ambil semua tahun ajaran untuk filter
        $tahunAjaranCollection = TahunAjaran::orderByDesc('tahun')->get();

        $tahunAjaranSelect = $tahunAjaranCollection->map(function ($item) use ($tahunAjaranAktif) {
            return [
                'id' => $item->id,
                'name' => $item->tahun . ($item->id == $tahunAjaranAktif?->id ? ' (Aktif)' : ''),
            ];
        });

        $kelas = $paginator->through(function ($item) use ($tahunAjaranId) {
            $waliKelas = GuruKelas::where('kelas_id', $item->id)
                ->where('peran', 'wali')
                ->where('tahun_ajaran_id', $tahunAjaranId)
                ->with('guru')
                ->first();

            $fase = $item->fase ? $item->fase->nama : ($item->fase ?? '-');
            $mapelList = $item->getMapelByTahunAjaran($tahunAjaranId);

            return [
                'id' => $item->id,
                'nama' => $item->nama,
                'fase_id' => $item->fase_id,
                'fase' => $fase,
                'wali' => $waliKelas && $waliKelas->guru ? $waliKelas->guru->nama : '-',
                'wali_kelas_id' => $waliKelas && $waliKelas->guru ? $waliKelas->guru->id : null,
                'mapel' => $mapelList ? $mapelList->map(fn($m) => ['nama' => $m->nama])->toArray() : [],
                'mapel_count' => $mapelList ? $mapelList->count() : 0,
                'siswa_count' => KelasSiswa::where('kelas_id', $item->id)
                    ->where('tahun_ajaran_id', $tahunAjaranId)
                    ->count(),
            ];
        });

        $guru = Guru::pluck('nama', 'id');
        $faseList = Fase::pluck('nama', 'id');

        $breadcrumbs = [['label' => 'Manage Kelas']];
        $title = 'Manage Kelas';

        return view('kelas.index', compact(
            'kelas',
            'totalCount',
            'breadcrumbs',
            'title',
            'tahunAjaranAktif',
            'guru',
            'faseList',
            'tahunAjaranCollection',
            'tahunAjaranSelect',
            'tahunAjaranId',
                'search',
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
        ]);
        Kelas::create([
            'nama' => $request->nama,
            'fase_id' => $request->fase_id,
        ]);
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
            // 'wali_kelas_id' => 'required|exists:guru,id',
            'fase_id' => 'required|exists:fase,id',
        ]);

        $kelas = Kelas::findOrFail($id);

        $kelas->update([
            'nama' => $request->nama,
            // 'guru_id' => $request->wali_kelas_id,
            'fase_id' => $request->fase_id,
        ]);

        // Ambil tahun semester dari filter, default ke tahun aktif
        // $tahunAktif = TahunSemester::where('is_active', true)->first();
        // $tahunSemesterId = $request->input('tahun_semester_filter', $tahunAktif ? $tahunAktif->id : null);
        $tahunAktif = TahunAjaran::where('is_active', true)->first();
        $tahunAjaranId = $request->input('tahun_ajaran_filter', $tahunAktif ? $tahunAktif->id : null);

        // GuruKelas::updateOrCreate(
        //     [
        //         'kelas_id' => $kelas->id,
        //         // 'tahun_semester_id' => $tahunSemesterId,
        //         'tahun_ajaran_id' => $tahunAjaranId,
        //         'peran' => 'wali',
        //     ],
        //     [
        //         'guru_id' => $request->wali_kelas_id,
        //         'mapel_id' => null,
        //     ]
        // );

        // return redirect()->to(role_route('kelas.index', ['tahun_semester_filter' => $tahunSemesterId]))
        return redirect()->to(role_route('kelas.index', ['tahun_ajaran_filter' => $tahunAjaranId]))
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

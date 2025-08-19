<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\GuruKelas;
use App\Models\Kelas;
use App\Models\Mapel;
// use App\Models\TahunSemester;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class MapelKelasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index(Request $request)
    // {
    // $perPage = $request->input('per_page', 10);

    // $kelas = Kelas::findOrFail($kelas);
    // $tahunAktif = TahunSemester::where('is_active', true)->first();

    // // Semua mapel yang sudah dipakai di kelas ini (peran pengajar)
    // $mapelTerpakaiAll = GuruKelas::where('kelas_id', $kelas->id)
    //     ->where('tahun_semester_id', $tahunAktif?->id)
    //     ->where('peran', 'pengajar')
    //     ->pluck('mapel_id')
    //     ->toArray();

    // // Untuk tabel dan modal edit (pakai paginator jika ingin paginasi)
    // $mapelList = GuruKelas::with(['guru', 'mapel'])
    //     ->where('kelas_id', $kelas->id)
    //     ->where('tahun_semester_id', $tahunAktif?->id)
    //     ->where('peran', 'pengajar')
    //     ->paginate($perPage);

    // // Siapkan data untuk row dan modal edit
    // $mapelList->getCollection()->transform(function ($item) use ($mapelTerpakaiAll) {
    //     // Mapel yang belum dipakai selain yang sedang diedit
    //     $mapelTerpakai = array_diff($mapelTerpakaiAll, [$item->mapel_id]);
    //     $mapelSelect = Mapel::whereNotIn('id', $mapelTerpakai)
    //         ->orWhere('id', $item->mapel_id)
    //         ->pluck('nama', 'id')
    //         ->toArray();
    //     return [
    //         'id' => $item->id,
    //         'mapel_id' => $item->mapel_id,
    //         'guru_id' => $item->guru_id,
    //         'nama' => $item->mapel->nama ?? '-',
    //         'guru_nama' => $item->guru->nama ?? '-',
    //         'mapelSelect' => $mapelSelect,
    //     ];
    // });

    // $totalCount = $mapelList->total();

    // // Untuk create: semua mapel yang belum dipakai
    // $mapelSelect = Mapel::whereNotIn('id', $mapelTerpakaiAll)
    //     ->pluck('nama', 'id')
    //     ->toArray();

    // $guruSelect = Guru::pluck('nama', 'id')->toArray();

    // $title = 'Mapel Kelas ' . $kelas->nama;
    // $breadcrumbs = [
    //     ['label' => 'Manage Kelas', 'url' => role_route('kelas.index')],
    //     ['label' => 'Mapel'],
    // ];

    // return view('mapel-kelas.index', compact(
    //     'tahunAktif',
    //     'kelas',
    //     'mapelList',
    //     'title',
    //     'breadcrumbs',
    //     'totalCount',
    //     'guruSelect',
    //     'mapelSelect'
    // ));
    // Ambil tahun ajaran dari filter, jika tidak ada pakai tahun ajaran aktif
    //     $tahunAjaranId = $request->input('tahun_ajaran_filter');
    //     $tahunAjaranAktif = TahunAjaran::where('is_active', true)->first();
    //     if (!$tahunAjaranId) {
    //         $tahunAjaranId = $tahunAjaranAktif?->id;
    //     }
    //     // Ambil semua kelas
    //     $kelasList = Kelas::orderBy('nama')->get();
    //     $mapelList = $item->getMapelByTahunAjaran($tahunAjaranId);
    //     // $guruList = Guru::orderBy('nama')->pluck('nama', 'id')->toArray();
    //     // Filter pagination
    //     $perPage = $request->input('per_page', 10);
    //     $paginator = $query->paginate($perPage)->withQueryString();
    //     // Ambil wali kelas untuk setiap kelas di tahun ajaran aktif
    //     $data = $kelasList->map(function ($kelas) use ($tahunAjaranId) {
    //         // $waliKelas = GuruKelas::where('kelas_id', $kelas->id)
    //         //     ->where('tahun_ajaran_id', $tahunAjaranId)
    //         //     ->where('peran', 'pengajar')
    //         //     ->with('guru')
    //         //     ->first();

    //         // Hitung jumlah mapel aktif di kelas dan tahun ajaran aktif
    //         // $jumlahMapel = GuruKelas::where('kelas_id', $kelas->id)
    //         //     ->where('tahun_ajaran_id', $tahunAjaranId)
    //         //     ->where('mapel_id', '!=', null)
    //         //     ->count();

    //         return [
    //             // 'no' => $index + 1,
    //             'id' => $kelas->id,
    //             'kelas' => $kelas->nama,
    //             // 'wali_kelas' => $waliKelas && $waliKelas->guru ? $waliKelas->guru->nama : '-',
    //             'jumlah_mapel' => $jumlahMapel . ' Mapel',
    //         ];
    //     });
    //     // Ambil semua tahun ajaran untuk select filter (terbaru di atas)
    //     $tahunAjaranCollection = TahunAjaran::orderByDesc('tahun')->get();
    //     // Untuk select filter di toolbar (terbaru di atas)
    //     $tahunAjaranSelect = $tahunAjaranCollection->map(function ($item) use ($tahunAjaranAktif) {
    //         return [
    //             'id' => $item->id,
    //             'name' => $item->tahun . ($item->id == $tahunAjaranAktif?->id ? ' (Aktif)' : ''),
    //         ];
    //     });
    //     $totalCount = $data->count();

    //     $title = 'Daftar Mapel Kelas';
    //     $breadcrumbs = [
    //         ['label' => 'Manage Mapel Kelas'],
    //     ];

    //     return view('mapel-kelas.index', compact(
    //         'title',
    //         'breadcrumbs',
    //         'data',
    //         'tahunAjaranId',
    //         'tahunAjaranAktif',
    //         'tahunAjaranCollection',
    //         'tahunAjaranSelect',
    //         'totalCount',
    //         'kelasList',
    //         'mapelList',
    //         // 'guruList'
    //     ));
    // }
    public function index(Request $request)
    {
        $tahunAjaranId = $request->input('tahun_ajaran_filter');
        $tahunAjaranAktif = TahunAjaran::where('is_active', true)->first();
        if (!$tahunAjaranId) {
            $tahunAjaranId = $tahunAjaranAktif?->id;
        }

        $perPage = $request->input('per_page', 10);

        // Query semua kelas
        $query = Kelas::query()->orderBy('nama');

        $totalCount = $query->count();

        // Paginasi kelas
        $paginator = $query->paginate($perPage)->withQueryString();

        // Transformasi: hanya butuh nama kelas & jumlah mapel
        $kelasList = $paginator->through(function ($kelas) use ($tahunAjaranId) {
            $jumlahMapel = GuruKelas::where('kelas_id', $kelas->id)
                ->where('tahun_ajaran_id', $tahunAjaranId)
                ->where('peran', 'pengajar')
                ->count();

            return [
                'id'           => $kelas->id,
                'kelas'   => $kelas->nama,
                'jumlah_mapel' => $jumlahMapel,
            ];
        });

        // Untuk select filter tahun ajaran
        $tahunAjaranCollection = TahunAjaran::orderByDesc('tahun')->get();
        $tahunAjaranSelect = $tahunAjaranCollection->map(function ($item) use ($tahunAjaranAktif) {
            return [
                'id'   => $item->id,
                'name' => $item->tahun . ($item->id == $tahunAjaranAktif?->id ? ' (Aktif)' : ''),
            ];
        });

        $title = 'Daftar Mapel Kelas';
        $breadcrumbs = [
            ['label' => 'Manage Mapel Kelas'],
        ];

        return view('mapel-kelas.index', compact(
            'kelasList',
            'paginator',
            'totalCount',
            'tahunAjaranId',
            'tahunAjaranAktif',
            'tahunAjaranCollection',
            'tahunAjaranSelect',
            'title',
            'breadcrumbs',
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
        // //     ['label' => 'Mapel', 'url' => role_route('mapel-kelas.index', $kelas->id)],
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

        // return view('mapel-kelas.create', compact(
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
    public function store(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'mapel_id' => 'required|array',
            'mapel_id.*' => 'required|exists:mapel,id',
            'guru_id' => 'required|exists:guru,id',
            'tahun_ajaran_id' => 'required|exists:tahun_ajaran,id',
        ]);

        foreach ($request->mapel_id as $mapelId) {
            GuruKelas::create([
                'kelas_id' => $request->kelas_id,
                'mapel_id' => $mapelId,
                'guru_id' => $request->guru_id,
                'tahun_ajaran_id' => $request->tahun_ajaran_id,
                'peran' => 'pengajar',
            ]);
        }

        return redirect()->to(role_route('mapel-kelas.detail', [
            'kelas' => $request->kelas_id,
            'tahun_ajaran_filter' => $request->tahun_ajaran_id,
        ]))->with('success', 'Mapel berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $perPage = $request->input('per_page', 10);

        $kelas = Kelas::findOrFail($id);

        $tahunAjaranId = $request->input('tahun_ajaran_filter');
        $tahunAjaranAktif = TahunAjaran::where('is_active', true)->first();
        if (!$tahunAjaranId) {
            $tahunAjaranId = $tahunAjaranAktif?->id;
        }

        // Semua mapel yang sudah dipakai di kelas ini (peran pengajar)
        $mapelTerpakaiAll = GuruKelas::where('kelas_id', $kelas->id)
            ->where('tahun_ajaran_id', $tahunAjaranId)
            ->where('peran', 'pengajar')
            ->pluck('mapel_id')
            ->toArray();

        // Paging GuruKelas (mapel kelas)
        $mapelList = GuruKelas::with(['guru', 'mapel'])
            ->where('kelas_id', $kelas->id)
            ->where('tahun_ajaran_id', $tahunAjaranId)
            ->where('peran', 'pengajar')
            ->paginate($perPage)
            ->withQueryString();

        // Transform data untuk row
        $mapelList->getCollection()->transform(function ($item) use ($mapelTerpakaiAll) {
            $mapelTerpakai = array_diff($mapelTerpakaiAll, [$item->mapel_id]);
            $mapelSelect = Mapel::whereNotIn('id', $mapelTerpakai)
                ->orWhere('id', $item->mapel_id)
                ->pluck('nama', 'id')
                ->toArray();
            return [
                'id' => $item->id,
                'mapel_id' => $item->mapel_id,
                'guru_id' => $item->guru_id,
                'mapel' => $item->mapel->nama ?? '-',
                'guru' => $item->guru->nama ?? '-',
                'mapelSelect' => $mapelSelect,
            ];
        });

        $totalCount = $mapelList->total();

        // Untuk create: semua mapel yang belum dipakai
        $mapelSelect = Mapel::whereNotIn('id', $mapelTerpakaiAll)
            ->pluck('nama', 'id')
            ->toArray();

        $guruSelect = Guru::pluck('nama', 'id')->toArray();

        $tahunAjaranCollection = TahunAjaran::orderByDesc('tahun')->get();
        $tahunAjaranSelect = $tahunAjaranCollection->map(function ($item) use ($tahunAjaranAktif) {
            return [
                'id' => $item->id,
                'name' => $item->tahun . ($item->id == $tahunAjaranAktif?->id ? ' (Aktif)' : ''),
            ];
        });

        //edit
        $mapelTerpakai = GuruKelas::where('kelas_id', $kelas->id)
            ->where('tahun_ajaran_id', $tahunAjaranAktif?->id)
            ->pluck('mapel_id')
            ->toArray();

        // Ambil mapel yang BELUM dipakai
        $mapelOptions = Mapel::whereNotIn('id', $mapelTerpakai)
            ->pluck('nama', 'id')
            ->toArray();

        $guruOptions = Guru::pluck('nama', 'id')->toArray();


        $title = 'Mapel Kelas ' . $kelas->nama;
        $breadcrumbs = [
            ['label' => 'Manage Mapel Kelas', 'url' => role_route('mapel-kelas.index')],
            ['label' => 'Mapel'],
        ];

        return view('mapel-kelas.show', compact(
            'kelas',
            'mapelList',
            'title',
            'breadcrumbs',
            'totalCount',
            'guruSelect',
            'mapelSelect',
            'tahunAjaranAktif',
            'tahunAjaranSelect',
            'tahunAjaranCollection',
            'tahunAjaranId',
            'mapelOptions',
            'guruOptions'
        ));
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
        //     ['label' => 'Mapel', 'url' => role_route('mapel-kelas.index', $kelas->id)],
        //     ['label' => 'Edit Mapel'],
        // ];

        // $guruSelect = Guru::pluck('nama', 'id')->toArray();
        // $mapelSelect = Mapel::pluck('nama', 'id')->toArray();

        // return view('mapel-kelas.edit', compact(
        //     'kelas',
        //     'guruKelas',
        //     'title',
        //     'breadcrumbs',
        //     'guruSelect',
        //     'mapelSelect'
        // ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $request->validate([
            'mapel_id' => 'required|exists:mapel,id',
            'guru_id' => 'required|exists:guru,id',
        ]);

        $guruKelas = GuruKelas::findOrFail($request->mapel_id);
        $guruKelas->update([
            'mapel_id' => $request->mapel_id,
            'guru_id' => $request->guru_id,
        ]);

        // return redirect()->route('mapel-kelas.index', $kelas)->with('success', 'Mapel berhasil diperbarui.');
        return redirect()->to(role_route('mapel-kelas.index', [
            'kelas' => $request->kelas_id,
            'tahun_ajaran_filter' => $request->tahun_ajaran_id,
        ]))->with('success', 'Mapel berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $guruKelas = GuruKelas::findOrFail($id);
        $guruKelas->delete();

        // return redirect()->route('mapel-kelas.index', $kelas)->with('success', 'Mapel berhasil dihapus.');
        return redirect()->to(role_route('mapel-kelas.detail', [
            'kelas' => $kelas_id
        ]))->with('success', 'Mapel berhasil dihapus.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\KelasSiswa;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\TahunSemester;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class KelasSiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index(Request $request, string $kelas)
    // {
    //     $kelas = Kelas::findOrFail($kelas);

    //     // Ambil tahun semester dari filter, jika tidak ada pakai tahun aktif
    //     $tahunSemesterId = $request->input('tahun_semester_filter');
    //     $tahunAktif = TahunSemester::where('is_active', true)->first();
    //     if (!$tahunSemesterId) {
    //         $tahunSemesterId = $tahunAktif?->id;
    //     }

    //     $perPage = $request->input('per_page', 10);

    //     // Ambil dan urutkan siswa berdasarkan nama (abjad A-Z)
    //     $query = KelasSiswa::with('siswa')
    //         ->where('kelas_id', $kelas->id)
    //         ->where('tahun_semester_id', $tahunSemesterId)
    //         ->get();

    //     // Cek apakah semua `no_absen` null atau kosong
    //     $semuaKosong = $query->every(fn($ks) => empty($ks->no_absen));

    //     // Urutkan sesuai kondisi
    //     $query = $query->sortBy(function ($ks) use ($semuaKosong) {
    //         return $semuaKosong
    //             ? strtolower($ks->siswa->nama)
    //             : $ks->no_absen ?? PHP_INT_MAX;
    //     })->values();

    //     $paginator = new LengthAwarePaginator(
    //         $query->forPage($request->input('page', 1), $perPage),
    //         $query->count(),
    //         $perPage,
    //         $request->input('page', 1),
    //         ['path' => $request->url(), 'query' => $request->query()]
    //     );

    //     $siswa = $paginator->getCollection()->map(function ($item) {
    //         return [
    //             'id' => $item->id,
    //             'siswa_id' => $item->siswa->id ?? null,
    //             'nama' => $item->siswa->nama ?? '-',
    //             'nipd' => $item->siswa->nipd ?? '-',
    //             'nisn' => $item->siswa->nisn ?? '-',
    //             'no_absen' => $item->no_absen ?? '-',
    //         ];
    //     });
    //     $paginator->setCollection($siswa);

    //     $totalCount = $paginator->total();

    //     // Ambil siswa yang belum terdaftar di kelas_siswa tahun semester ini
    //     $siswaTerpakai = KelasSiswa::where('tahun_semester_id', $tahunSemesterId)
    //         ->pluck('siswa_id')
    //         ->toArray();

    //     $siswaOptions = Siswa::whereNotIn('id', $siswaTerpakai)
    //         ->pluck('nama', 'id')
    //         ->toArray();

    //     $kelasList = Kelas::pluck('nama', 'id')->toArray();

    //     // Ambil semua tahun semester untuk select filter (terbaru di atas)
    //     $tahunSemesterCollection = TahunSemester::orderByDesc('tahun')
    //         ->orderByDesc('semester')
    //         ->get();

    //     // Untuk select filter di toolbar (terbaru di atas)
    //     $tahunSemesterSelect = $tahunSemesterCollection->map(function ($item) use ($tahunAktif) {
    //         return [
    //             'id' => $item->id,
    //             'name' => $item->tahun . ' - ' . ucfirst($item->semester) . ($item->id == $tahunAktif?->id ? ' (Tahun Aktif)' : ''),
    //         ];
    //     });

    //     // Untuk select tahun semester di form promote (hilangkan tahun yang sedang digunakan/dibuka, terbaru di atas)
    //     $tahunSemesterList = TahunSemester::where('id', '!=', $tahunSemesterId)
    //         ->orderByDesc('tahun')
    //         ->orderByDesc('semester')
    //         ->get()
    //         ->map(function ($ts) use ($tahunAktif) {
    //             return [
    //                 'id' => $ts->id,
    //                 'name' => $ts->tahun . ' - ' . ucfirst($ts->semester) . ($ts->id == $tahunAktif?->id ? ' (Tahun Aktif)' : ''),
    //             ];
    //         })
    //         ->values();

    //     $title = 'Siswa Kelas ' . $kelas->nama;
    //     $breadcrumbs = [
    //         ['label' => 'Manage Kelas', 'url' => role_route('kelas.index')],
    //         ['label' => 'Siswa'],
    //     ];

    //     return view('kelas.siswa.index', compact(
    //         'kelas',
    //         'title',
    //         'breadcrumbs',
    //         'paginator',
    //         'totalCount',
    //         'tahunAktif',
    //         'siswa',
    //         'siswaOptions',
    //         'kelasList',
    //         'tahunSemesterCollection',
    //         'tahunSemesterList',
    //         'tahunSemesterSelect',
    //         'tahunSemesterId'
    //     ));
    // }

    
    public function index(Request $request, string $kelas)
    {
        $kelas = Kelas::findOrFail($kelas);

        // Ambil tahun ajaran dari filter, jika tidak ada pakai tahun ajaran aktif
        $tahunAjaranId = $request->input('tahun_ajaran_filter');
        $tahunAjaranAktif = TahunAjaran::where('is_active', true)->first();
        if (!$tahunAjaranId) {
            $tahunAjaranId = $tahunAjaranAktif?->id;
        }

        $perPage = $request->input('per_page', 10);

        // Ambil dan urutkan siswa berdasarkan nama (abjad A-Z), hanya yang status Aktif
        $query = KelasSiswa::with('siswa')
            ->where('kelas_id', $kelas->id)
            ->where('tahun_ajaran_id', $tahunAjaranId)
            ->where('status', 'Aktif')
            ->get();

        // Cek apakah semua `no_absen` null atau kosong
        $semuaKosong = $query->every(fn($ks) => empty($ks->no_absen));

        // Urutkan sesuai kondisi
        $query = $query->sortBy(function ($ks) use ($semuaKosong) {
            return $semuaKosong
                ? strtolower($ks->siswa->nama)
                : $ks->no_absen ?? PHP_INT_MAX;
        })->values();

        $paginator = new LengthAwarePaginator(
            $query->forPage($request->input('page', 1), $perPage),
            $query->count(),
            $perPage,
            $request->input('page', 1),
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $siswa = $paginator->getCollection()->map(function ($item) {
            return [
                'id' => $item->id,
                'siswa_id' => $item->siswa->id ?? null,
                'nama' => $item->siswa->nama ?? '-',
                'nipd' => $item->siswa->nipd ?? '-',
                'nisn' => $item->siswa->nisn ?? '-',
                'no_absen' => $item->no_absen ?? '-',
            ];
        });
        $paginator->setCollection($siswa);

        $totalCount = $paginator->total();

        // Ambil siswa yang belum terdaftar di kelas_siswa tahun ajaran ini
        $siswaTerpakai = KelasSiswa::where('tahun_ajaran_id', $tahunAjaranId)
            ->pluck('siswa_id')
            ->toArray();

        $siswaOptions = Siswa::whereNotIn('id', $siswaTerpakai)
            ->where('status', 'Aktif')
            ->pluck('nama', 'id')
            ->toArray();

        $kelasList = Kelas::pluck('nama', 'id')->toArray();

        // Ambil semua tahun ajaran untuk select filter (terbaru di atas)
        $tahunAjaranCollection = TahunAjaran::orderByDesc('mulai')->get();

        // Untuk select filter di toolbar (terbaru di atas)
        $tahunAjaranSelect = $tahunAjaranCollection->map(function ($item) use ($tahunAjaranAktif) {
            return [
                'id' => $item->id,
                'name' => $item->tahun . ($item->id == $tahunAjaranAktif?->id ? ' (Aktif)' : ''),
            ];
        });

        $title = 'Siswa Kelas ' . $kelas->nama;
        $breadcrumbs = [
            ['label' => 'Manage Kelas', 'url' => role_route('kelas.index')],
            ['label' => 'Siswa'],
        ];

        return view('kelas.siswa.index', compact(
            'kelas',
            'title',
            'breadcrumbs',
            'paginator',
            'totalCount',
            'tahunAjaranAktif',
            'siswa',
            'siswaOptions',
            'kelasList',
            'tahunAjaranCollection',
            'tahunAjaranSelect',
            'tahunAjaranId'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request, string $kelas)
    {
        // $kelas = Kelas::findOrFail($kelas);
        // $tahunSemesterId = $request->input('tahun_semester_filter');
        // $tahunAktif = TahunSemester::where('is_active', true)->first();
        // if (!$tahunSemesterId) {
        //     $tahunSemesterId = $tahunAktif?->id;
        // }

        // $siswaTerpakai = KelasSiswa::where('tahun_semester_id', $tahunSemesterId)
        //     ->pluck('siswa_id')
        //     ->toArray();

        // $siswaOptions = Siswa::where('status', 'Aktif')
        //     ->whereNotIn('id', $siswaTerpakai)
        //     ->pluck('nama', 'id')
        //     ->toArray();

        // if (empty($siswaOptions)) {
        //     return redirect('/siswa')->with('warning', 'Tidak ada siswa yang tersedia untuk ditambahkan. Silakan tambah data siswa terlebih dahulu.');
        // }

        // $title = 'Tambah Siswa ke Kelas ' . $kelas->nama;
        // $breadcrumbs = [
        //     ['label' => 'Kelas', 'url' => role_route('kelas.index')],
        //     ['label' => 'Siswa', 'url' => role_route('kelas.siswa.index', $kelas->id)],
        //     ['label' => 'Tambah Siswa'],
        // ];

        // return view('kelas.siswa.create', compact('kelas', 'title', 'breadcrumbs', 'siswaOptions', 'tahunSemesterId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, string $kelas)
    {
        $request->validate([
            'siswa_id' => 'required|array',
            'siswa_id.*' => 'required|exists:siswa,id',
            'tahun_semester_id' => 'required|exists:tahun_semester,id',
        ]);

        $tahunSemesterId = $request->input('tahun_semester_id');

        $siswaTersimpan = [];
        foreach ($request->siswa_id as $siswaId) {
            $sudahAda = KelasSiswa::where('siswa_id', $siswaId)
                ->where('tahun_semester_id', $tahunSemesterId)
                ->exists();

            if ($sudahAda) {
                $siswaTersimpan[] = $siswaId;
                continue;
            }

            KelasSiswa::create([
                'kelas_id' => $kelas,
                'siswa_id' => $siswaId,
                'tahun_semester_id' => $tahunSemesterId,
                'no_absen' => null,
                'status' => 'Aktif'
            ]);
        }

        if (count($siswaTersimpan) > 0) {
            $namaSiswa = Siswa::whereIn('id', $siswaTersimpan)->pluck('nama')->toArray();
            return redirect()->to(role_route('kelas.siswa.index', ['kelas' => $kelas, 'tahun_semester_filter' => $tahunSemesterId]))
                ->with('warning', 'Beberapa siswa tidak ditambahkan karena sudah terdaftar di kelas lain: ' . implode(', ', $namaSiswa));
        }

        return redirect()->to(role_route('kelas.siswa.index', ['kelas' => $kelas, 'tahun_semester_filter' => $tahunSemesterId]))
            ->with('success', 'Siswa berhasil ditambahkan ke kelas.');
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
    public function edit(Request $request, string $kelas, string $id)
    {
        $kelas = Kelas::findOrFail($kelas);
        $kelasSiswa = KelasSiswa::with('siswa')->findOrFail($id);
        $tahunSemesterId = $request->input('tahun_semester_filter');

        return view('kelas.siswa.edit', [
            'kelasSiswa' => $kelasSiswa,
            'kelas' => $kelas,
            'tahunSemesterId' => $tahunSemesterId,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $kelas, string $id)
    {
        $request->validate([
            'no_absen' => 'nullable|integer|min:1'
        ]);

        $kelasSiswa = KelasSiswa::findOrFail($id);
        $kelasSiswa->no_absen = $request->no_absen;
        $kelasSiswa->save();

        $tahunSemesterId = $request->input('tahun_semester_id');

        return redirect()->to(role_route('kelas.siswa.index', ['kelas' => $kelas, 'tahun_semester_filter' => $tahunSemesterId]))
            ->with('success', 'Nomor absen berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $kelas, string $id)
    {
        $kelasSiswa = KelasSiswa::findOrFail($id);
        $kelasSiswa->delete();

        return redirect()->to(role_route('kelas.siswa.index', ['kelas' => $kelas]))->with('success', 'Siswa berhasil dihapus dari kelas.');
    }

    public function generateAbsen(Request $request, $kelas_id)
    {
        // Ambil tahun semester dari filter, jika tidak ada pakai tahun aktif
        $tahunSemesterId = $request->input('tahun_semester_filter');
        $tahunAktif = TahunSemester::where('is_active', true)->first();
        if (!$tahunSemesterId) {
            $tahunSemesterId = $tahunAktif?->id;
        }

        $kelasSiswa = KelasSiswa::with('siswa')
            ->where('kelas_id', $kelas_id)
            ->where('tahun_semester_id', $tahunSemesterId)
            ->get()
            ->sortBy(fn($ks) => strtolower($ks->siswa->nama))
            ->values();

        foreach ($kelasSiswa as $index => $ks) {
            $ksBaru = KelasSiswa::find($ks->id);
            $ksBaru->no_absen = $index + 1;
            $ksBaru->save();
        }

        return redirect()->to(role_route('kelas.siswa.index', [
            'kelas' => $kelas_id,
            'tahun_semester_filter' => $tahunSemesterId,
        ]))->with('success', 'Nomor absen berhasil diurutkan berdasarkan nama.');
    }

    public function promote(Request $request)
    {
        $request->validate([
            'kelas_lama_id' => 'required|exists:kelas,id',
            'kelas_baru_id' => 'required|exists:kelas,id',
            'tahun_baru_id' => 'required|exists:tahun_semester,id',
        ]);

        // $tahunLama = TahunSemester::where('is_active', true)->first();
        $tahunLamaId = $request->input('tahun_lama_id') ?? TahunSemester::where('is_active', true)->first()?->id;
        $siswaList = KelasSiswa::where('kelas_id', $request->kelas_lama_id)
            ->where('tahun_semester_id', $tahunLamaId)
            ->get();

        foreach ($siswaList as $entry) {
            // Cek apakah siswa sudah pernah dipromosikan ke tahun baru
            $sudahAda = KelasSiswa::where('siswa_id', $entry->siswa_id)
                ->where('tahun_semester_id', $request->tahun_baru_id)
                ->exists();

            if (!$sudahAda) {
                KelasSiswa::create([
                    'siswa_id' => $entry->siswa_id,
                    'kelas_id' => $request->kelas_baru_id,
                    'tahun_semester_id' => $request->tahun_baru_id,
                    'no_absen' => null,
                ]);
            }
        }

        return redirect()->to(role_route('kelas.siswa.index', [
            'kelas' => $request->kelas_baru_id,
            'tahun_semester_filter' => $request->tahun_baru_id,
        ]))->with('success', 'Siswa berhasil dipindahkan ke kelas baru.');
    }
}

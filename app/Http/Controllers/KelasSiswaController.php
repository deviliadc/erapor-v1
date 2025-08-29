<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\GuruKelas;
use App\Models\KelasSiswa;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Models\TahunSemester;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
// No explicit imports for collect, view, auth needed in Laravel controller

class KelasSiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $tahunAjaranId = $request->input('tahun_ajaran_filter');
        $tahunAjaranAktif = TahunAjaran::where('is_active', true)->first();
        $user = auth()->user();
        if ($user->hasRole('guru')) {
            $guruId = $user->guru?->id;
            $tahunAjaranGuruIds = GuruKelas::where('guru_id', $guruId)
                ->whereIn('peran', ['wali', 'pengajar'])
                ->pluck('tahun_ajaran_id')
                ->unique()
                ->toArray();
            if (empty($tahunAjaranGuruIds)) {
                $tahunAjaranCollection = collect();
                $tahunAjaranSelect = collect();
                $kelas = collect();
                $totalCount = 0;
                $guruList = ['' => ''];
                $kelasList = collect();
                $title = 'Daftar Kelas Siswa';
                $breadcrumbs = [
                    ['label' => 'Manage Kelas Siswa'],
                ];
                return view('kelas-siswa.index', compact(
                    'title',
                    'breadcrumbs',
                    'kelas',
                    'totalCount',
                    'tahunAjaranId',
                    'tahunAjaranAktif',
                    'tahunAjaranCollection',
                    'tahunAjaranSelect',
                    'guruList',
                    'kelasList'
                ));
            }
            if (!$tahunAjaranId || !in_array($tahunAjaranId, $tahunAjaranGuruIds)) {
                $tahunAjaranId = $tahunAjaranAktif?->id;
            }
            $kelasGuruIds = GuruKelas::where('guru_id', $guruId)
                ->whereIn('peran', ['wali', 'pengajar'])
                ->where('tahun_ajaran_id', $tahunAjaranId)
                ->pluck('kelas_id')
                ->unique()
                ->toArray();
            $tahunAjaranCollection = TahunAjaran::whereIn('id', $tahunAjaranGuruIds)->orderByDesc('tahun')->get();
            $tahunAjaranSelect = $tahunAjaranCollection->map(function ($item) use ($tahunAjaranAktif) {
                return [
                    'id' => $item->id,
                    'name' => $item->tahun . ($item->id == $tahunAjaranAktif?->id ? ' (Aktif)' : ''),
                ];
            });
            $query = Kelas::whereIn('id', $kelasGuruIds)->orderBy('nama');
        } else {
            if (!$tahunAjaranId) {
                $tahunAjaranId = $tahunAjaranAktif?->id;
            }
            $query = Kelas::orderBy('nama');
            $tahunAjaranCollection = TahunAjaran::orderByDesc('tahun')->get();
            $tahunAjaranSelect = $tahunAjaranCollection->map(function ($item) use ($tahunAjaranAktif) {
                return [
                    'id' => $item->id,
                    'name' => $item->tahun . ($item->id == $tahunAjaranAktif?->id ? ' (Aktif)' : ''),
                ];
            });
        }
        $totalCount = $query->count();
        $paginator = $query->paginate($perPage)->withQueryString();
        $kelas = $paginator->through(function ($item) use ($tahunAjaranId) {
            $waliKelas = GuruKelas::where('kelas_id', $item->id)
                ->where('tahun_ajaran_id', $tahunAjaranId)
                ->where('peran', 'wali')
                ->with('guru')
                ->first();
            $jumlahSiswa = KelasSiswa::where('kelas_id', $item->id)
                ->where('tahun_ajaran_id', $tahunAjaranId)
                ->where('status', 'Aktif')
                ->count();
            return [
                'id' => $item->id,
                'kelas' => $item->nama,
                'wali_kelas' => $waliKelas && $waliKelas->guru ? $waliKelas->guru->nama : '-',
                'jumlah_siswa' => $jumlahSiswa . ' Siswa',
            ];
        });
        if ($user->hasRole('guru')) {
            $kelasList = Kelas::whereIn('id', GuruKelas::where('guru_id', $guruId)->where('peran', 'wali')->pluck('kelas_id'))->orderBy('nama')->get();
        } else {
            $kelasList = Kelas::orderBy('nama')->get();
        }
        $guruList = ['' => ''] + Guru::orderBy('nama')->pluck('nama', 'id')->toArray();
        $title = 'Daftar Kelas Siswa';
        $breadcrumbs = [
            ['label' => 'Manage Kelas Siswa'],
        ];
        return view('kelas-siswa.index', compact(
            'title',
            'breadcrumbs',
            'kelas',
            'totalCount',
            'tahunAjaranId',
            'tahunAjaranAktif',
            'tahunAjaranCollection',
            'tahunAjaranSelect',
            'guruList',
            'kelasList'
        ));
    }
// End of index method



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
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'siswa_id' => 'required|array',
            'siswa_id.*' => 'required|exists:siswa,id',
            // 'tahun_semester_id' => 'required|exists:tahun_semester,id',
            'tahun_ajaran_id' => 'required|exists:tahun_ajaran,id',
        ]);

        $kelas = $request->kelas_id;
        // $tahunSemesterId = $request->input('tahun_semester_id');
        $tahunAjaranId = $request->input('tahun_ajaran_id');

        $siswaTersimpan = [];
        foreach ($request->siswa_id as $siswaId) {
            $sudahAda = KelasSiswa::where('siswa_id', $siswaId)
                // ->where('tahun_ajaran_id', $tahunAjaranId)
                ->where('kelas_id', $request->kelas_baru_id)
                ->exists();

            if ($sudahAda) {
                $siswaTersimpan[] = $siswaId;
                continue;
            }

            KelasSiswa::create([
                'kelas_id' => $kelas,
                'siswa_id' => $siswaId,
                'tahun_ajaran_id' => $tahunAjaranId,
                'no_absen' => null,
                'status' => 'Aktif'
            ]);
        }

        if (count($siswaTersimpan) > 0) {
            $namaSiswa = Siswa::whereIn('id', $siswaTersimpan)->pluck('nama')->toArray();
            return redirect()->to(role_route('kelas-siswa.detail', ['kelas' => $kelas, 'tahun_ajaran_filter' => $tahunAjaranId]))
                ->with('warning', 'Beberapa siswa tidak ditambahkan karena sudah terdaftar di kelas lain: ' . implode(', ', $namaSiswa));
        }

        // return redirect()->to(role_route('kelas-siswa.show', ['kelas' => $kelas, 'tahun_ajaran_filter' => $tahunAjaranId]))
        //     ->with('success', 'Siswa berhasil ditambahkan ke kelas.');
        return redirect()->to(role_route('kelas-siswa.detail', [
            'kelas' => $kelas,
            'tahun_ajaran_filter' => $tahunAjaranId
        ]))->with('success', 'Siswa berhasil ditambahkan ke kelas.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $kelas = Kelas::findOrFail($id);

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
            ->orderByRaw('COALESCE(no_absen, 99999), siswa_id') // urutkan absen, lalu nama
            ->paginate($perPage)
            ->withQueryString();

        // Mapping data siswa untuk table
        $siswa = $query->getCollection()->map(function ($item) {
            return [
                'id' => $item->id,
                'siswa_id' => $item->siswa->id ?? null,
                'nama' => $item->siswa->nama ?? '-',
                'nipd' => $item->siswa->nipd ?? '-',
                'nisn' => $item->siswa->nisn ?? '-',
                'no_absen' => $item->no_absen ?? '-',
            ];
        });
        $query->setCollection($siswa);

        $totalCount = $query->total();

        // Ambil siswa yang belum terdaftar di kelas_siswa tahun ajaran ini
        $siswaTerpakai = KelasSiswa::where('tahun_ajaran_id', $tahunAjaranId)
            ->pluck('siswa_id')
            ->toArray();

        $siswaOptions = Siswa::with('kelasSiswa')->whereNotIn('id', $siswaTerpakai)
            ->pluck('nama', 'id')
            ->toArray();

        $kelasList = Kelas::orderBy('nama')->get();

        // Filter kelas agar sesuai aturan promote
        $kelasList = $kelasList->filter(function ($k) use ($kelas, $siswa) {
            if ($k->id < $kelas->id) {
                return false;
            }
            foreach ($siswa as $s) {
                $pernah = KelasSiswa::where('siswa_id', $s['siswa_id'])
                    ->where('kelas_id', $k->id)
                    ->exists();
                if ($pernah) {
                    return false;
                }
            }
            return true;
        })->pluck('nama', 'id')->toArray();

        $user = auth()->user();
        $isGuru = $user->hasRole('guru');
        if ($isGuru) {
            $guruId = $user->guru?->id;
            $tahunAjaranGuruIds = \App\Models\GuruKelas::where('guru_id', $guruId)
                ->where('kelas_id', $kelas->id)
                ->where('peran', 'wali')
                ->pluck('tahun_ajaran_id')
                ->unique()
                ->toArray();
            $tahunAjaranCollection = \App\Models\TahunAjaran::whereIn('id', $tahunAjaranGuruIds)->orderByDesc('tahun')->get();
        } else {
            $tahunAjaranCollection = \App\Models\TahunAjaran::orderByDesc('tahun')->get();
        }
        $tahunAjaranSelect = $tahunAjaranCollection->map(function ($item) use ($tahunAjaranAktif) {
            return [
                'id' => $item->id,
                'name' => $item->tahun . ($item->id == $tahunAjaranAktif?->id ? ' (Aktif)' : ''),
            ];
        });

        $tahunAjaranOptions = $tahunAjaranCollection->mapWithKeys(function ($item) use ($tahunAjaranAktif) {
            return [
                $item->id => $item->tahun . ($item->id == $tahunAjaranAktif?->id ? ' (Aktif)' : ''),
            ];
        });

        $user = auth()->user();
        $isAdmin = $user->hasRole('admin');
        $isGuru = $user->hasRole('guru');
        $isWaliKelas = false;
        if ($isGuru) {
            $guruId = $user->guru?->id;
            $isWaliKelas = \App\Models\GuruKelas::where('guru_id', $guruId)
                ->where('kelas_id', $kelas->id)
                ->where('tahun_ajaran_id', $tahunAjaranId)
                ->where('peran', 'wali')
                ->exists();
        }

        $canAddSiswa = $isAdmin || $isWaliKelas;

        $title = 'Siswa Kelas ' . $kelas->nama;
        $breadcrumbs = [
            ['label' => 'Kelas Siswa', 'url' => role_route('kelas-siswa.index')],
            ['label' => 'Siswa'],
        ];

        return view('kelas-siswa.show', compact(
            'kelas',
            'title',
            'breadcrumbs',
            'query', // paginator hasil mapping
            'totalCount',
            'tahunAjaranAktif',
            'siswa',
            'siswaOptions',
            'kelasList',
            'tahunAjaranCollection',
            'tahunAjaranSelect',
            'tahunAjaranId',
            'tahunAjaranOptions',
            'canAddSiswa'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'no_absen' => 'nullable|integer|min:1'
        ]);

        $kelasSiswa = KelasSiswa::findOrFail($id);
        $kelasSiswa->no_absen = $request->no_absen;
        $kelasSiswa->save();

        $tahunAjaranId = $request->input('tahun_ajaran_filter');
        return redirect()->to(role_route('kelas-siswa.detail', [
            'kelas' =>  $kelasSiswa->kelas_id,
            'tahun_ajaran_filter' => $tahunAjaranId
        ]))->with('success', 'Nomor absen berhasil diperbarui.');
    }

    public function updateWali(Request $request, $kelas_id)
    {
        $request->validate([
            'guru_id' => 'required|exists:guru,id',
            'tahun_ajaran_id' => 'required|exists:tahun_ajaran,id',
        ]);

        // Update atau buat GuruKelas sebagai wali kelas
        GuruKelas::updateOrCreate(
            [
                'kelas_id' => $kelas_id,
                'tahun_ajaran_id' => $request->tahun_ajaran_id,
                'peran' => 'wali',
            ],
            [
                'guru_id' => $request->guru_id,
                'mapel_id' => null,
            ]
        );

        return redirect()->to(role_route('kelas-siswa.index', [
            'tahun_ajaran_filter' => $request->tahun_ajaran_id
        ]))->with('success', 'Wali kelas berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $kelasSiswa = KelasSiswa::findOrFail($id);
        $kelas_id = $kelasSiswa->kelas_id;
        $kelasSiswa->delete();

        return redirect()->to(role_route('kelas-siswa.detail', [
            'kelas' => $kelas_id,
            'tahun_ajaran_filter' => $kelasSiswa->tahun_ajaran_id
        ]))->with('success', 'Siswa berhasil dihapus dari kelas.');
    }

    public function generateAbsen(Request $request, $kelas_id)
    {
        $tahunAjaranId = $request->input('tahun_ajaran_filter');
        $tahunAktif = TahunAjaran::where('is_active', true)->first();
        if (!$tahunAjaranId) {
            $tahunAjaranId = $tahunAktif?->id;
        }

        $kelasSiswa = KelasSiswa::with('siswa')
            ->where('kelas_id', $kelas_id)
            ->where('tahun_ajaran_id', $tahunAjaranId)
            ->get()
            ->sortBy(fn($ks) => strtolower($ks->siswa->nama))
            ->values();

        foreach ($kelasSiswa as $index => $ks) {
            $ksBaru = KelasSiswa::find($ks->id);
            $ksBaru->no_absen = $index + 1;
            $ksBaru->save();
        }

        return redirect()->to(role_route('kelas-siswa.detail', [
            'kelas' => $kelas_id,
            'tahun_ajaran_filter' => $tahunAjaranId,
        ]))->with('success', 'Nomor absen berhasil diurutkan berdasarkan nama.');
    }

    public function promote(Request $request)
    {
        $request->validate([
            'kelas_lama_id' => 'required|exists:kelas,id',
            'tahun_lama_id' => 'required|exists:tahun_ajaran,id',
            // 'kelas_baru_id' => 'nullable|exists:kelas,id', // tidak perlu, sistem tentukan otomatis
            // 'tahun_baru_id' => 'nullable|exists:tahun_ajaran,id', // sistem tentukan otomatis
            'siswa_id' => 'nullable|array',
            'siswa_id.*' => 'exists:siswa,id',
        ]);

        $kelasLama = Kelas::findOrFail($request->kelas_lama_id);
        $tahunLama = TahunAjaran::findOrFail($request->tahun_lama_id);

        // Tentukan tahun ajaran baru (next year)
        $tahunBaru = TahunAjaran::where('tahun', $tahunLama->tahun + 1)->first();
        if (!$tahunBaru) {
            $tahunBaru = TahunAjaran::create([
                'tahun' => $tahunLama->tahun + 1,
                'is_active' => false,
            ]);
        }

        // Ambil siswa yang dipilih, jika tidak pilih, ambil semua siswa aktif di kelas lama
        $siswaQuery = KelasSiswa::where('kelas_id', $kelasLama->id)
            ->where('tahun_ajaran_id', $tahunLama->id)
            ->where('status', 'Aktif');

        if ($request->filled('siswa_id')) {
            $siswaQuery->whereIn('siswa_id', $request->siswa_id);
        }

        $siswaList = $siswaQuery->get();

        // Kelas terakhir (misal kelas 6)
        $kelasTerakhir = Kelas::orderByDesc('id')->first();
        $isKelasTerakhir = $kelasLama->id == $kelasTerakhir->id;

    $promoted = [];
    $lulus = [];
    $gagalPromote = [];

        foreach ($siswaList as $entry) {
            // Cek apakah siswa sudah pernah di kelas tujuan di tahun ajaran manapun
            $sudahPernah = KelasSiswa::where('siswa_id', $entry->siswa_id)
                ->where('kelas_id', '>=', $kelasLama->id) // hanya kelas sama atau lebih tinggi
                ->exists();

            // Tidak bisa turun kelas
            if ($kelasLama->id < $kelasTerakhir->id && $sudahPernah) {
                $gagalPromote[] = $entry->siswa->nama;
                continue;
            }

            if ($isKelasTerakhir) {
                // Luluskan siswa
                $entry->status = 'Lulus';
                $entry->save();
                $lulus[] = $entry->siswa->nama;
            } else {
                // Naikkan kelas (id kelas + 1)
                $kelasBaru = Kelas::where('id', $kelasLama->id + 1)->first();
                if (!$kelasBaru) continue;

                // Cek duplikat di tahun ajaran baru
                $sudahAda = KelasSiswa::where('siswa_id', $entry->siswa_id)
                    ->where('kelas_id', $kelasBaru->id)
                    ->where('tahun_ajaran_id', $tahunBaru->id)
                    ->exists();

                if (!$sudahAda) {
                    KelasSiswa::create([
                        'siswa_id' => $entry->siswa_id,
                        'kelas_id' => $kelasBaru->id,
                        'tahun_ajaran_id' => $tahunBaru->id,
                        'no_absen' => null,
                        'status' => 'Aktif',
                    ]);
                    $promoted[] = $entry->siswa->nama;
                } else {
                    $gagalPromote[] = $entry->siswa->nama;
                }
            }
        }

        $msg = [];
        if ($promoted) $msg[] = 'Siswa dipromosikan: ' . implode(', ', $promoted);
        if ($lulus) $msg[] = 'Siswa diluluskan: ' . implode(', ', $lulus);

        $redirect = redirect()->to(role_route('kelas-siswa.index', [
            'tahun_ajaran_filter' => $tahunBaru->id,
        ]));

        if ($gagalPromote) {
            $redirect = $redirect->with('warning', 'Siswa berikut sudah ada di kelas tujuan: ' . implode(', ', $gagalPromote));
        }

        return $redirect->with('success', implode(' | ', $msg));
    }


    public function promoteGlobal(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required|array',
            'kelas_id.*' => 'exists:kelas,id',
        ]);

        // Tambahkan ini di awal!
        $tahunAjaranAktif = TahunAjaran::where('is_active', true)->first();
        if (!$tahunAjaranAktif) {
            return back()->with('error', 'Tahun ajaran aktif tidak ditemukan.');
        }

        $nextTahun = nextTahunAjaran($tahunAjaranAktif->tahun);
        $tahunAjaranBaru = TahunAjaran::where('tahun', $nextTahun)->first();
        if (!$tahunAjaranBaru) {
            $tahunAjaranBaru = TahunAjaran::create([
                'tahun' => $nextTahun,
                'is_active' => false,
            ]);
        }

        $kelasTerakhir = Kelas::orderByDesc('id')->first();
        $promoted = [];
        $lulus = [];

        foreach ($request->kelas_id as $kelasId) {
            $kelas = Kelas::find($kelasId);

            // Ambil semua siswa aktif di kelas ini
            $siswaList = KelasSiswa::where('kelas_id', $kelasId)
                ->where('tahun_ajaran_id', $tahunAjaranAktif->id)
                ->where('status', 'Aktif')
                ->get();

            foreach ($siswaList as $entry) {
                if ($kelasId == $kelasTerakhir->id) {
                    // Kelas terakhir, luluskan siswa
                    $entry->status = 'Lulus';
                    $entry->save();
                    $lulus[] = $entry->siswa->nama . ' (' . $kelas->nama . ')';
                } else {
                    // Naikkan kelas (id + 1)
                    $kelasBaru = Kelas::where('id', $kelasId + 1)->first();
                    if (!$kelasBaru) continue;

                    // Cek duplikat di tahun ajaran baru
                    $sudahAda = KelasSiswa::where('siswa_id', $entry->siswa_id)
                        ->where('kelas_id', $kelasBaru->id)
                        ->where('tahun_ajaran_id', $tahunAjaranBaru->id)
                        ->exists();

                    // Tidak bisa pilih kelas yang sudah pernah ditempati siswa di tahun ajaran manapun
                    $pernah = KelasSiswa::where('siswa_id', $entry->siswa_id)
                        ->where('kelas_id', $kelasBaru->id)
                        ->exists();

                    if (!$sudahAda && !$pernah) {
                        KelasSiswa::create([
                            'siswa_id' => $entry->siswa_id,
                            'kelas_id' => $kelasBaru->id,
                            'tahun_ajaran_id' => $tahunAjaranBaru->id,
                            'no_absen' => null,
                            'status' => 'Aktif',
                        ]);
                        $promoted[] = $entry->siswa->nama . ' (' . $kelas->nama . ' → ' . $kelasBaru->nama . ')';
                    }
                }
            }
        }

        $msg = [];
        if ($promoted) $msg[] = 'Dipromosikan: ' . implode(', ', $promoted);
        if ($lulus) $msg[] = 'Diluluskan: ' . implode(', ', $lulus);

        return redirect()->to(role_route('kelas-siswa.index', [
            'tahun_ajaran_filter' => $tahunAjaranBaru->id,
        ]))->with('success', implode(' | ', $msg));
    }
}

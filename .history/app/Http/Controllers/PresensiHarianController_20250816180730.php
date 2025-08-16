<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\KelasSiswa;
use App\Models\GuruKelas;
use App\Models\PresensiDetail;
use App\Models\PresensiHarian;
use App\Models\TahunAjaran;
use App\Models\TahunSemester;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PresensiHarianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
//     public function index(Request $request)
//     {
//         // Ambil tahun ajaran aktif
//         $tahunAktif = TahunSemester::where('is_active', true)->first();
//         // Jika user memilih tahun, pakai itu, jika tidak pakai tahun aktif
//         $selectedTahun = $request->input('tahun_semester_id', $tahunAktif?->id);

//         $paginator = PresensiHarian::with('kelas')
//             // ->when($selectedTahun, function ($query) use ($selectedTahun) {
//             //     $query->whereHas('detail.kelasSiswa', function ($q) use ($selectedTahun) {
//             //         $q->where('tahun_semester_id', $selectedTahun);
//             //     });
//             // })
//             ->when($selectedTahun, function ($query) use ($selectedTahun) {
//     $tahunSemester = TahunSemester::find($selectedTahun);
//     $tahunAjaranId = $tahunSemester ? $tahunSemester->tahun_ajaran_id : null;
//     if ($tahunAjaranId) {
//         $query->whereHas('detail.kelasSiswa', function ($q) use ($tahunAjaranId) {
//             $q->where('tahun_ajaran_id', $tahunAjaranId);
//         });
//     }
// })
//             ->orderByDesc('tanggal')
//             ->paginate(10)
//             ->withQueryString();

//         $data = $paginator->getCollection()->map(function ($item) {
//             return [
//                 'id' => $item->id,
//                 'tanggal' => $item->tanggal,
//                 'kelas' => $item->kelas->nama ?? '-',
//                 'catatan' => $item->catatan ?? '-',
//             ];
//         });
//         $paginator->setCollection($data);

//         $tahun_semester = TahunSemester::orderByDesc('tahun')->get();

//         $breadcrumbs = [
//             ['label' => 'Presensi Harian'],
//         ];
//         $title = 'Presensi Harian';

//         return view('presensi-harian.index', [
//             'data' => $paginator,
//             'totalCount' => $paginator->total(),
//             'breadcrumbs' => $breadcrumbs,
//             'title' => $title,
//             'tahun_semester' => $tahun_semester,
//             'selectedTahun' => $selectedTahun,
//         ]);
//     }

public function index(Request $request)
{
    // Ambil tahun ajaran aktif dari tahun semester aktif
    $tahunSemesterAktif = TahunSemester::where('is_active', true)->first();
    $tahunAjaranAktifId = $tahunSemesterAktif ? $tahunSemesterAktif->tahun_ajaran_id : null;

    // Filter: user memilih tahun semester, ambil tahun ajaran dari relasi
    $selectedTahunSemesterId = $request->input('tahun_semester_id', $tahunSemesterAktif?->id);
    $selectedTahunAjaranId = null;
    if ($selectedTahunSemesterId) {
        $selectedTahunSemester = TahunSemester::find($selectedTahunSemesterId);
        $selectedTahunAjaranId = $selectedTahunSemester ? $selectedTahunSemester->tahun_ajaran_id : $tahunAjaranAktifId;
    }

    // Query presensi harian, filter berdasarkan tahun ajaran
    $paginator = PresensiHarian::with('kelas')
        ->when($selectedTahunAjaranId, function ($query) use ($selectedTahunAjaranId) {
            $query->whereHas('detail.kelasSiswa', function ($q) use ($selectedTahunAjaranId) {
                $q->where('tahun_ajaran_id', $selectedTahunAjaranId);
            });
        })
        ->orderByDesc('tanggal')
        ->paginate(10)
        ->withQueryString();

    $data = $paginator->getCollection()->map(function ($item) {
        return [
            'id' => $item->id,
            'tanggal' => $item->tanggal,
            'kelas' => $item->kelas->nama ?? '-',
            'catatan' => $item->catatan ?? '-',
        ];
    });
    $paginator->setCollection($data);

    // Untuk filter: ambil semua tahun semester, tampilkan tahun ajaran dan semester
    $tahun_semester = TahunSemester::with('tahunAjaran')
        ->orderByDesc(
            TahunAjaran::select('mulai')
                ->whereColumn('tahun_ajaran_id', 'tahun_ajaran.id')
        )
        ->orderByDesc('semester')
        ->get();

    $tahunSemesterSelect = $tahun_semester->map(function ($ts) {
        return [
            'id' => $ts->id,
            'name' => ($ts->tahunAjaran ? $ts->tahunAjaran->tahun : '-') . ' - ' . ucfirst($ts->semester)
        ];
    });

    $breadcrumbs = [
        ['label' => 'Presensi Harian'],
    ];
    $title = 'Presensi Harian';

    return view('presensi-harian.index', [
        'data' => $paginator,
        'totalCount' => $paginator->total(),
        'breadcrumbs' => $breadcrumbs,
        'title' => $title,
        'tahun_semester' => $tahun_semester,
        'tahunSemesterSelect' => $tahunSemesterSelect,
        'selectedTahunSemesterId' => $selectedTahunSemesterId,
    ]);
}

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $user = Auth::user();
        $kelasId = $request->kelas_id;
        $tahun = TahunSemester::where('is_active', true)->first();

        $kelas = collect(); // Default: kosong
        $siswa = collect();

        $canFill = false;

        if ($user->hasRole('admin')) {
            $kelas = Kelas::all();
            $canFill = true;
        } elseif ($user->hasRole('guru')) {
            $guru = $user->guru;
            // Ambil kelas yang diampu sebagai wali atau pengajar di tahun aktif
            $kelasIds = GuruKelas::where('guru_id', $guru->id)
                ->where('tahun_semester_id', $tahun->id ?? null)
                ->whereIn('peran', ['wali', 'pengajar'])
                ->pluck('kelas_id');
            $kelas = Kelas::whereIn('id', $kelasIds)->get();
            $canFill = $kelasIds->isNotEmpty();
        }

        if ($kelasId && $canFill) {
            $siswa = KelasSiswa::with('siswa')
                ->where('kelas_id', $kelasId)
                ->where('tahun_semester_id', $tahun->id ?? null)
                ->orderBy('no_absen')
                ->get();
        }

        if ($kelasId) {
            $siswa = KelasSiswa::with('siswa')
                ->where('kelas_id', $kelasId)
                ->where('tahun_semester_id', $tahun->id ?? null)
                ->orderBy('no_absen')
                ->get();
        }

        $breadcrumbs = [
            ['label' => 'Presensi Harian', 'url' => role_route('presensi-harian.index')],
            ['label' => 'Buat Presensi'],
        ];
        $title = 'Buat Presensi Harian';

        return view('presensi-harian.create', compact(
            'kelas',
            'siswa',
            'breadcrumbs',
            'title'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $kelas_id = $request->kelas_id;
        $tahun = TahunSemester::where('is_active', true)->first();

        $tanggal = $request->tanggal;
        $kelas_id = $request->kelas_id;

        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'tanggal' => [
                'required',
                'date',
                function ($attribute, $value, $fail) use ($tanggal, $kelas_id) {
                    $exists = PresensiHarian::where('kelas_id', $kelas_id)
                        ->where('tanggal', $tanggal)
                        ->exists();
                    if ($exists) {
                        $fail('Presensi untuk kelas dan tanggal ini sudah ada.');
                    }
                }
            ],
            'catatan' => 'nullable|string|max:255',
            // 'periode' => 'required|in:tengah,akhir',
        ]);

        $tahun = TahunSemester::where('is_active', true)->first();
        $jumlahSiswa = KelasSiswa::where('kelas_id', $kelas_id)
            ->where('tahun_semester_id', $tahun->id ?? null)
            ->count();

        if ($jumlahSiswa < 1) {
            return redirect()->back()->with('error', 'Tidak ada siswa di kelas ini. Presensi tidak bisa disimpan.');
        }

        $presensiTanggal = $request->tanggal;
        $tahunSemester = TahunSemester::find($request->tahun_semester_id);

        if ($presensiTanggal < $tahunSemester->mulai || $presensiTanggal > $tahunSemester->selesai) {
            return back()->withErrors(['tanggal' => 'Tanggal di luar rentang semester.']);
        }

        // Cek atau buat presensi_harian
        $presensi = PresensiHarian::updateOrCreate(
            [
                'kelas_id' => $request->kelas_id,
                'tanggal' => $request->tanggal,
            ],
            [
                // 'input_by' => Auth::id(),
                'catatan' => $request->catatan,
                'periode' => 'akhir',
            ]
        );

        // Loop setiap siswa
        foreach ($request->status as $kelas_siswa_id => $status) {
            PresensiDetail::updateOrCreate(
                [
                    'presensi_harian_id' => $presensi->id,
                    'kelas_siswa_id' => $kelas_siswa_id,
                ],
                [
                    'periode' => 'akhir',
                    'status' => $status,
                    'keterangan' => $request->keterangan[$kelas_siswa_id] ?? null,
                ]
            );
        }

        return redirect()->to(role_route('presensi-harian.index'))->with('success', 'Presensi berhasil disimpan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return redirect()->to(role_route('presensi-detail.show', ['presensi_detail' => $id]));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $presensi = PresensiHarian::with('kelas')->findOrFail($id);

        // Agar bisa dipakai di @foreach pada edit.blade.php, bungkus dengan koleksi
        $presensiCollection = collect([$presensi]);

        return view('presensi-harian.edit', [
            'presensi' => $presensiCollection
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'catatan' => 'nullable|string|max:255',
        ]);

        $presensi = PresensiHarian::findOrFail($id);

        $presensi->update([
            'catatan' => $request->catatan,
        ]);

        return redirect()->to(role_route('presensi-harian.index'))->with('success', 'Catatan presensi berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $presensi = PresensiHarian::findOrFail($id);
            $presensi->delete();

            return redirect()->to(role_route('presensi-harian.index'))->with('success', 'Presensi berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->to(role_route('presensi-harian.index'))->with('error', 'Gagal menghapus presensi. Pastikan tidak sedang digunakan.');
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Exports\ReusableExportPdf;
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
    //    public function index(Request $request)
    // {
    //     $breadcrumbs = [['label' => 'Rapor']];
    //     $title = 'Rapor';

    //     // Ambil tahun semester aktif
    //     $tahunAktif = TahunSemester::where('is_active', true)->first();
    //     $tahunSemesterId = $request->input('tahun_semester_id', $tahunAktif?->id);

    //     // Ambil tahun_ajaran_id dari tahun_semester
    //     $tahunSemester = TahunSemester::find($tahunSemesterId);
    //     $tahunAjaranId = $tahunSemester?->tahun_ajaran_id;

    //     $kelas_id = $request->input('kelas_id');

    //     // Daftar filter
    //     $daftarTahunSemester = TahunSemester::orderByDesc('id')->get();
    //     $kelasList = Kelas::orderBy('nama')->get();

    //     // Filter kelas sesuai role guru
    //     $user = Auth::user();
    //     if ($user->hasRole('guru')) {
    //         $kelasIds = $user->guru?->kelasDiampuIds() ?? [];
    //         $kelasList = $kelasList->whereIn('id', $kelasIds);
    //     }

    //     // Query siswa dengan filter tahun ajaran & kelas
    //     $siswaList = Siswa::with(['kelasSiswa.kelas'])
    //         ->whereHas('kelasSiswa', function ($q) use ($tahunAjaranId, $kelas_id) {
    //             if ($tahunAjaranId) $q->where('tahun_ajaran_id', $tahunAjaranId);
    //             if ($kelas_id) $q->where('kelas_id', $kelas_id);
    //         })
    //         ->get()
    //         ->sortBy('nama');

    //     return view('rapor.index', compact(
    //         'breadcrumbs', 'title', 'tahunAktif', 'tahunSemesterId',
    //         'daftarTahunSemester', 'kelasList', 'kelas_id', 'siswaList'
    //     ));
    // }
    public function index(Request $request)
    {
        $breadcrumbs = [['label' => 'Rapor']];
        $title = 'Rapor';

        $user = Auth::user();
        $guru = $user->guru ?? null;

        // Ambil daftar Tahun Semester
        $daftarTahunSemester = TahunSemester::with('tahunAjaran')
            ->orderByDesc('id')
            ->get();

        // Pilih Tahun Semester aktif atau dari request
        $tahunSemesterId = $request->input('tahun_semester_id');
        $tahunAktif = $tahunSemesterId
            ? TahunSemester::find($tahunSemesterId)
            : $daftarTahunSemester->firstWhere('is_active', true)
            ?? $daftarTahunSemester->first();

        $tahunSemesterId = $tahunAktif?->id;
        $tahunAjaranId = $tahunAktif?->tahun_ajaran_id;

        // Ambil daftar kelas
        $kelasList = Kelas::orderBy('nama')->get();
        if ($user->hasRole('guru') && $guru) {
            $kelasIds = $user->guru?->kelasDiampuIds() ?? [];
            $kelasList = $kelasList->whereIn('id', $kelasIds);
        }

        $kelas_id = $request->input('kelas_id');
        $kelasDipilih = $kelas_id ? Kelas::find($kelas_id) : null;

        // Query siswa sesuai Tahun Ajaran dan Kelas
        $siswaList = Siswa::with(['kelasSiswa.kelas'])
            ->whereHas('kelasSiswa', function ($q) use ($tahunAjaranId, $kelas_id) {
                if ($tahunAjaranId) $q->where('tahun_ajaran_id', $tahunAjaranId);
                if ($kelas_id) $q->where('kelas_id', $kelas_id);
            })
            ->get()
            ->sortBy('nama');

        return view('rapor.index', compact(
            'breadcrumbs',
            'title',
            'tahunAktif',
            'tahunSemesterId',
            'daftarTahunSemester',
            'kelasList',
            'kelas_id',
            'siswaList'
        ));
    }


protected function getPengaturanRapor($tahunSemesterId)
{
    return \App\Models\PengaturanRapor::where('tahun_semester_id', $tahunSemesterId)->first();
}

    public function download(Request $request, Siswa $siswa)
    {
        $type = $request->input('type'); // kelengkapan, tengah, akhir, p5

        // Siapkan data sesuai tipe rapor
        switch ($type) {
            case 'kelengkapan':
                $title = "Kelengkapan Rapor - {$siswa->nama}";
                $headings = ['Nama', 'NIPD', 'NISN', 'Kelas', 'Status'];
                $rows = [
                    [
                        $siswa->nama,
                        $siswa->nipd,
                        $siswa->nisn,
                        optional($siswa->kelasSiswa->first()?->kelas)->nama,
                        'Lengkap / Belum'
                    ]
                ];
                break;

            case 'tengah':
                $title = "Rapor Tengah Semester - {$siswa->nama}";
                $headings = ['Mata Pelajaran', 'Nilai'];
                $rows = $siswa->mapel->map(fn($mp) => [$mp->nama, $mp->nilai])->toArray();
                break;

            case 'akhir':
                $title = "Rapor Akhir Semester - {$siswa->nama}";
                $headings = ['Mata Pelajaran', 'Nilai Akhir'];
                $rows = $siswa->mapel->map(fn($mp) => [$mp->nama, $mp->nilai_akhir])->toArray();
                break;

            case 'p5':
                $title = "Rapor P5 - {$siswa->nama}";
                $headings = ['Kompetensi', 'Nilai'];
                $rows = $siswa->p5->map(fn($p5) => [$p5->nama, $p5->nilai])->toArray();
                break;

            default:
                abort(404);
        }

        $export = new ReusableExportPdf($headings, $rows, $title);
        return $export->download("{$type}_{$siswa->nama}.pdf");
    }


    public function cetakKelengkapan(Request $request, Siswa $siswa)
    {
        $tahunSemesterId = $request->input('tahun_semester_id');
        $pengaturan = $this->getPengaturanRapor($tahunSemesterId);

        $kelas = $siswa->kelasSiswa->first()?->kelas;
        $view = 'rapor.kelengkapan';

        return view($view, compact('siswa', 'kelas', 'pengaturan'));
    }


public function cetakTengah(Request $request, Siswa $siswa)
{
    $tahunSemesterId = $request->input('tahun_semester_id');
    $pengaturan = $this->getPengaturanRapor($tahunSemesterId);

    $kelas = $siswa->kelasSiswa->first()?->kelas;
    $nilaiMapel = $siswa->mapel; // sesuaikan relasi

    $view = 'rapor.tengah';
    return view($view, compact('siswa', 'kelas', 'pengaturan', 'nilaiMapel'));
}


public function cetakAkhir(Request $request, Siswa $siswa)
{
    $tahunSemesterId = $request->input('tahun_semester_id');
    $pengaturan = $this->getPengaturanRapor($tahunSemesterId);

    $kelas = $siswa->kelasSiswa->first()?->kelas;
    $nilaiMapel = $siswa->mapel; // sesuaikan relasi

    $view = 'rapor.akhir';
    return view($view, compact('siswa', 'kelas', 'pengaturan', 'nilaiMapel'));
}


public function cetakP5(Request $request, Siswa $siswa)
{
    $tahunSemesterId = $request->input('tahun_semester_id');
    $pengaturan = $this->getPengaturanRapor($tahunSemesterId);
    $kelas = $siswa->kelasSiswa->first()?->kelas;
    // nama guru
    $guruKelas = $kelas->guruKelas->first();
    $proyek = $siswa->p5; // sesuaikan relasi

    $view = 'rapor.p5';
    return view($view, compact('siswa', 'kelas', 'pengaturan', 'proyek', 'guruKelas'));
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
    public function show(string $id) {}

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

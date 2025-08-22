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

    // public function download(Request $request, Siswa $siswa)
    // {
    //     $type = $request->input('type'); // kelengkapan, tengah, akhir, p5

    //     // Siapkan data sesuai tipe rapor
    //     switch ($type) {
    //         case 'kelengkapan':
    //             $title = "Kelengkapan Rapor - {$siswa->nama}";
    //             $headings = ['Nama', 'NIPD', 'NISN', 'Kelas', 'Status'];
    //             $rows = [
    //                 [
    //                     $siswa->nama,
    //                     $siswa->nipd,
    //                     $siswa->nisn,
    //                     optional($siswa->kelasSiswa->first()?->kelas)->nama,
    //                     'Lengkap / Belum'
    //                 ]
    //             ];
    //             break;

    //         case 'tengah':
    //             $title = "Rapor Tengah Semester - {$siswa->nama}";
    //             $headings = ['Mata Pelajaran', 'Nilai'];
    //             $rows = $siswa->mapel->map(fn($mp) => [$mp->nama, $mp->nilai])->toArray();
    //             break;

    //         case 'akhir':
    //             $title = "Rapor Akhir Semester - {$siswa->nama}";
    //             $headings = ['Mata Pelajaran', 'Nilai Akhir'];
    //             $rows = $siswa->mapel->map(fn($mp) => [$mp->nama, $mp->nilai_akhir])->toArray();
    //             break;

    //         case 'p5':
    //             $title = "Rapor P5 - {$siswa->nama}";
    //             $headings = ['Kompetensi', 'Nilai'];
    //             $rows = $siswa->p5->map(fn($p5) => [$p5->nama, $p5->nilai])->toArray();
    //             break;

    //         default:
    //             abort(404);
    //     }

    //     $export = new ReusableExportPdf($headings, $rows, $title);
    //     return $export->download("{$type}_{$siswa->nama}.pdf");
    // }


    public function cetakKelengkapan(Request $request, Siswa $siswa)
    {
        $tahunSemesterId = $request->input('tahun_semester_id');
        $pengaturan = $this->getPengaturanRapor($tahunSemesterId);

        $kelas = $siswa->kelasSiswa->first()?->kelas;
        $sekolah = $kelas?->sekolah ?? null;

        if (!$sekolah) {
        $sekolah = (object)[
            'nama' => 'SD Negeri Darmorejo 02',
            'npsn' => '20508147',
            'nss' => '101050804017',
            'alamat' => 'Jl. Kebonagungg No. 1',
            'no_telp' => '-',
            'desa' => 'Desa Darmorejo',
            'kecamatan' => 'Kecamatan Mejayan',
            'kabupaten' => 'Kabupaten Madiun',
            'provinsi' => 'Provinsi Jawa Timur',
            'website' => '-',
            'email' => 'sdn.darmorejo02@gmail.com',
        ];
    }

        return view('rapor.kelengkapan', compact('siswa', 'kelas', 'sekolah', 'pengaturan'));
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

        // Ambil kelas siswa di tahun ajaran terkait
        $kelasSiswa = $siswa->kelasSiswa()
            ->where('tahun_semester_id', $tahunSemesterId)
            ->first();

        $kelas = $kelasSiswa?->kelas ?? $siswa->kelasSiswa->first()?->kelas;

        // Ambil proyek P5 siswa sesuai tahun semester
        $nilaiP5 = $siswa->nilaiP5()
            ->where('tahun_semester_id', $tahunSemesterId)
            ->with(['nilaiP5Detail' => function($q) {
                $q->with(['subElemen', 'dimensi']);
            }])
            ->get();

        // Ambil data proyek P5 dari master proyek
        $proyekList = \App\Models\P5Proyek::where('tahun_semester_id', $tahunSemesterId)->get();

        // Siapkan array untuk view
        $p5Data = [];
        foreach ($nilaiP5 as $np5) {
            $proyek = $proyekList->firstWhere('id', $np5->p5_proyek_id);
            $capaian = [];
            foreach ($np5->nilaiP5Detail as $detail) {
                $capaian[] = [
                    'dimensi' => $detail->dimensi->nama ?? '-',
                    'sub_elemen' => $detail->subElemen->nama_sub_elemen ?? '-',
                    'predikat' => $detail->predikat,
                    'deskripsi' => $detail->deskripsi,
                ];
            }
            $p5Data[] = [
                'proyek' => $proyek,
                'catatan' => $np5->catatan,
                'capaian' => $capaian,
            ];
        }

        // Data sekolah, wali kelas, dsb (isi sesuai kebutuhan)
        $sekolah = $kelas?->sekolah ?? null;
        $waliKelas = $kelas?->guruKelas->first()?->guru ?? null;
        $fase = $kelas?->fase->nama ?? '-';
        $semester = $pengaturan?->semester ?? '-';
        $tahunAjaran = $pengaturan?->tahun_ajaran ?? '-';
        $tanggal = now()->format('d F Y');

        return view('rapor.p5', compact(
            'siswa', 'kelas', 'pengaturan', 'p5Data',
            'sekolah', 'waliKelas', 'fase', 'semester', 'tahunAjaran', 'tanggal'
        ));
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

<?php

namespace App\Http\Controllers;

use App\Exports\ReusableExportPdf;
use App\Models\Kelas;
use App\Models\P5Proyek;
use App\Models\PengaturanRapor;
use App\Models\TahunSemester;
use App\Models\Siswa;
use App\Models\ValidasiSemester;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class RaporController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    protected function sekolah($kelas = null)
    {
        // Ambil sekolah dari kelas jika ada
        $sekolah = $kelas?->sekolah ?? null;

        // Jika tidak ada di database, pakai default
        if (!$sekolah) {
            $sekolah = (object)[
                'nama' => 'SD Negeri Darmorejo 02',
                'npsn' => '20508147',
                'nss' => '101050804017',
                'alamat' => 'Jl. Kebonagung No. 1',
                'no_telp' => '-',
                'desa' => 'Darmorejo',
                'kecamatan' => 'Mejayan',
                'kabupaten' => 'Madiun',
                'provinsi' => 'Jawa Timur',
                'website' => '-',
                'email' => 'sdn.darmorejo02@gmail.com',
            ];
        }

        return $sekolah;
    }

    protected function getPengaturanRapor($tahunSemesterId)
    {
        return PengaturanRapor::where('tahun_semester_id', $tahunSemesterId)->first();
    }

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

        $isValidUAS = ValidasiSemester::where('tahun_semester_id', $tahunSemesterId)
    ->whereIn('tipe', ['UAS', 'Ekstra', 'Presensi'])
    ->where('is_validated', true)
    ->pluck('tipe')
    ->unique()
    ->count() === 3;

        $isValidUTS = ValidasiSemester::where('tahun_semester_id', $tahunSemesterId)
            ->where('tipe', 'UTS')
            ->where('is_validated', true)
            ->exists();

        $isValidP5 = ValidasiSemester::where('tahun_semester_id', $tahunSemesterId)
            ->where('tipe', 'P5')
            ->where('is_validated', true)
            ->exists();

        return view('rapor.index', compact(
            'breadcrumbs',
            'title',
            'tahunAktif',
            'tahunSemesterId',
            'daftarTahunSemester',
            'kelasList',
            'kelas_id',
            'siswaList',
            'isValidUAS',
            'isValidUTS',
            'isValidP5',
        ));
    }



    public function cetakKelengkapan(Request $request, Siswa $siswa)
    {
        $tahunSemesterId = $request->input('tahun_semester_id');
        $pengaturan = $this->getPengaturanRapor($tahunSemesterId);

        $kelas = $siswa->kelasSiswa->first()?->kelas;
        $sekolah = $this->sekolah($kelas);
        // $sekolah = $kelas?->sekolah ?? null;

        // if (!$sekolah) {
        // $sekolah = (object)[
        //     'nama' => 'SD Negeri Darmorejo 02',
        //     'npsn' => '20508147',
        //     'nss' => '101050804017',
        //     'alamat' => 'Jl. Kebonagungg No. 1',
        //     'no_telp' => '-',
        //     'desa' => 'Desa Darmorejo',
        //     'kecamatan' => 'Kecamatan Mejayan',
        //     'kabupaten' => 'Kabupaten Madiun',
        //     'provinsi' => 'Provinsi Jawa Timur',
        //     'website' => '-',
        //     'email' => 'sdn.darmorejo02@gmail.com',
        // ];
        // }

        return view('rapor.kelengkapan', compact('siswa', 'kelas', 'sekolah', 'pengaturan'));
    }


    public function cetakTengah(Request $request, Siswa $siswa)
    {
        $tahunSemesterId = $request->input('tahun_semester_id');
        $pengaturan = $this->getPengaturanRapor($tahunSemesterId);

        $tahunSemester = TahunSemester::find($tahunSemesterId);
        $tahunAjaran = $tahunSemester?->tahunAjaran?->tahun ?? '-';
        $semester = $tahunSemester?->semester ?? '-';

        $kelas = $siswa->kelasSiswa->first()?->kelas;
        $sekolah = $this->sekolah($kelas);
        $waliKelas = $kelas?->guruKelas->first()?->guru ?? null;
        $fase = $kelas?->fase->nama ?? '-';
        $tanggal = now()->translatedFormat('d F Y');

        // Ambil kelas_siswa_id aktif untuk semester ini
        $kelasSiswaAktif = $siswa->kelasSiswa()
            ->where('tahun_ajaran_id', $kelas?->tahun_ajaran_id)
            ->first();

        // Ambil nilai mapel periode tengah (UTS) untuk semester ini
        $nilaiMapel = \App\Models\NilaiMapel::with('mapel')
            ->where('kelas_siswa_id', $kelasSiswaAktif?->id)
            ->where('tahun_semester_id', $tahunSemesterId)
            ->where('periode', 'tengah') // hanya nilai UTS
            ->get();

        return view('rapor.uts', compact(
            'siswa',
            'kelas',
            'sekolah',
            'pengaturan',
            'nilaiMapel',
            'waliKelas',
            'tanggal',
            'fase',
            'semester',
            'tahunAjaran'
        ));
    }


    public function cetakAkhir(Request $request, Siswa $siswa)
    {
        $tahunSemesterId = $request->input('tahun_semester_id');
        $pengaturan = $this->getPengaturanRapor($tahunSemesterId);

        $tahunSemester = TahunSemester::find($tahunSemesterId);
        $tahunAjaran = $tahunSemester?->tahunAjaran?->tahun ?? '-';
        $semester = $tahunSemester?->semester ?? '-';

        $kelas = $siswa->kelasSiswa->first()?->kelas;
        $sekolah = $this->sekolah($kelas);
        $waliKelas = $kelas?->guruKelas->first()?->guru ?? null;
        $fase = $kelas?->fase->nama ?? '-';
        $tanggal = now()->translatedFormat('d F Y');

        // Ambil kelas_siswa_id aktif untuk semester ini
        $kelasSiswaAktif = $siswa->kelasSiswa()
            ->where('tahun_ajaran_id', $kelas?->tahun_ajaran_id)
            ->first();

        $view = 'rapor.uas';
        return view($view, compact('siswa', 'kelas', 'pengaturan', 'nilaiMapel'));
    }



    public function cetakP5(Request $request, Siswa $siswa)
    {
        $tahunSemesterId = $request->input('tahun_semester_id');
        $pengaturan = $this->getPengaturanRapor($tahunSemesterId);

        $tahunSemester = TahunSemester::find($tahunSemesterId);
        $tahunAjaran = $tahunSemester?->tahunAjaran?->tahun ?? '-';
        $semester = $tahunSemester?->semester ?? '-';

        $kelasSiswa = $siswa->kelasSiswa()
            ->where('tahun_ajaran_id', $tahunSemester?->tahun_ajaran_id)
            ->first();

        $kelas = $kelasSiswa?->kelas ?? $siswa->kelasSiswa->first()?->kelas;
        $sekolah = $this->sekolah($kelas);

        // Ambil semua nilai P5 siswa di semester ini
        $nilaiP5 = $siswa->nilaiP5()
            ->where('tahun_semester_id', $tahunSemesterId)
            ->with(['detailP5' => function ($q) {
                $q->with(['p5SubElemen', 'p5Dimensi']);
            }])
            ->get();

        // Ambil data proyek P5 dari master proyek
        $proyekList = P5Proyek::where('tahun_semester_id', $tahunSemesterId)->get();

        $p5Data = [];
        foreach ($nilaiP5 as $np5) {
            $proyek = $proyekList->firstWhere('id', $np5->p5_proyek_id);
            $capaian = [];
            foreach ($np5->detailP5 ?? [] as $detail) {
                $capaian[] = [
                    'dimensi' => $detail->p5Dimensi->nama_dimensi ?? '-',
                    'sub_elemen' => $detail->p5SubElemen->nama_sub_elemen ?? '-',
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

        $waliKelas = $kelas?->guruKelas->first()?->guru ?? null;
        $fase = $kelas?->fase->nama ?? '-';
        $tanggal = now()->translatedFormat('d F Y');

        return view('rapor.p5', compact(
            'siswa',
            'kelas',
            'pengaturan',
            'p5Data',
            'sekolah',
            'waliKelas',
            'fase',
            'semester',
            'tahunAjaran',
            'tanggal'
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

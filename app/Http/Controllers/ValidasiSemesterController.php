<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\TahunSemester;
use App\Models\ValidasiSemester;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use function auth;
use function view;
use function collect;

class ValidasiSemesterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $user = auth()->user();
        $isAdmin = $user->hasRole('admin');
        $isGuru = $user->hasRole('guru');
        // Ambil tahun_semester sesuai role
        if ($isAdmin) {
            $semesterList = TahunSemester::with('tahunAjaran')
                ->orderByDesc('tahun_ajaran_id')
                ->orderBy('semester')
                ->get()
                ->map(function ($s) {
                    $label = $s->tahunAjaran->tahun . ' - ' . ($s->semester == 'Ganjil' ? 'Ganjil' : 'Genap');
                    $s->label = $label;
                    return $s;
                });
        } elseif ($isGuru) {
            $guruId = $user->guru?->id;
            // Cari tahun_ajaran_id di mana guru menjadi wali
            $waliTahunAjaranIds = \App\Models\GuruKelas::where('guru_id', $guruId)
                ->where('peran', 'wali')
                ->pluck('tahun_ajaran_id')
                ->unique();
            $semesterList = TahunSemester::with('tahunAjaran')
                ->whereIn('tahun_ajaran_id', $waliTahunAjaranIds)
                ->orderByDesc('tahun_ajaran_id')
                ->orderBy('semester')
                ->get()
                ->map(function ($s) {
                    $label = $s->tahunAjaran->tahun . ' - ' . ($s->semester == 'Ganjil' ? 'Ganjil' : 'Genap');
                    $s->label = $label;
                    return $s;
                });
        }
        // Set default semesterId ke tahun semester aktif jika belum dipilih
        $semesterAktif = TahunSemester::where('is_active', 1)->first();
        if ($semesterAktif) {
            $semesterId = $request->input('tahun_semester', $semesterAktif->id);
        } else if ($isGuru) {
            $guruId = $user->guru?->id;
            // Cari tahun semester terakhir di mana guru pernah jadi wali
            $waliTahunAjaranIds = \App\Models\GuruKelas::where('guru_id', $guruId)
                ->where('peran', 'wali')
                ->pluck('tahun_ajaran_id')
                ->unique();
            $lastSemester = TahunSemester::whereIn('tahun_ajaran_id', $waliTahunAjaranIds)
                ->orderByDesc('tahun_ajaran_id')
                ->orderByDesc('semester')
                ->first();
            $semesterId = $request->input('tahun_semester', $lastSemester?->id);
        } else {
            $semesterId = $request->input('tahun_semester');
        }
    // $kelasList = collect();
    // if ($isAdmin) {
    //     $kelasList = Kelas::orderBy('nama')->get()->map(function ($k) {
    //         $k->label = $k->nama;
    //         return $k;
    //     });
    // } elseif ($isGuru && $semesterId) {
    //     $guruId = $user->guru?->id;
    //     $tahunAjaranId = TahunSemester::where('id', $semesterId)->value('tahun_ajaran_id');
    //     $waliKelas = \App\Models\GuruKelas::where('guru_id', $guruId)
    //         ->where('tahun_ajaran_id', $tahunAjaranId)
    //         ->where('peran', 'wali')
    //         ->pluck('kelas_id');
    //     $kelasList = Kelas::whereIn('id', $waliKelas)->orderBy('nama')->get()->map(function ($k) {
    //         $k->label = $k->nama;
    //         return $k;
    //     });
    // }
    // if ($kelasList->count() > 0) {
    //     $kelasList->prepend((object)[
    //         'id' => 'semua',
    //         'label' => 'Semua Kelas',
    //     ]);
    // }
    // $kelasId = $request->input('kelas');
        // Query validasi hanya filter tahun_semester
        $query = ValidasiSemester::with('tahunSemester.tahunAjaran', 'validator');
        if ($semesterId) {
            $query->where('tahun_semester_id', $semesterId);
        }
        // kelas_id diabaikan
        $totalCount = $query->count();
        $paginator = $query->paginate($perPage)->withQueryString();
        $validasi = $paginator->through(fn($item) => [
            'id' => $item->id,
            'tipe' => $item->tipe,
            'is_validated' => $item->is_validated,
            'validated_at' => $item->validated_at,
            'validator_name' => $item->validator?->name,
            'validator_username' => $item->validator?->username,
            'tahun_semester' => $item->tahunSemester
                ? $item->tahunSemester->tahunAjaran->tahun . ' - ' . ($item->tahunSemester->semester == 'ganjil' ? 'Ganjil' : 'Genap')
                : '-',
        ]);
        return view('validasi-semester.index', [
            'validasi' => $validasi,
            'totalCount' => $totalCount,
            'paginator' => $paginator,
            'semesterList' => $semesterList,
            'semesterId' => $semesterId,
            // 'kelasList' => $kelasList,
            // 'kelasId' => $kelasId,
        ]);
    }

    // public function validateType(ValidasiSemester $validasiSemester)
    // {
    //     $validasiSemester->update([
    //         'is_validated' => true,
    //         'validated_at' => now(),
    //         'validated_by' => Auth::id(),
    //     ]);

    //     return redirect()->back()->with('success', "{$validasiSemester->tipe} berhasil divalidasi!");
    // }
    public function validateType(ValidasiSemester $validasiSemester)
    {
        $semesterId = $validasiSemester->tahun_semester_id;
        $user = auth()->user();

        // --- CEK HAK AKSES ---
        $isAdmin = $user->hasRole('admin');
        $isWali = false;

        if ($user->hasRole('guru')) {
            $guruId = $user->guru?->id;
            $tahunAjaranId = TahunSemester::where('id', $semesterId)->value('tahun_ajaran_id');
            $isWali = \App\Models\GuruKelas::where('guru_id', $guruId)
                ->where('tahun_ajaran_id', $tahunAjaranId)
                ->where('peran', 'wali')
                ->exists();
        }

        if (!$isAdmin && !$isWali) {
            return redirect()->back()->with('error', 'Anda tidak memiliki hak untuk memvalidasi semester ini.');
        }
        // --- END CEK HAK AKSES ---
        // $tipe = $validasiSemester->tipe;

        // // Tentukan periode untuk nilai mapel
        // $periodeMapel = $tipe === 'UTS' ? 'tengah' : 'akhir';

        // // Ambil semua siswa
        // $kelasSiswaIds = \App\Models\KelasSiswa::where('tahun_ajaran_id', function ($q) use ($semesterId) {
        //     $q->select('tahun_ajaran_id')->from('tahun_semester')->where('id', $semesterId)->limit(1);
        // })->pluck('id');

        // $message = null;

        // switch ($tipe) {
        //     case 'UTS':
        //     case 'UAS':
        //         $siswaKurangMapel = $kelasSiswaIds->filter(
        //             fn($ksId) => !$this->siswaLengkapNilaiMapel($ksId, $semesterId, $periodeMapel)
        //         );
        //         if ($siswaKurangMapel->isNotEmpty()) {
        //             $mapelSiswa = \App\Models\KelasSiswa::whereIn('id', $siswaKurangMapel)->with('siswa')->get()->pluck('siswa.nama');
        //             $message = "Validasi gagal! Nilai mapel belum lengkap untuk [" . $mapelSiswa->join(', ') . "]";
        //         }
        //         break;

        //     case 'P5':
        //         $siswaKurangP5 = $kelasSiswaIds->filter(
        //             fn($ksId) => !$this->siswaLengkapNilaiP5($ksId, $semesterId)
        //         );
        //         if ($siswaKurangP5->isNotEmpty()) {
        //             $p5Siswa = \App\Models\KelasSiswa::whereIn('id', $siswaKurangP5)->with('siswa')->get()->pluck('siswa.nama');
        //             $message = "Validasi gagal! Nilai P5 belum lengkap untuk [" . $p5Siswa->join(', ') . "]";
        //         }
        //         break;

        //     case 'Presensi':
        //         $siswaKurangAbsensi = $kelasSiswaIds->filter(
        //             fn($ksId) => !$this->siswaLengkapAbsensi($ksId, $semesterId)
        //         );
        //         if ($siswaKurangAbsensi->isNotEmpty()) {
        //             $absensiSiswa = \App\Models\KelasSiswa::whereIn('id', $siswaKurangAbsensi)->with('siswa')->get()->pluck('siswa.nama');
        //             $message = "Validasi gagal! Absensi belum lengkap untuk [" . $absensiSiswa->join(', ') . "]";
        //         }
        //         break;

        //     case 'Ekstra':
        //         $siswaKurangEkstra = $kelasSiswaIds->filter(
        //             fn($ksId) => !\App\Models\NilaiEkstra::where('kelas_siswa_id', $ksId)
        //                 ->where('tahun_semester_id', $semesterId)
        //                 ->whereNotNull('nilai_akhir')
        //                 ->exists()
        //         );
        //         if ($siswaKurangEkstra->isNotEmpty()) {
        //             $ekstraSiswa = \App\Models\KelasSiswa::whereIn('id', $siswaKurangEkstra)->with('siswa')->get()->pluck('siswa.nama');
        //             $message = "Nilai Ekstra belum lengkap untuk [" . $ekstraSiswa->join(', ') . "], tapi sifatnya opsional (tidak blokir validasi).";
        //         }
        //         break;
        // }

        // Jika gagal, return error
        // if ($message) {
        //     return redirect()->back()->with('error', $message);
        // }
        $message = $this->cekKelengkapanValidasi($validasiSemester);
    if ($message) {
        return redirect()->back()->with('error', $message);
    }


        // Jika semua oke → validasi
        $validasiSemester->update([
            'is_validated' => true,
            'validated_at' => now(),
            'validated_by' => Auth::id(),
        ]);

        $tipe = $validasiSemester->tipe;
        return redirect()->back()->with('success', "$tipe berhasil divalidasi!");
    }


    // ===== Helper Functions =====
    private function cekKelengkapanValidasi(ValidasiSemester $validasiSemester): ?string
    {
        $semesterId = $validasiSemester->tahun_semester_id;
        $tipe = $validasiSemester->tipe;

        $periodeMapel = $tipe === 'UTS' ? 'tengah' : 'akhir';

        $kelasSiswaIds = \App\Models\KelasSiswa::where('tahun_ajaran_id', function ($q) use ($semesterId) {
            $q->select('tahun_ajaran_id')->from('tahun_semester')->where('id', $semesterId)->limit(1);
        })->pluck('id');

        switch ($tipe) {
            case 'UTS':
            case 'UAS':
                $siswaKurangMapel = $kelasSiswaIds->filter(
                    fn($ksId) => !$this->siswaLengkapNilaiMapel($ksId, $semesterId, $periodeMapel)
                );
                if ($siswaKurangMapel->isNotEmpty()) {
                    $mapelSiswa = \App\Models\KelasSiswa::whereIn('id', $siswaKurangMapel)->with('siswa')->get()->pluck('siswa.nama');
                    return "Validasi gagal! Nilai mapel belum lengkap untuk [" . $mapelSiswa->join(', ') . "]";
                }
                break;

            case 'P5':
                $siswaKurangP5 = $kelasSiswaIds->filter(
                    fn($ksId) => !$this->siswaLengkapNilaiP5($ksId, $semesterId)
                );
                if ($siswaKurangP5->isNotEmpty()) {
                    $p5Siswa = \App\Models\KelasSiswa::whereIn('id', $siswaKurangP5)->with('siswa')->get()->pluck('siswa.nama');
                    return "Validasi gagal! Nilai P5 belum lengkap untuk [" . $p5Siswa->join(', ') . "]";
                }
                break;

            case 'Presensi':
                $siswaKurangAbsensi = $kelasSiswaIds->filter(
                    fn($ksId) => !$this->siswaLengkapAbsensi($ksId, $semesterId)
                );
                if ($siswaKurangAbsensi->isNotEmpty()) {
                    $absensiSiswa = \App\Models\KelasSiswa::whereIn('id', $siswaKurangAbsensi)->with('siswa')->get()->pluck('siswa.nama');
                    return "Validasi gagal! Absensi belum lengkap untuk [" . $absensiSiswa->join(', ') . "]";
                }
                break;

            case 'Ekstra':
                $siswaKurangEkstra = $kelasSiswaIds->filter(
                    fn($ksId) => !\App\Models\NilaiEkstra::where('kelas_siswa_id', $ksId)
                        ->where('tahun_semester_id', $semesterId)
                        ->whereNotNull('nilai_akhir')
                        ->exists()
                );
                if ($siswaKurangEkstra->isNotEmpty()) {
                    $ekstraSiswa = \App\Models\KelasSiswa::whereIn('id', $siswaKurangEkstra)->with('siswa')->get()->pluck('siswa.nama');
                    return "Nilai Ekstra belum lengkap untuk [" . $ekstraSiswa->join(', ') . "], tapi sifatnya opsional (tidak blokir validasi).";
                }
                break;
        }

        return null;
    }


    private function siswaLengkapNilaiMapel($ksId, $semesterId, $periode)
    {
        $kelasSiswa = \App\Models\KelasSiswa::find($ksId);
        if (!$kelasSiswa) return false;

        // Ambil tahun ajaran dari tahun_semester
        $tahunAjaranId = \App\Models\TahunSemester::where('id', $semesterId)->value('tahun_ajaran_id');

        // Ambil semua mapel yang diajar di kelas ini untuk tahun ajaran tsb
        $mapelIds = \App\Models\GuruKelas::where('kelas_id', $kelasSiswa->kelas_id)
            ->where('tahun_ajaran_id', $tahunAjaranId)
            ->whereNotNull('mapel_id')
            ->pluck('mapel_id');

        // Kalau kelas belum punya mapel → anggap valid
        if ($mapelIds->isEmpty()) {
            return true;
        }

        // Hitung jumlah mapel yang wajib ada nilai
        $jumlahMapel = $mapelIds->count();

        // Hitung jumlah mapel yang sudah ada nilai akhir
        $jumlahNilai = \App\Models\NilaiMapel::where('kelas_siswa_id', $ksId)
            ->where('tahun_semester_id', $semesterId)
            ->where('periode', $periode)
            ->whereNotNull('nilai_akhir')
            ->whereIn('mapel_id', $mapelIds)
            ->distinct('mapel_id')
            ->count('mapel_id');

        // Valid kalau jumlah nilai sudah sama dengan jumlah mapel
        return $jumlahNilai >= $jumlahMapel;
    }
    private function siswaLengkapNilaiP5($ksId, $semesterId)
    {
        return \App\Models\NilaiP5Detail::whereHas('nilaiP5', function ($q) use ($ksId, $semesterId) {
            $q->where('kelas_siswa_id', $ksId)
                ->where('tahun_semester_id', $semesterId);
        })->whereNotNull('predikat')
            ->whereNotNull('deskripsi')
            ->exists();
    }

    private function siswaLengkapAbsensi($ksId, $semesterId)
    {
        $rekap = \App\Models\RekapAbsensi::where('kelas_siswa_id', $ksId)
            ->where('tahun_semester_id', $semesterId)
            ->first();

        if (!$rekap) return false;

        $jumlahHari = \App\Models\PresensiHarian::where('kelas_id', $rekap->kelas_id)
            ->where('tahun_semester_id', $semesterId)
            ->count();

        $totalAbsensi = ($rekap->total_sakit ?? 0) + ($rekap->total_izin ?? 0) + ($rekap->total_alfa ?? 0);

        return $totalAbsensi >= $jumlahHari;
    }



    public function cancelValidation(ValidasiSemester $validasiSemester)
    {
        $semesterId = $validasiSemester->tahun_semester_id;
        $user = auth()->user();

        $isAdmin = $user->hasRole('admin');
        $isWali = false;

        if ($user->hasRole('guru')) {
            $guruId = $user->guru?->id;
            $tahunAjaranId = TahunSemester::where('id', $semesterId)->value('tahun_ajaran_id');
            $isWali = \App\Models\GuruKelas::where('guru_id', $guruId)
                ->where('tahun_ajaran_id', $tahunAjaranId)
                ->where('peran', 'wali')
                ->exists();
        }

        if (!$isAdmin && !$isWali) {
            return redirect()->back()->with('error', 'Anda tidak memiliki hak untuk membatalkan validasi semester ini.');
        }


        $validasiSemester->update([
            'is_validated' => false,
            'validated_at' => null,
            'validated_by' => null,
        ]);

        return redirect()->back()->with('success', "{$validasiSemester->tipe} validasi dibatalkan!");
    }


    public function validateAll(Request $request)
    {
        $semesterId = $request->input('tahun_semester_id');
        $user = auth()->user();

        $isAdmin = $user->hasRole('admin');
        $isWali = false;

        if ($user->hasRole('guru')) {
            $guruId = $user->guru?->id;
            $tahunAjaranId = TahunSemester::where('id', $semesterId)->value('tahun_ajaran_id');
            $isWali = \App\Models\GuruKelas::where('guru_id', $guruId)
                ->where('tahun_ajaran_id', $tahunAjaranId)
                ->where('peran', 'wali')
                ->exists();
        }

        // Admin bisa validasi semua, guru wali bisa validasi semua di tahun semester di mana dia menjadi wali
        if (!$isAdmin && !$isWali) {
            return redirect()->back()->with('error', 'Anda tidak memiliki hak untuk memvalidasi semua data di semester ini.');
        }

        // $query = ValidasiSemester::where('is_validated', false)
        //     ->when($semesterId, fn($q) => $q->where('tahun_semester_id', $semesterId));

        // $pending = $query->get();

        // if ($pending->isEmpty()) {
        //     return redirect()->back()->with('error', 'Tidak ada data yang bisa divalidasi untuk filter ini.');
        // }
        $pending = ValidasiSemester::where('is_validated', false)
        ->when($semesterId, fn($q) => $q->where('tahun_semester_id', $semesterId))
        ->get();

    if ($pending->isEmpty()) {
        return redirect()->back()->with('error', 'Tidak ada data yang bisa divalidasi untuk filter ini.');
    }

    // 🔍 cek kelengkapan data tiap tipe
    foreach ($pending as $item) {
        $message = $this->cekKelengkapanValidasi($item);
        if ($message) {
            return redirect()->back()->with('error', $message);
        }
    }


        $pending->each(function ($item) {
            $item->update([
                'is_validated' => true,
                'validated_at' => now(),
                'validated_by' => Auth::id(),
            ]);
        });

        // Ambil tipe dari data pertama jika ingin menampilkan tipe
        $tipe = $pending->first()?->tipe ?? '';
        $successMsg = $tipe ? ("Semua data tipe $tipe yang sesuai filter berhasil divalidasi!") : "Semua data yang sesuai filter berhasil divalidasi!";
        return redirect()->back()->with('success', $successMsg);
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
    public function show(string $id)
    {
        //
    }

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
}

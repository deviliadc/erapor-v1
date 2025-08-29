<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\TahunSemester;
use App\Models\ValidasiSemester;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ValidasiSemesterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        // Ambil semua semester dengan relasi tahun ajaran
        $semester = TahunSemester::with('tahunAjaran')
            ->orderByDesc('tahun_ajaran_id')
            ->orderBy('semester')
            ->get();
        // Ambil semester aktif
        $semesterAktif = TahunSemester::aktif()->first();
        // Tambahkan label untuk dropdown + tandai aktif
        $semester = $semester->map(function ($s) use ($semesterAktif) {
            $label = $s->tahunAjaran->tahun . ' - ' . $s->semester;
            if ($semesterAktif && $s->id === $semesterAktif->id) {
                $label .= ' (Aktif)';
            }
            $s->label = $label;
            return $s;
        });
        // Default filter ke semester aktif
        $semesterId = $request->input('tahun_semester', $semesterAktif?->id);
        // Query validasi sesuai filter semester
        $query = ValidasiSemester::with('tahunSemester.tahunAjaran', 'validator')
            ->where('tahun_semester_id', $semesterId);
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
                ? $item->tahunSemester->tahunAjaran->tahun . ' - ' . $item->tahunSemester->semester
                : '-',
        ]);
        return view('validasi-semester.index', compact(
            'validasi',
            'totalCount',
            'paginator',
            'semester',
            'semesterId'
        ));
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
        $tipe = $validasiSemester->tipe;

        // Tentukan periode untuk nilai mapel
        $periodeMapel = $tipe === 'UTS' ? 'tengah' : 'akhir';

        // Ambil semua siswa
        $kelasSiswaIds = \App\Models\KelasSiswa::where('tahun_ajaran_id', function ($q) use ($semesterId) {
            $q->select('tahun_ajaran_id')->from('tahun_semester')->where('id', $semesterId)->limit(1);
        })->pluck('id');

        $message = null;

        switch ($tipe) {
            case 'UTS':
            case 'UAS':
                $siswaKurangMapel = $kelasSiswaIds->filter(
                    fn($ksId) => !$this->siswaLengkapNilaiMapel($ksId, $semesterId, $periodeMapel)
                );
                if ($siswaKurangMapel->isNotEmpty()) {
                    $mapelSiswa = \App\Models\KelasSiswa::whereIn('id', $siswaKurangMapel)->with('siswa')->get()->pluck('siswa.nama');
                    $message = "Validasi gagal! Nilai mapel belum lengkap untuk [" . $mapelSiswa->join(', ') . "]";
                }
                break;

            case 'P5':
                $siswaKurangP5 = $kelasSiswaIds->filter(
                    fn($ksId) => !$this->siswaLengkapNilaiP5($ksId, $semesterId)
                );
                if ($siswaKurangP5->isNotEmpty()) {
                    $p5Siswa = \App\Models\KelasSiswa::whereIn('id', $siswaKurangP5)->with('siswa')->get()->pluck('siswa.nama');
                    $message = "Validasi gagal! Nilai P5 belum lengkap untuk [" . $p5Siswa->join(', ') . "]";
                }
                break;

            case 'Presensi':
                $siswaKurangAbsensi = $kelasSiswaIds->filter(
                    fn($ksId) => !$this->siswaLengkapAbsensi($ksId, $semesterId)
                );
                if ($siswaKurangAbsensi->isNotEmpty()) {
                    $absensiSiswa = \App\Models\KelasSiswa::whereIn('id', $siswaKurangAbsensi)->with('siswa')->get()->pluck('siswa.nama');
                    $message = "Validasi gagal! Absensi belum lengkap untuk [" . $absensiSiswa->join(', ') . "]";
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
                    $message = "Nilai Ekstra belum lengkap untuk [" . $ekstraSiswa->join(', ') . "], tapi sifatnya opsional (tidak blokir validasi).";
                }
                break;
        }

        // Jika gagal, return error
        if ($message) {
            return redirect()->back()->with('error', $message);
        }

        // Jika semua oke → validasi
        $validasiSemester->update([
            'is_validated' => true,
            'validated_at' => now(),
            'validated_by' => Auth::id(),
        ]);

        return redirect()->back()->with('success', "{$tipe} berhasil divalidasi!");
    }


    // ===== Helper Functions =====
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
        $validasiSemester->update([
            'is_validated' => false,
            'validated_at' => null,
            'validated_by' => null,
        ]);

        return redirect()->back()->with('success', "{$validasiSemester->tipe} validasi dibatalkan!");
    }


    public function validateAll(Request $request)
    {
        $semesterId = $request->input('semester_id');

        $query = ValidasiSemester::where('is_validated', false)
            ->when($semesterId, fn($q) => $q->where('tahun_semester_id', $semesterId));

        $pending = $query->get();

        if ($pending->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada data yang bisa divalidasi untuk filter ini.');
        }

        $pending->each(function ($item) {
            $item->update([
                'is_validated' => true,
                'validated_at' => now(),
                'validated_by' => Auth::id(),
            ]);
        });

        return redirect()->back()->with('success', 'Semua data yang sesuai filter berhasil divalidasi!');
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

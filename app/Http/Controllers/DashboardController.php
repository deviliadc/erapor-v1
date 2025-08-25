<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Siswa;
use App\Models\Mapel;
use App\Models\Ekstra;
use App\Models\P5Proyek;
use App\Models\GuruKelas;
use App\Models\TahunAjaran;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $role = $user->role;

        return match ($role) {
            'admin' => $this->adminDashboard(),
            'guru' => $this->guruDashboard(),
            'siswa' => $this->siswaDashboard(),
            // 'wali_kelas' => $this->waliKelasDashboard(),
            'kepala_sekolah' => $this->kepsekDashboard(),
            default => abort(403, 'Unauthorized'),
        };
    }

    protected function adminDashboard()
    {
        // $totalSiswa = Siswa::count();
        $totalGuru = Guru::count();
        $totalMapel = Mapel::count();
        $totalEkstrakurikuler = Ekstra::count();
        $totalP5 = P5Proyek::count();
        // $totalLulusan = Siswa::whereHas('kelasSiswa', function ($query) {
        //     $query->where('status', 'Lulus');
        // })->count();

        // Tahun ajaran aktif
        $tahunAjaranAktif = TahunAjaran::where('is_active', 1)->first();
        $totalSiswaAktif = 0;
        if ($tahunAjaranAktif) {
            $totalSiswaAktif = Siswa::whereHas('kelasSiswa', function ($q) use ($tahunAjaranAktif) {
                $q->where('tahun_ajaran_id', $tahunAjaranAktif->id)
                    ->where('status', 'Aktif');
            })->count();
        }

        $totalLulusan = 0;
        if ($tahunAjaranAktif) {
            $totalLulusan = Siswa::whereHas('kelasSiswa', function ($query) use ($tahunAjaranAktif) {
                $query->where('status', 'Lulus')
                    ->whereHas('tahunAjaran', function ($q) use ($tahunAjaranAktif) {
                        $q->where('tahun', '<=', $tahunAjaranAktif->tahun);
                    });
            })->count();
        }

        // Ambil tahun ajaran terbaru
        $jumlahTahun = 5;
        $tahunAjaranTerbaru = TahunAjaran::orderByDesc('tahun')
            ->limit($jumlahTahun)
            ->get();
        $tahunAjaranTerbaru = $tahunAjaranTerbaru->sortBy('tahun')->values();

        // $tahunAjaranIds = $tahunAjaranTerbaru->pluck('id')->toArray();

        // // Ambil semua tahun semester yang termasuk tahun ajaran terbaru
        // $tahunSemester = TahunSemester::with('tahunAjaran')
        //     ->whereIn('tahun_ajaran_id', $tahunAjaranIds)
        //     ->get();

        // Kelompokkan dan jumlahkan siswa aktif per tahun ajaran
        $chartSiswa = collect($tahunAjaranTerbaru)->map(function ($ta) {
            $total = Siswa::whereHas('kelasSiswa', function ($q) use ($ta) {
                $q->where('tahun_ajaran_id', $ta->id)
                    ->where('status', 'Aktif');
            })->count();
            return [
                'label' => $ta->tahun,
                'total' => $total
            ];
        });

        $breadcrumbs = [['label' => 'Dashboard']];
        $title = 'Dashboard Admin';

        return view('dashboard.admin', compact(
            // 'totalSiswa',
            'totalGuru',
            'totalMapel',
            'totalEkstrakurikuler',
            'totalP5',
            'totalLulusan',
            'breadcrumbs',
            'title',
            'totalSiswaAktif',
            'tahunAjaranAktif',
            'chartSiswa'
        ));
    }

    protected function guruDashboard()
    {
        $user = Auth::user();
        $guru = $user->guru;

        if (!$guru) {
            abort(403, 'Data guru tidak ditemukan.');
        }

        // Ambil semua data kelas & mapel yang dia ajar
        $guruKelas = GuruKelas::with(['kelas', 'mapel'])
            ->where('guru_id', $guru->id)
            ->get();

        $isGuruAktif = $guruKelas->isNotEmpty();

        // Statistik sederhana (opsional bisa difilter berdasarkan guru)
        $totalSiswa = Siswa::count();
        $totalMapel = Mapel::count();
        $totalEkstra = Ekstra::count();
        $totalP5 = P5Proyek::count();

        $breadcrumbs = [['label' => 'Dashboard']];
        $title = 'Dashboard Guru';

        return view('dashboard.guru', compact(
            'breadcrumbs',
            'title',
            'guru',
            'guruKelas',
            'isGuruAktif',
            'totalSiswa',
            'totalMapel',
            'totalEkstra',
            'totalP5'
        ));
    }

    protected function siswaDashboard()
    {
        $user = Auth::user();
        $siswa = $user->siswa;
        $breadcrumbs = [['label' => 'Dashboard']];
        $title = 'Dashboard Siswa';

        // Ambil kelas siswa aktif
        $kelasSiswaAktif = $siswa->kelasSiswa()->where('status', 'Aktif')->latest()->first();
        $tahunSemesterAktif = null;
        $nilaiMapel = collect();

        if ($kelasSiswaAktif) {
            // Ambil tahun semester aktif dari kelas siswa
            $tahunSemesterAktif = \App\Models\TahunSemester::where('tahun_ajaran_id', $kelasSiswaAktif->tahun_ajaran_id)
                ->where('is_active', true)->first();

            // Ambil semua mapel di kelas
            $mapelList = $kelasSiswaAktif->kelas->mapel ?? collect();

            // Ambil nilai mapel akhir semester
            $nilaiMapel = \App\Models\NilaiMapel::where('kelas_siswa_id', $kelasSiswaAktif->id)
                ->where('tahun_semester_id', $tahunSemesterAktif?->id)
                ->where('periode', 'akhir')
                ->get();

            // Siapkan data chart
            $chartLabels = [];
            $chartData = [];
            foreach ($mapelList as $mapel) {
                $chartLabels[] = $mapel->nama;
                $nilai = $nilaiMapel->firstWhere('mapel_id', $mapel->id);
                $chartData[] = $nilai?->nilai_akhir ?? 0;
            }
        } else {
            $chartLabels = [];
            $chartData = [];
        }

        return view('dashboard.siswa', compact(
            'breadcrumbs',
            'title',
            'siswa',
            'chartLabels',
            'chartData'
        ));
    }

    protected function kepsekDashboard()
    {
        // $totalSiswa = Siswa::count();
        $totalGuru = Guru::count();
        $totalMapel = Mapel::count();
        $totalEkstrakurikuler = Ekstra::count();
        $totalP5 = P5Proyek::count();
        // $totalLulusan = Siswa::whereHas('kelasSiswa', function ($query) {
        //     $query->where('status', 'Lulus');
        // })->count();

        // Tahun ajaran aktif
        $tahunAjaranAktif = TahunAjaran::where('is_active', 1)->first();
        $totalSiswaAktif = 0;
        if ($tahunAjaranAktif) {
            $totalSiswaAktif = Siswa::whereHas('kelasSiswa', function ($q) use ($tahunAjaranAktif) {
                $q->where('tahun_ajaran_id', $tahunAjaranAktif->id)
                    ->where('status', 'Aktif');
            })->count();
        }

        $totalLulusan = 0;
        if ($tahunAjaranAktif) {
            $totalLulusan = Siswa::whereHas('kelasSiswa', function ($query) use ($tahunAjaranAktif) {
                $query->where('status', 'Lulus')
                    ->whereHas('tahunAjaran', function ($q) use ($tahunAjaranAktif) {
                        $q->where('tahun', '<=', $tahunAjaranAktif->tahun);
                    });
            })->count();
        }

        // Ambil tahun ajaran terbaru
        $jumlahTahun = 5;
        $tahunAjaranTerbaru = TahunAjaran::orderByDesc('tahun')
            ->limit($jumlahTahun)
            ->get();
        $tahunAjaranTerbaru = $tahunAjaranTerbaru->sortBy('tahun')->values();

        // $tahunAjaranIds = $tahunAjaranTerbaru->pluck('id')->toArray();

        // // Ambil semua tahun semester yang termasuk tahun ajaran terbaru
        // $tahunSemester = TahunSemester::with('tahunAjaran')
        //     ->whereIn('tahun_ajaran_id', $tahunAjaranIds)
        //     ->get();

        // Kelompokkan dan jumlahkan siswa aktif per tahun ajaran
        $chartSiswa = collect($tahunAjaranTerbaru)->map(function ($ta) {
            $total = Siswa::whereHas('kelasSiswa', function ($q) use ($ta) {
                $q->where('tahun_ajaran_id', $ta->id)
                    ->where('status', 'Aktif');
            })->count();
            return [
                'label' => $ta->tahun,
                'total' => $total
            ];
        });

        $breadcrumbs = [['label' => 'Dashboard']];
        $title = 'Dashboard Kepala Sekolah';
        return view('dashboard.kepala-sekolah', compact(
            // 'totalSiswa',
            'totalGuru',
            'totalMapel',
            'totalEkstrakurikuler',
            'totalP5',
            'totalLulusan',
            'breadcrumbs',
            'title',
            'totalSiswaAktif',
            'tahunAjaranAktif',
            'chartSiswa'
        ));
    }
}

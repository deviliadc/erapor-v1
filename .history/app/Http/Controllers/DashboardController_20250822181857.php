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
        $totalSiswa = Siswa::count();
        $totalMapel = Mapel::count();
        $totalEkstra
        $user = Auth::user();
        $guru = $user->guru;

        if (!$guru) {
            abort(403, 'Data guru tidak ditemukan.');
        }

        $guruKelas = GuruKelas::where('guru_id', $guru->id)->get();

        // $isPengajar = $guruKelas->where('peran', 'pengajar')->isNotEmpty();
        // $isWaliKelas = $guruKelas->where('peran', 'wali')->isNotEmpty();
        $isPengajar = $guru->isPengajar();
        $isWaliKelas = $guru->isWaliKelas();

        $breadcrumbs = [['label' => 'Dashboard']];
        $title = 'Dashboard Guru';

        return view('dashboard.guru', compact(
            'totalSiswa',
            'totalMapel',
            'breadcrumbs',
            'title',
            'isPengajar',
            'isWaliKelas'
        ));
    }

    protected function siswaDashboard()
    {
        $user = Auth::user();
        $siswa = $user->siswa;
        // $waliMurid = $siswa->waliMurid;
        $breadcrumbs = [['label' => 'Dashboard']];
        $title = 'Dashboard Siswa';
        return view('dashboard.siswa', compact(
            'breadcrumbs',
            'title',
            'siswa',
            // 'waliMurid'
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

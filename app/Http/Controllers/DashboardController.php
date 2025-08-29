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
        $totalGuru = Guru::count();
        $totalMapel = Mapel::count();
        $totalEkstrakurikuler = Ekstra::count();
        $totalP5 = P5Proyek::count();
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
        $jumlahTahun = 5;
        $tahunAjaranTerbaru = TahunAjaran::orderByDesc('tahun')
            ->limit($jumlahTahun)
            ->get();
        $tahunAjaranTerbaru = $tahunAjaranTerbaru->sortBy('tahun')->values();
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
        $tahunAjaranAktif = TahunAjaran::where('is_active', 1)->first();
        $totalSiswaAktif = 0;
        if ($tahunAjaranAktif) {
            $totalSiswaAktif = Siswa::whereHas('kelasSiswa', function ($q) use ($tahunAjaranAktif) {
                $q->where('tahun_ajaran_id', $tahunAjaranAktif->id)
                    ->where('status', 'Aktif');
            })->count();
        }
        if (!$guru) {
            abort(403, 'Data guru tidak ditemukan.');
        }
        $guruKelas = GuruKelas::with(['kelas', 'mapel'])
            ->where('guru_id', $guru->id)
            ->whereHas('tahunAjaran', function ($query) {
                $query->where('is_active', true);
            })
            ->get();
        $isGuruAktif = $guruKelas->isNotEmpty();
        $totalSiswa = Siswa::count();
        $totalMapel = Mapel::count();
        $totalEkstrakurikuler = Ekstra::count();
        $totalP5 = P5Proyek::count();
        $totalMapelAjar = $guruKelas->where('peran', 'pengajar')->count();
        $totalWaliKelas = $guruKelas->where('peran', 'wali')->count();
        $breadcrumbs = [['label' => 'Dashboard']];
        $title = 'Dashboard Guru';
        return view('dashboard.guru', compact(
            'breadcrumbs',
            'title',
            'guru',
            'guruKelas',
            'isGuruAktif',
            'totalSiswaAktif',
            'totalSiswa',
            'totalMapel',
            'totalEkstrakurikuler',
            'totalP5',
            'tahunAjaranAktif',
            'totalMapelAjar',
            'totalWaliKelas'
        ));
    }


    protected function siswaDashboard()
    {
        $user = Auth::user();
        $siswa = $user->siswa;
        $breadcrumbs = [['label' => 'Dashboard']];
        $title = 'Dashboard Siswa';
        $chartLabels = [];
        $chartUts = [];
        $chartUas = [];
        $kelasSiswaAktif = $siswa->kelasSiswaAktif();
        $tahunSemesterAktif = \App\Models\TahunSemester::where('is_active', 1)->first();
        $tahunSemesterId = $tahunSemesterAktif?->id;
        if ($kelasSiswaAktif && $tahunSemesterId) {
            $nilaiMapelRows = \App\Models\NilaiMapel::with('mapel')
                ->where('kelas_siswa_id', $kelasSiswaAktif->id)
                ->where('tahun_semester_id', $tahunSemesterId)
                ->orderBy('mapel_id')
                ->orderByRaw("FIELD(periode, 'tengah', 'akhir')")
                ->get();
            $grouped = [];
            foreach ($nilaiMapelRows as $row) {
                $mapelId = $row->mapel_id;
                $mapelNama = $row->mapel->nama ?? '-';
                if (!isset($grouped[$mapelId])) {
                    $grouped[$mapelId] = [
                        'nama' => $mapelNama,
                        'uts' => null,
                        'uas' => null,
                    ];
                }
                if ($row->periode == 'tengah') {
                    $grouped[$mapelId]['uts'] = $row->nilai_akhir ?? null;
                }
                if ($row->periode == 'akhir') {
                    $grouped[$mapelId]['uas'] = $row->nilai_akhir ?? null;
                }
            }
            foreach ($grouped as $mapel) {
                $chartLabels[] = $mapel['nama'];
                $chartUts[] = $mapel['uts'] ?? 0;
                $chartUas[] = $mapel['uas'] ?? 0;
            }
        }
        return view('dashboard.siswa', compact(
            'breadcrumbs',
            'title',
            'siswa',
            'chartLabels',
            'chartUts',
            'chartUas'
        ));
    }

    protected function kepsekDashboard()
    {
        $totalGuru = Guru::count();
        $totalMapel = Mapel::count();
        $totalEkstrakurikuler = Ekstra::count();
        $totalP5 = P5Proyek::count();
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
        $jumlahTahun = 5;
        $tahunAjaranTerbaru = TahunAjaran::orderByDesc('tahun')
            ->limit($jumlahTahun)
            ->get();
        $tahunAjaranTerbaru = $tahunAjaranTerbaru->sortBy('tahun')->values();
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

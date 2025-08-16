<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Siswa;
use App\Models\Mapel;
use App\Models\GuruKelas;
use App\Models\TahunAjaran;
use App\Models\TahunSemester;
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
        $totalSiswa = Siswa::count();
        $totalGuru = Guru::count();
        $totalMapel = Mapel::count();
        $totalLulusan = Siswa::whereHas('kelasSiswa', function ($query) {
            $query->where('status', 'Lulus');
        })->count();

        // Tahun ajaran aktif
        $tahunAjaranAktif = TahunSemester::where('is_active', 1)->first();
        $totalSiswaAktif = 0;
        if ($tahunAjaranAktif) {
            $totalSiswaAktif = Siswa::whereHas('kelasSiswa', function ($q) use ($tahunAjaranAktif) {
                $q->where('tahun_semester_id', $tahunAjaranAktif->id)
                    ->where('status', 'Aktif');
            })->count();
        }

               <?php
        // Ambil N tahun ajaran terbaru
        $jumlahTahun = 5;
        $tahunAjaranTerbaru = TahunAjaran::orderByDesc('tahun')
            ->limit($jumlahTahun)
            ->get();

        $tahunAjaranIds = $tahunAjaranTerbaru->pluck('id')->toArray();

        // Ambil tahun semester yang tahun_ajaran_id termasuk tahun terbaru
        $chartSiswa = TahunSemester::with('tahunAjaran')
            ->whereIn('tahun_ajaran_id', $tahunAjaranIds)
            ->orderByDesc('tahun_ajaran_id')
            ->orderBy('semester')
            ->get()
            ->map(function ($ts) {
                $total = Siswa::whereHas('kelasSiswa', function ($q) use ($ts) {
                    $q->where('tahun_semester_id', $ts->id)
                        ->where('status', 'Aktif');
                })->count();
                return [
                    'label' => ($ts->tahunAjaran ? $ts->tahunAjaran->tahun : '-') . ' - ' . $ts->semester,
                    'total' => $total
                ];
            });

        $breadcrumbs = [['label' => 'Dashboard']];
        $title = 'Dashboard Admin';

        return view('dashboard.admin', compact(
            'totalSiswa',
            'totalGuru',
            'totalMapel',
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

    // protected function waliKelasDashboard()
    // {
    //     $breadcrumbs = [['label' => 'Dashboard']];
    //     $title = 'Dashboard Wali Kelas';
    //     return view('dashboard.wali-kelas', compact('breadcrumbs', 'title'));
    // }

    protected function kepsekDashboard()
    {
        $totalSiswa = Siswa::count();
        $totalGuru = Guru::count();
        $totalMapel = Mapel::count();
        $breadcrumbs = [['label' => 'Dashboard']];
        $title = 'Dashboard Kepala Sekolah';
        return view('dashboard.kepala-sekolah', compact('breadcrumbs', 'title', 'totalSiswa', 'totalGuru', 'totalMapel'));
    }
}

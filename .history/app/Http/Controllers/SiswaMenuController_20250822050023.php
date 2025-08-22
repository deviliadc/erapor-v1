<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\RekapAbsensi;
use App\Models\NilaiMapel;
use App\Models\NilaiEkstra;
use App\Models\NilaiP5;
use App\Models\PresensiDetail;
use App\Models\TahunSemester;

class SiswaMenuController extends Controller
{
    public function absensi(Request $request)
    {
        $user = Auth::user();
        $siswa = $user->siswa;

        // $daftarTahunSemester = TahunSemester::orderByDesc('tahun')->orderByDesc('semester')->get();
        $daftarTahunSemester = TahunSemester::with('tahunAjaran')
            ->get()
            ->sortByDesc(fn($ts) => $ts->tahunAjaran->tahun)
            ->sortByDesc('semester')
            ->values();

        $tahunAktif = TahunSemester::where('is_active', 1)->first();
        $tahunSemesterId = $request->input('tahun_semester_id', $tahunAktif->id);

        // $kelasSiswaAktif = $siswa->kelasSiswa()->where('tahun_semester_id', $tahunSemesterId)->first();
        $kelasSiswaAktif = $siswa->kelasSiswa()
            ->where('tahun_ajaran_id', $tahunAktif->tahun_ajaran_id)
            ->first();

        // Rekap absensi untuk tahun semester yang dipilih
        $rekapAbsensi = RekapAbsensi::with('kelasSiswa.kelas')
            ->whereHas('kelasSiswa', function ($q) use ($siswa, $tahunSemesterId) {
                $q->where('siswa_id', $siswa->id)
                    ->where('tahun_semester_id', $tahunSemesterId);
            })->first();

        // Presensi harian detail untuk tahun semester yang dipilih, paginated
        $perPage = $request->input('per_page', 10);
        $presensiQuery = PresensiDetail::with(['presensiHarian.kelas.tahunSemester'])
            ->where('kelas_siswa_id', $kelasSiswaAktif?->id)
            ->orderBy('presensi_harian_id');

        $paginator = $presensiQuery->paginate($perPage)->withQueryString();

        // Mapping data untuk x-table.table
        $data = $paginator->through(function ($item) {
            return [
                'id' => $item->id,
                // 'tanggal' => $item->tanggal,
                'tanggal' => $item->presensiHarian?->tanggal ?? '-',
                'status' => ucfirst($item->status),
                'keterangan' => $item->keterangan ?? '-',
            ];
        });

        // Untuk chart
        // Untuk chart
        $allPresensi = $presensiQuery->get();

        $chartTanggal = [];
        $chartHadir = [];
        $chartSakit = [];
        $chartIzin = [];
        $chartAlfa = [];

        if ($allPresensi->isEmpty() && $rekapAbsensi) {
            // Fallback pakai RekapAbsensi
            $chartTanggal = ['Total']; // pakai label sederhana
            $chartHadir   = [$rekapAbsensi->total_hadir ?? 0];
            $chartSakit   = [$rekapAbsensi->total_sakit ?? 0];
            $chartIzin    = [$rekapAbsensi->total_izin ?? 0];
            $chartAlfa    = [$rekapAbsensi->total_alfa ?? 0];
        } else {
            // Normal: pakai detail harian
            $chartTanggal = $allPresensi->pluck('presensiHarian.tanggal')->unique()->values();

            foreach ($chartTanggal as $tgl) {
                $chartHadir[] = $allPresensi->where('presensiHarian.tanggal', $tgl)->where('status', 'hadir')->count();
                $chartSakit[] = $allPresensi->where('presensiHarian.tanggal', $tgl)->where('status', 'sakit')->count();
                $chartIzin[]  = $allPresensi->where('presensiHarian.tanggal', $tgl)->where('status', 'izin')->count();
                $chartAlfa[]  = $allPresensi->where('presensiHarian.tanggal', $tgl)->where('status', 'alfa')->count();
            }
        }


        $breadcrumbs = [
            ['label' => 'Absensi', 'url' => route('absensi-siswa')],
        ];
        $title = 'Rekap Absensi Siswa';

        return view('menu-siswa.absensi', compact(
            'siswa',
            'rekapAbsensi',
            'data',
            'paginator',
            'tahunAktif',
            'daftarTahunSemester',
            'chartTanggal',
            'chartHadir',
            'chartSakit',
            'chartIzin',
            'chartAlfa',
            'breadcrumbs',
            'title'
        ));
    }

    
    public function nilaiMapel(Request $request)
    {
        $user = Auth::user();
        $siswa = $user->siswa;

        // Ambil tahun semester aktif
        $tahunAktif = TahunSemester::where('is_active', 1)->first();
        $daftarTahunSemester = TahunSemester::with('tahunAjaran')->get();
        $tahunSemesterId = $request->input('tahun_semester_id', $tahunAktif?->id);

        // Ambil semua nilai mapel untuk semester terpilih
        $nilaiMapelRows = \App\Models\NilaiMapel::with('mapel')
            ->where('kelas_siswa_id', $siswa->kelasSiswaAktif()?->id)
            ->where('tahun_semester_id', $tahunSemesterId)
            ->get();

        // Group by mapel, ambil nilai UTS dan UAS per mapel
        $nilaiMapel = [];
        foreach ($nilaiMapelRows as $row) {
            $mapelId = $row->mapel_id;
            $mapelNama = $row->mapel->nama ?? '-';
            if (!isset($nilaiMapel[$mapelId])) {
                $nilaiMapel[$mapelId] = [
                    'nama' => $mapelNama,
                    'uts' => '-',
                    'uas' => '-',
                ];
            }
            if ($row->periode == 'tengah') {
                $nilaiMapel[$mapelId]['uts'] = $row->nilai_akhir ?? '-';
            }
            if ($row->periode == 'akhir') {
                $nilaiMapel[$mapelId]['uas'] = $row->nilai_akhir ?? '-';
            }
        }

        $breadcrumbs = [
            ['label' => 'Nilai Mata Pelajaran', 'url' => route('nilai-mapel-siswa')],
        ];
        $title = 'Rekap Nilai Mapel Siswa';

        return view('menu-siswa.nilai-mapel', compact(
            'siswa',
            'nilaiMapel',
            'breadcrumbs',
            'title',
            'daftarTahunSemester',
            'tahunAktif'
        ));
    }
    public function nilaiEkstra(Request $request)
    {
        $user = Auth::user();
        $siswa = $user->siswa;
        $perPage = $request->input('per_page', 10);
        $sortBy = $request->input('sortBy', 'id');
        $sortDirection = $request->input('sortDirection', 'asc');

        // Mapping kolom untuk sorting aman
        $columnMap = [
            'id'            => 'nilai_ekstra.id',
            'ekstra'        => 'ekstra.nama',
            'nilai_akhir'   => 'nilai_ekstra.nilai_akhir',
            'deskripsi'     => 'nilai_ekstra.deskripsi',
            'tahun_semester' => 'tahun_ajaran.tahun',
        ];

        $query = NilaiEkstra::query()
            ->where('kelas_siswa_id', $siswa->kelasSiswaAktif()?->id)
            ->leftJoin('ekstra', 'ekstra.id', '=', 'nilai_ekstra.ekstra_id')
            ->leftJoin('kelas_siswa', 'kelas_siswa.id', '=', 'nilai_ekstra.kelas_siswa_id')
            ->leftJoin('tahun_semester', 'tahun_semester.id', '=', 'nilai_ekstra.tahun_semester_id') // ✅ ambil dari nilai_ekstra, bukan kelas_siswa
            ->leftJoin('tahun_ajaran', 'tahun_ajaran.id', '=', 'tahun_semester.tahun_ajaran_id')
            ->select(
                'nilai_ekstra.*',
                'ekstra.nama as ekstra_nama',
                'tahun_ajaran.tahun as tahun',
                'tahun_semester.semester as semester'
            );

        // Sorting
        if (isset($columnMap[$sortBy])) {
            $query->orderBy($columnMap[$sortBy], $sortDirection);
        } else {
            $query->orderBy('nilai_ekstra.id', 'asc');
        }

        $paginator = $query->paginate($perPage)->withQueryString();

        // Mapping data untuk x-table.table
        $data = $paginator->through(function ($e) {
            return [
                'id'            => $e->id,
                'ekstra'        => $e->ekstra_nama ?? '-',
                'nilai_akhir'   => $e->nilai_akhir ?? '-',
                'deskripsi'     => $e->deskripsi ?? '-',
                'tahun_semester' => ($e->tahun ?? '-') . ' - ' . ucfirst($e->semester ?? '-'),
            ];
        });

        $breadcrumbs = [
            ['label' => 'Nilai Ekstrakurikuler', 'url' => route('nilai-ekstra-siswa')],
        ];
        $title = 'Rekap Nilai Ekstrakurikuler Siswa';

        return view('menu-siswa.nilai-ekstra', compact(
            'siswa',
            'data',
            'paginator',
            'breadcrumbs',
            'title'
        ));
    }



    public function nilaiP5(Request $request)
    {
        $user = Auth::user();
        $siswa = $user->siswa;
        $perPage = $request->input('per_page', 10);
        $sortBy = $request->input('sortBy', 'id');
        $sortDirection = $request->input('sortDirection', 'asc');

        // Mapping kolom untuk sorting aman
        $columnMap = [
            'id' => 'nilai_p5.id',
            'proyek' => 'p5_proyek.nama_proyek',
            'predikat' => 'nilai_p5_detail.predikat',
            'sub_elemen' => 'p5_sub_elemen.nama',
            'tahun_semester' => 'tahun_ajaran.tahun',
        ];

        $query = NilaiP5::query()
            ->where('kelas_siswa_id', $siswa->kelasSiswaAktif()?->id)
            ->leftJoin('p5_proyek', 'p5_proyek.id', '=', 'nilai_p5.p5_proyek_id')
            ->leftJoin('kelas_siswa', 'kelas_siswa.id', '=', 'nilai_p5.kelas_siswa_id')
            ->leftJoin('tahun_semester', 'tahun_semester.id', '=', 'nilai_p5.tahun_semester_id')
            ->leftJoin('tahun_ajaran', 'tahun_ajaran.id', '=', 'tahun_semester.tahun_ajaran_id')
            ->leftJoin('nilai_p5_detail', 'nilai_p5_detail.nilai_p5_id', '=', 'nilai_p5.id')
            ->leftJoin('p5_sub_elemen', 'p5_sub_elemen.id', '=', 'nilai_p5_detail.p5_sub_elemen_id')
            ->select(
                'nilai_p5.*',
                'p5_proyek.nama_proyek as proyek_nama',
                'p5_sub_elemen.nama_sub_elemen as sub_elemen_nama', // ✅ pakai nama_sub_elemen
                'nilai_p5_detail.predikat',
                'nilai_p5_detail.deskripsi',
                'tahun_ajaran.tahun as tahun',
                'tahun_semester.semester as semester'
            );

        // Sorting
        if (isset($columnMap[$sortBy])) {
            $query->orderBy($columnMap[$sortBy], $sortDirection);
        } else {
            $query->orderBy('nilai_p5.id', 'asc');
        }

        $paginator = $query->paginate($perPage)->withQueryString();

        // Mapping data untuk x-table.table
        $data = $paginator->through(function ($p) {
            return [
                'id' => $p->id,
                'proyek' => $p->proyek_nama ?? '-',
                'sub_elemen' => $p->sub_elemen_nama ?? '-',
                'predikat' => $p->predikat ?? '-',
                'deskripsi' => $p->deskripsi ?? '-',
                'tahun_semester' => ($p->tahun ?? '-') . ' - ' . ucfirst($p->semester ?? '-'),
            ];
        });

        $breadcrumbs = [
            ['label' => 'Nilai P5', 'url' => route('nilai-p5-siswa')],
        ];
        $title = 'Rekap Nilai P5 Siswa';

        return view('menu-siswa.nilai-p5', compact(
            'siswa',
            'data',
            'paginator',
            'breadcrumbs',
            'title'
        ));
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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

<?php

namespace App\Http\Controllers;

use App\Exports\ReusableMultiSheetExport;
use App\Models\Kelas;
use App\Models\KelasSiswa;
use App\Models\NilaiMapel;
use App\Models\TahunSemester;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class LegerRaporController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $breadcrumbs = [
            ['label' => 'Leger Rapor', 'url' => role_route('leger-rapor.index')],
        ];
        $title = 'Leger Rapor';
        $allTahunSemester = TahunSemester::with('tahunAjaran')
            ->orderByDesc('tahun_ajaran_id')
            ->orderBy('semester')
            ->get()
            ->mapWithKeys(function ($ts) {
                return [$ts->id => $ts->tahunAjaran->tahun . ' - ' . ucfirst($ts->semester)];
            });
        $allKelas = Kelas::orderBy('nama')->get()
            ->mapWithKeys(fn($kls) => [$kls->id => $kls->nama]);
        $tahunAktif = $request->tahun_semester_id
            ? TahunSemester::find($request->tahun_semester_id)
            : TahunSemester::where('is_active', true)->first();
        $kelasDipilih = $request->kelas_id
            ? Kelas::with('mapel')->find($request->kelas_id)
            : Kelas::with('mapel')->first();
        if (!$kelasDipilih || !$tahunAktif) {
            return redirect()->back()->with('error', 'Kelas atau Tahun Semester tidak valid.');
        }
        $periode = $request->input('periode', 'akhir');
        $siswaList = KelasSiswa::with('siswa')
            ->where('kelas_id', $kelasDipilih->id)
            ->where('tahun_ajaran_id', $tahunAktif->tahun_ajaran_id)
            ->get();
        $siswaIds = $siswaList->pluck('id');
        $nilaiMapel = NilaiMapel::with('mapel')
            ->whereIn('kelas_siswa_id', $siswaIds)
            ->where('tahun_semester_id', $tahunAktif->id)
            ->where('periode', $periode)
            ->get()
            ->groupBy('kelas_siswa_id');
        $mapelList = $kelasDipilih->mapel ?? collect();
        // Ambil nama mapel untuk header kolom
        $mapelColumns = $mapelList->map(fn($m) => [
            'id' => $m->id,
            'nama' => $m->nama,
        ])->values();
        // Susun data leger per siswa
        $leger = $siswaList->map(function ($ks) use ($nilaiMapel, $mapelColumns) {
            $nilaiSiswa = $nilaiMapel->get($ks->id) ?? collect();
            $nilaiPerMapel = [];
            $total = 0;
            $count = 0;
            foreach ($mapelColumns as $mapel) {
                $nilaiTengah = $nilaiSiswa->where('mapel_id', $mapel['id'])->where('periode', 'tengah')->first()?->nilai_akhir;
                $nilaiAkhir  = $nilaiSiswa->where('mapel_id', $mapel['id'])->where('periode', 'akhir')->first()?->nilai_akhir;
                $nilai = $nilaiAkhir ?? $nilaiTengah ?? '-';
                $nilaiPerMapel[$mapel['id']] = $nilai;
                if (is_numeric($nilai)) {
                    $total += $nilai;
                    $count++;
                }
            }
            $rataRata = $count > 0 ? round($total / $count, 2) : '-';
            return [
                'nama' => $ks->siswa->nama,
                'nipd' => $ks->siswa->nipd ?? '-',
                'nisn' => $ks->siswa->nisn ?? '-',
                'jk' => $ks->siswa->jenis_kelamin ?? '-',
                'mapel' => $nilaiPerMapel,
                'rata_rata' => $rataRata,
            ];
        });
        return view('leger-rapor.index', compact(
            'breadcrumbs',
            'title',
            'leger',
            'mapelColumns',
            'tahunAktif',
            'periode',
            'kelasDipilih',
            'allTahunSemester',
            'allKelas'
        ));
    }
public function export(Request $request)
{
    $tahunSemesterId = $request->input('tahun_semester_id');
    $kelasId = $request->input('kelas_id');

    $tahunSemester = TahunSemester::with('tahunAjaran')->find($tahunSemesterId);
    if (!$tahunSemester) {
        return back()->with('error', 'Tahun semester tidak valid.');
    }

    // Ambil daftar kelas
    $kelasList = $kelasId
        ? Kelas::with('mapel')->where('id', $kelasId)->get()
        : Kelas::with('mapel')->orderBy('nama')->get();

    $sheets = [];

    foreach ($kelasList as $kelas) {
        // Ambil siswa sesuai kelas & tahun ajaran
        $siswaList = KelasSiswa::with('siswa')
            ->where('kelas_id', $kelas->id)
            ->where('tahun_ajaran_id', $tahunSemester->tahun_ajaran_id)
            ->get();

        $siswaIds = $siswaList->pluck('id');

        // Ambil nilai mapel siswa (periode akhir)
        $nilaiMapel = NilaiMapel::whereIn('kelas_siswa_id', $siswaIds)
            ->where('tahun_semester_id', $tahunSemester->id)
            ->where('periode', 'akhir')
            ->get()
            ->groupBy('kelas_siswa_id');

        $mapelList = $kelas->mapel ?? collect();

        // Heading
        $headings = ['Nama', 'NIPD', 'NISN', 'Jenis Kelamin'];
        foreach ($mapelList as $m) {
            $headings[] = $m->nama;
        }
        $headings[] = 'Rata-rata';

        $enumInfo = array_fill(0, count($headings), ''); // bisa dikosongkan

        // Data
        $data = [];
        foreach ($siswaList as $ks) {
            if (!$ks->siswa) continue; // skip jika siswa null

            $nilaiSiswa = $nilaiMapel->get($ks->id) ?? collect();

$row = [
    $ks->siswa->nama ?? '-',
    $ks->siswa->nipd ?? '-',
    $ks->siswa->nisn ?? '-',
    $ks->siswa->jenis_kelamin ?? '-',
];

$total = 0;
$count = 0;

foreach ($mapelList as $m) {
    $nilaiTengah = $nilaiSiswa->where('mapel_id', $m->id)->where('periode', 'tengah')->first()?->nilai_akhir;
    $nilaiAkhir  = $nilaiSiswa->where('mapel_id', $m->id)->where('periode', 'akhir')->first()?->nilai_akhir;

    $nilai = $nilaiAkhir ?? $nilaiTengah ?? '-';
    $row[] = $nilai;

    if (is_numeric($nilai)) {
        $total += $nilai;
        $count++;
    }
}

$row[] = $count > 0 ? round($total / $count, 2) : '-';

            $data[] = $row;
        }

        // Set sheet data
        $sheets[$kelas->nama ?? 'Kelas'] = [
            'headings' => $headings,
            'enumInfo' => $enumInfo,
            'data' => $data,
        ];
    }

    $filename = 'leger_rapor_' . now()->format('Ymd_His') . '.xlsx';

    return (new \App\Exports\ReusableMultiSheetExport($sheets))->download($filename);
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

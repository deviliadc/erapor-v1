<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\KelasSiswa;
use App\Models\TahunSemester;
use App\Models\NilaiMapelDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class NilaiMapelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route('dashboard')],
            ['label' => 'Nilai Mapel'],
        ];
        $title = 'Nilai Mapel';

        $tahunAktif = TahunSemester::find($request->tahun_semester_id)
            ?? TahunSemester::where('is_active', true)->first();
        $daftarTahunSemester = TahunSemester::orderByDesc('id')->get();

        $kelasDipilih = $request->kelas_id ? Kelas::find($request->kelas_id) : null;
        $daftarKelas = Kelas::all();
        $siswaList = collect();
        $mapel = collect();
        $nilaiMapel = [];
        $activeTab = $request->input('mapel') ?? ($mapel->first()?->mapel->id ?? '');
        $periode = $request->input('periode', 'tengah');

        if ($kelasDipilih && $tahunAktif) {
            $mapel = $kelasDipilih->guruKelas()
                ->with(['mapel', 'lingkupMateri.tujuanPembelajaran'])
                ->where('tahun_semester_id', $tahunAktif->id)
                ->get();

            $siswaList = KelasSiswa::with('siswa')
                ->where('kelas_id', $kelasDipilih->id)
                ->where('tahun_semester_id', $tahunAktif->id)
                ->orderByRaw('no_absen IS NULL, no_absen ASC')
                ->get();

            // foreach ($mapel as $gk) {
            //     if ($gk->mapel_id) {
            //         $nilai = NilaiMapelDetail::with(['tujuanPembelajaran'])
            //             ->where('mapel_id', $gk->mapel_id)
            //             ->where('tahun_semester_id', $tahunAktif->id)
            //             ->get()
            //             ->groupBy('siswa_id');
            //         $nilaiMapel[$gk->id] = $nilai;
            //     } else {
            //         $nilaiMapel[$gk->id] = collect();
            //     }
            // }
            foreach ($mapel as $gk) {
                if ($gk->mapel_id) {
                    $nilai = NilaiMapelDetail::where('mapel_id', $gk->mapel_id)
                        ->where('tahun_semester_id', $tahunAktif->id)
                        ->get()
                        ->groupBy('siswa_id');

                    $nilaiMapel[$gk->mapel_id] = []; // inisialisasi

                    foreach ($nilai as $siswaId => $details) {
                        foreach ($details as $item) {
                            $jenis = $item->jenis_nilai;
                            if (in_array($jenis, ['formatif', 'sumatif'])) {
                                $key = $jenis == 'formatif' ? $item->tujuan_pembelajaran_id : $item->lingkup_materi_id;
                                $nilaiMapel[$gk->mapel_id][$jenis][$siswaId][$key] = $item->nilai;
                            } else {
                                $jenisKey = match ($jenis) {
                                    'uts-nontes' => 'non_tes',
                                    'uts-tes' => 'tes',
                                    'uas-nontes' => 'non_tes',
                                    'uas-tes' => 'tes',
                                    default => $jenis,
                                };
                                $parentKey = str_starts_with($jenis, 'uts') ? 'uts' : 'uas';
                                $nilaiMapel[$gk->mapel_id][$parentKey][$siswaId][$jenisKey] = $item->nilai;
                            }
                        }
                    }
                }
            }
        }

        $tujuanPembelajaranList = [];
        $lingkupMateriList = [];
        foreach ($mapel as $gk) {
            $tpList = $gk->lingkupMateri->flatMap->tujuanPembelajaran;
            $babList = $gk->lingkupMateri->pluck('bab')->unique()->values();
            $tujuanPembelajaranList[$gk->id] = $tpList;
            $lingkupMateriList[$gk->id] = $babList;
        }

        return view('nilai-mapel.index', compact(
            'breadcrumbs',
            'title',
            'daftarKelas',
            'kelasDipilih',
            'mapel',
            'periode',
            'nilaiMapel',
            'activeTab',
            'tahunAktif',
            'daftarTahunSemester',
            'siswaList',
            'tujuanPembelajaranList',
            'lingkupMateriList'
        ));
    }

    /**
     * Simpan nilai mapel detail dan rekap nilai akhir.
     */
    public function bulkStore(Request $request)
    {
        $request->validate([
            'mapel_id' => 'required|exists:mapel,id',
            'tahun_semester_id' => 'required|exists:tahun_semester,id',
            'periode' => 'required|in:tengah,akhir',
            'nilai' => 'nullable|array',
        ]);

        DB::beginTransaction();
        try {
            foreach (($request->nilai ?? []) as $siswaId => $nilaiSet) {
                foreach ($nilaiSet as $key => $nilai) {
                    if ($nilai === null || $nilai === '') continue;

                    $data = [
                        'siswa_id' => $siswaId,
                        'mapel_id' => $request->mapel_id,
                        'tahun_semester_id' => $request->tahun_semester_id,
                        'periode' => $request->periode,
                        'nilai' => $nilai,
                        'is_validated' => false,
                    ];

                    // Mapping jenis_nilai dan tujuan_pembelajaran_id
                    if (Str::startsWith($key, 'tp_')) {
                        $data['tujuan_pembelajaran_id'] = Str::after($key, 'tp_');
                        $data['jenis_nilai'] = 'formatif';
                    } elseif (Str::startsWith($key, 'sumatif_')) {
                        $data['lingkup_materi_id'] = Str::after($key, 'sumatif_');
                        $data['jenis_nilai'] = 'sumatif';
                    } elseif ($key === 'uts_nontes') {
                        $data['jenis_nilai'] = 'uts-nontes';
                    } elseif ($key === 'uts_tes') {
                        $data['jenis_nilai'] = 'uts-tes';
                    } elseif ($key === 'uas_nontes') {
                        $data['jenis_nilai'] = 'uas-nontes';
                    } elseif ($key === 'uas_tes') {
                        $data['jenis_nilai'] = 'uas-tes';
                    } else {
                        $data['jenis_nilai'] = $key;
                    }

                    NilaiMapelDetail::updateOrCreate(
                        [
                            'siswa_id' => $data['siswa_id'],
                            'mapel_id' => $data['mapel_id'],
                            'tahun_semester_id' => $data['tahun_semester_id'],
                            'periode' => $data['periode'],
                            'jenis_nilai' => $data['jenis_nilai'],
                            'tujuan_pembelajaran_id' => $data['tujuan_pembelajaran_id'] ?? null,
                            'lingkup_materi_id' => $data['lingkup_materi_id'] ?? null,
                        ],
                        ['nilai' => $data['nilai'], 'is_validated' => false]
                    );
                }
            }

            DB::commit();
            return redirect()->route('nilai-mapel.index', [
                'kelas_id' => $request->kelas_id,
                'tahun_semester_id' => $request->tahun_semester_id,
                'periode' => $request->periode,
                'mapel' => $request->mapel_id,
            ])->with('success', 'Nilai berhasil disimpan.');
        } catch (\Throwable $th) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $th->getMessage());
        }
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
    public function update()
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

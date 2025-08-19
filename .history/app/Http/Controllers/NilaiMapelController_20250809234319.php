<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\KelasSiswa;
use App\Models\GuruKelas;
use App\Models\LingkupMateri;
use App\Models\NilaiMapel;
use App\Models\TahunSemester;
use App\Models\NilaiMapelDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NilaiMapelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => role_route('dashboard')],
            ['label' => 'Nilai Mapel'],
        ];
        $title = 'Nilai Mapel';

        $user = Auth::user();
        $guru = $user->guru ?? null;
        $periode = $request->input('periode', 'tengah');

        // Tahun semester
        $daftarTahunSemester = $user->hasRole('guru') && $guru
            ? TahunSemester::whereIn('id', GuruKelas::where('guru_id', $guru->id)->pluck('tahun_semester_id'))->orderByDesc('id')->get()
            : TahunSemester::orderByDesc('id')->get();

        $tahunAktif = TahunSemester::find($request->tahun_semester_id)
            ?? $daftarTahunSemester->firstWhere('is_active', true)
            ?? $daftarTahunSemester->first();

        // Kelas
        $daftarKelas = $user->hasRole('guru') && $guru
            ? Kelas::whereIn('id', GuruKelas::where('guru_id', $guru->id)->where('tahun_semester_id', $tahunAktif->id)->pluck('kelas_id'))->get()
            : Kelas::all();

        $kelasDipilih = $request->kelas_id ? Kelas::find($request->kelas_id) : null;
        $siswaList = collect();
        $mapel = collect();
        $nilaiMapel = [];
        $activeTab = $request->input('active_tab') ?? $request->input('mapel') ?? '';

        if ($kelasDipilih && $tahunAktif) {
            // Ambil siswa pertama (atau semua siswa jika ingin filter per siswa)
            $siswaPertama = $siswaList->first()?->siswa;

            if ($user->hasRole('guru') && $guru) {
                $isWali = GuruKelas::where('guru_id', $guru->id)
                    ->where('kelas_id', $kelasDipilih->id)
                    ->where('tahun_semester_id', $tahunAktif->id)
                    ->where('peran', 'wali')->exists();

                $mapel = GuruKelas::with(['mapel' => function ($q) use ($siswaPertama) {
                    // Filter mapel agama sesuai agama siswa
                    if ($siswaPertama && $siswaPertama->agama === 'Islam') {
                        $q->where(function ($sub) {
                            $sub->where('kategori', '!=', 'Wajib')
                                ->orWhere(function ($agama) {
                                    $agama->where('kategori', 'Wajib')
                                        ->where('nama', '!=', 'Pendidikan Agama');
                                });
                        })->orWhere(function ($sub) {
                            $sub->where('kategori', 'Wajib')
                                ->where('nama', 'Pendidikan Agama')
                                ->where('agama', 'Islam');
                        });
                    }
                }])
                    ->where('kelas_id', $kelasDipilih->id)
                    ->where('tahun_semester_id', $tahunAktif->id)
                    ->when(!$isWali, fn($q) => $q->where('guru_id', $guru->id))
                    ->get();
            } else {
                $mapel = GuruKelas::with(['mapel' => function ($q) use ($siswaPertama) {
                    if ($siswaPertama && $siswaPertama->agama === 'Islam') {
                        $q->where(function ($sub) {
                            $sub->where('kategori', '!=', 'Wajib')
                                ->orWhere(function ($agama) {
                                    $agama->where('kategori', 'Wajib')
                                        ->where('nama', '!=', 'Pendidikan Agama');
                                });
                        })->orWhere(function ($sub) {
                            $sub->where('kategori', 'Wajib')
                                ->where('nama', 'Pendidikan Agama')
                                ->where('agama', 'Islam');
                        });
                    }
                }])
                    ->where('kelas_id', $kelasDipilih->id)
                    ->where('tahun_semester_id', $tahunAktif->id)
                    ->get();
            }

            // Siswa
            $siswaList = KelasSiswa::with('siswa')
                ->where('kelas_id', $kelasDipilih->id)
                ->where('tahun_semester_id', $tahunAktif->id)
                ->orderByRaw('no_absen IS NULL, no_absen ASC')
                ->get();

            // Nilai Mapel
            foreach ($mapel as $gk) {
                if ($gk->mapel_id) {
                    foreach ($siswaList as $ks) {
                        $nm = NilaiMapel::with('detail')
                            ->where('kelas_siswa_id', $ks->id)
                            ->where('mapel_id', $gk->mapel_id)
                            ->where('periode', $periode)
                            ->first();

                        if ($nm) {
                            foreach ($nm->detail as $item) {
                                $jenis = $item->jenis_nilai;
                                if (in_array($jenis, ['formatif', 'sumatif'])) {
                                    $key = $jenis == 'formatif' ? $item->tujuan_pembelajaran_id : $item->lingkup_materi_id;
                                    $nilaiMapel[$gk->mapel_id][$jenis][$ks->id][$key] = $item->nilai;
                                } else {
                                    $jenisKey = match ($jenis) {
                                        'uts-nontes', 'uas-nontes' => 'non_tes',
                                        'uts-tes', 'uas-tes' => 'tes',
                                        default => $jenis,
                                    };
                                    $parentKey = str_starts_with($jenis, 'uts') ? 'uts' : (str_starts_with($jenis, 'uas') ? 'uas' : $jenis);
                                    $nilaiMapel[$gk->mapel_id][$parentKey][$ks->id][$jenisKey] = $item->nilai;
                                }
                            }
                        }
                    }
                }
            }
        }

        // Lingkup materi & tujuan pembelajaran
        $tujuanPembelajaranList = [];
        $lingkupMateriList = [];
        foreach ($mapel as $gk) {
            $lmList = LingkupMateri::with('tujuanPembelajaran')
                ->where('mapel_id', $gk->mapel_id)
                ->where('kelas_id', $gk->kelas_id)
                ->where('periode', $periode)
                ->get();
            $tpList = $lmList->flatMap->tujuanPembelajaran;
            $tujuanPembelajaranList[$gk->mapel_id] = $tpList;
            $lingkupMateriList[$gk->mapel_id] = $lmList;
        }

        // Rekap nilai per mapel per siswa
        $rekapNilai = [];
        foreach ($mapel as $gk) {
            foreach ($siswaList as $ks) {
                $nm = NilaiMapel::with('detail')
                    ->where('kelas_siswa_id', $ks->id)
                    ->where('mapel_id', $gk->mapel_id)
                    ->where('periode', $periode)
                    ->first();

                if ($nm) {
                    $rekapNilai[$gk->mapel_id][$ks->id] = $this->hitungRekapNilaiMapel($nm, $periode, $ks->id, $gk->mapel_id);
                }
            }
        }

        return view('nilai-mapel.index', compact(
            'breadcrumbs',
            'title',
            'user',
            'daftarTahunSemester',
            'tahunAktif',
            'daftarKelas',
            'kelasDipilih',
            'mapel',
            'siswaList',
            'nilaiMapel',
            'activeTab',
            'periode',
            'tujuanPembelajaranList',
            'lingkupMateriList',
            'rekapNilai'
        ));
    }

    /**
     * Hitung rekap nilai mapel per siswa per mapel.
     */
    private function hitungRekapNilaiMapel(NilaiMapel $nilaiMapel, string $periode, int $kelasSiswaId, int $mapelId)
    {
        $detail = $nilaiMapel->detail->where('periode', $periode);

        // FORMATIF
        $formatif = $detail->where('jenis_nilai', 'formatif');
        $naFormatif = $formatif->pluck('nilai')->filter()->avg();

        // SUMATIF
        $sumatif = $detail->where('jenis_nilai', 'sumatif');
        $naSumatif = $sumatif->pluck('nilai')->filter()->avg();

        // UTS
        $utsTes = $detail->where('jenis_nilai', 'uts-tes')->pluck('nilai')->first();
        $utsNonTes = $detail->where('jenis_nilai', 'uts-nontes')->pluck('nilai')->first();
        $naUTS = collect([$utsTes, $utsNonTes])->filter()->avg();

        // UAS
        $uasTes = $detail->where('jenis_nilai', 'uas-tes')->pluck('nilai')->first();
        $uasNonTes = $detail->where('jenis_nilai', 'uas-nontes')->pluck('nilai')->first();
        $naUAS = collect([$uasTes, $uasNonTes])->filter()->avg();

        // Nilai Akhir
        $komponen = [];
        if (!is_null($naFormatif)) $komponen[] = $naFormatif;
        if (!is_null($naSumatif)) $komponen[] = $naSumatif;
        if ($periode === 'tengah' && !is_null($naUTS)) $komponen[] = $naUTS;
        if ($periode === 'akhir') {
            if (!is_null($naUTS)) $komponen[] = $naUTS;
            if (!is_null($naUAS)) $komponen[] = $naUAS;
        }
        $nilaiAkhir = count($komponen) ? collect($komponen)->avg() : null;

        // Deskripsi tertinggi & terendah dari formatif
        $deskripsiTertinggi = null;
        $deskripsiTerendah = null;
        if ($formatif->count()) {
            $max = $formatif->max('nilai');
            $min = $formatif->min('nilai');
            $tertinggi = $formatif->where('nilai', $max)->first();
            $terendah = $formatif->where('nilai', $min)->first();
            $deskripsiTertinggi = $tertinggi->deskripsi ?: optional($tertinggi->tujuanPembelajaran)->tujuan;
            $deskripsiTerendah = $terendah->deskripsi ?: optional($terendah->tujuanPembelajaran)->tujuan;
        }

        return [
            'na_formatif' => $naFormatif,
            'na_sumatif' => $naSumatif,
            'na_uts' => $naUTS,
            'na_uas' => $naUAS,
            'nilai_akhir' => $nilaiAkhir,
            'deskripsi_tertinggi' => $deskripsiTertinggi,
            'deskripsi_terendah' => $deskripsiTerendah,
        ];
    }

    /**
     * Simpan nilai mapel detail dan rekap nilai akhir.
     */
    public function bulkStore(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'tahun_semester_id' => 'required|exists:tahun_semester,id',
            'periode' => 'required|in:tengah,akhir',
            'nilai' => 'nullable|array',
            'nilai.*.*' => 'nullable|numeric|min:0|max:100',
        ]);

        DB::beginTransaction();

        try {
            // Ambil semua mapel yang ada di form
            $mapelIds = [];
            foreach ($request->nilai ?? [] as $kelasSiswaId => $nilaiSet) {
                foreach ($nilaiSet as $key => $nilai) {
                    if (Str::startsWith($key, 'formatif_') || Str::startsWith($key, 'sumatif_')) {
                        // formatif_7 atau sumatif_14, ambil mapel dari relasi
                        // (atau bisa ambil dari hidden input mapel_id di form jika ada)
                    }
                }
                // Atau ambil dari $request->mapel_id jika satu mapel per submit
                if ($request->mapel_id) $mapelIds[] = $request->mapel_id;
            }
            $mapelIds = array_unique($mapelIds);

            // Jika hanya satu mapel per submit, gunakan cara lama
            if (count($mapelIds) === 0 && $request->mapel_id) {
                $mapelIds = [$request->mapel_id];
            }

            // Jika form mengirim semua mapel, proses semua
            foreach ($mapelIds as $mapelId) {
                foreach ($request->nilai ?? [] as $kelasSiswaId => $nilaiSet) {
                    $kelasSiswa = KelasSiswa::find($kelasSiswaId);
                    if (!$kelasSiswa) continue;

                    // Filter nilai yang benar-benar terisi
                    $nilaiTerisi = collect($nilaiSet)->filter(function ($v) {
                        return $v !== null && $v !== '';
                    });

                    if ($nilaiTerisi->isEmpty()) {
                        // Jika semua nilai kosong, hapus NilaiMapel & detail
                        $nilaiMapel = NilaiMapel::where([
                            'kelas_siswa_id' => $kelasSiswaId,
                            'mapel_id' => $mapelId,
                            'periode' => $request->periode,
                        ])->first();
                        if ($nilaiMapel) {
                            NilaiMapelDetail::where('nilai_mapel_id', $nilaiMapel->id)->delete();
                            $nilaiMapel->delete();
                        }
                        continue;
                    }

                    // Simpan/update NilaiMapel
                    $nilaiMapel = NilaiMapel::updateOrCreate(
                        [
                            'kelas_siswa_id' => $kelasSiswaId,
                            'siswa_id' => $kelasSiswa->siswa_id,
                            'mapel_id' => $mapelId,
                            'periode' => $request->periode,
                        ]
                    );

                    foreach ($nilaiSet as $key => $nilai) {
                        $jenisKey = match ($key) {
                            'uts_nontes' => 'uts-nontes',
                            'uts_tes' => 'uts-tes',
                            'uas_nontes' => 'uas-nontes',
                            'uas_tes' => 'uas-tes',
                            default => $key,
                        };

                        if ($request->periode === 'tengah' && str_starts_with($jenisKey, 'uas')) continue;

                        $data = [
                            'nilai_mapel_id' => $nilaiMapel->id,
                            'periode' => $request->periode,
                            'tujuan_pembelajaran_id' => null,
                            'lingkup_materi_id' => null,
                            'jenis_nilai' => $jenisKey,
                        ];

                        if (Str::startsWith($key, 'formatif_')) {
                            $data['jenis_nilai'] = 'formatif';
                            $data['tujuan_pembelajaran_id'] = Str::after($key, 'formatif_');
                        } elseif (Str::startsWith($key, 'sumatif_')) {
                            $data['jenis_nilai'] = 'sumatif';
                            $data['lingkup_materi_id'] = Str::after($key, 'sumatif_');
                        }

                        if ($nilai !== null && $nilai !== '') {
                            NilaiMapelDetail::updateOrCreate(
                                [
                                    'nilai_mapel_id' => $nilaiMapel->id,
                                    'jenis_nilai' => $data['jenis_nilai'],
                                    'tujuan_pembelajaran_id' => $data['tujuan_pembelajaran_id'],
                                    'lingkup_materi_id' => $data['lingkup_materi_id'],
                                    'periode' => $data['periode'],
                                ],
                                [
                                    'nilai' => $nilai,
                                    'is_validated' => false,
                                ]
                            );
                        } else {
                            NilaiMapelDetail::where([
                                'nilai_mapel_id' => $nilaiMapel->id,
                                'jenis_nilai' => $data['jenis_nilai'],
                                'tujuan_pembelajaran_id' => $data['tujuan_pembelajaran_id'],
                                'lingkup_materi_id' => $data['lingkup_materi_id'],
                                'periode' => $data['periode'],
                            ])->delete();
                        }
                    }

                    // Hitung rekap, jika semua detail kosong, hapus NilaiMapel
                    $rekap = $this->hitungRekapNilaiMapel($nilaiMapel, $request->periode, $kelasSiswaId, $mapelId);
                    $isKosong = collect($rekap)->except(['deskripsi_tertinggi', 'deskripsi_terendah'])->filter()->isEmpty();

                    if ($isKosong) {
                        NilaiMapelDetail::where('nilai_mapel_id', $nilaiMapel->id)->delete();
                        $nilaiMapel->delete();
                    } else {
                        $nilaiMapel->update([
                            'nilai_akhir' => $rekap['nilai_akhir'],
                            'deskripsi_tertinggi' => $rekap['deskripsi_tertinggi'],
                            'deskripsi_terendah' => $rekap['deskripsi_terendah'],
                        ]);
                    }
                }
            }

            DB::commit();

            return redirect()->to(role_route('nilai-mapel.index', [
                'kelas_id' => $request->kelas_id,
                'tahun_semester_id' => $request->tahun_semester_id,
                'periode' => $request->periode,
                'mapel' => $request->mapel_id,
                'active_tab' => $request->active_tab,
            ]))->with('success', 'Nilai berhasil disimpan.');
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

<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\KelasSiswa;
use App\Models\NilaiP5;
use App\Models\NilaiP5Detail;
use App\Models\P5Dimensi;
use App\Models\P5Elemen;
use App\Models\P5Proyek;
use App\Models\P5ProyekDetail;
use App\Models\P5SubElemen;
use App\Models\TahunSemester;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NilaiP5Controller extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        $user = Auth::user();
        $isAdmin = $user->hasRole('admin');
        $tahunSemesterList = collect();
        $kelasList = collect();

        // 1. Ambil daftar tahun semester (urut tahun ajaran dan semester)
        if ($isAdmin) {
            $tahunSemesterList = TahunSemester::with('tahunAjaran')
                ->get()
                ->sortByDesc(fn($ts) => $ts->tahunAjaran->tahun)
                ->sortByDesc('semester')
                ->values();
            $kelasList = Kelas::all();
        } elseif ($user->hasRole('guru')) {
            $guru = $user->guru;
            // Tahun semester di mana guru ini jadi wali
            $tahunIds = \App\Models\GuruKelas::where('guru_id', $guru->id)
                ->where('peran', 'wali')
                ->pluck('tahun_semester_id')
                ->unique()
                ->values();
            $tahunSemesterList = TahunSemester::with('tahunAjaran')
                ->whereIn('id', $tahunIds)
                ->get()
                ->sortByDesc(fn($ts) => $ts->tahunAjaran->tahun)
                ->sortByDesc('semester')
                ->values();
        }

        // 2. Default tahun semester: aktif, atau tahun wali pertama
        $tahunSemesterAktif = $tahunSemesterList->firstWhere('is_active', true) ?? $tahunSemesterList->first();
        $tahunSemesterId = $request->tahun_semester_id ?? $tahunSemesterAktif?->id;

        // 3. Kelas: admin semua kelas, wali hanya kelas yang diampu pada tahun semester terpilih
        if ($isAdmin) {
            $kelasList = Kelas::all();
        } elseif ($user->hasRole('guru')) {
            $guru = $user->guru;
            $kelasIds = \App\Models\GuruKelas::where('guru_id', $guru->id)
                ->where('tahun_semester_id', $tahunSemesterId)
                ->where('peran', 'wali')
                ->pluck('kelas_id')
                ->unique()
                ->values();
            $kelasList = Kelas::whereIn('id', $kelasIds)->get();
        }

        $faseId = $request->fase_id;
        $kelasId = $request->kelas_id ?? ($kelasList->first()?->id);

        // 4. Proyek P5 sesuai tahun semester yang dipilih
        $proyekList = P5Proyek::where('tahun_semester_id', $tahunSemesterId)->get();
        $proyekId = $request->proyek_id ?? $proyekList->first()?->id;

        $periode = 'akhir';

        // 5. Ambil detail proyek (dimensi, elemen, subelemen) hanya dari p5_proyek_detail
        $dimensiList = collect();
        $elemenByDimensi = [];
        $subelemenByDimensi = [];
        $subelemenList = collect();

        if ($proyekId) {
            $detail = P5ProyekDetail::where('p5_proyek_id', $proyekId)->get();

            $dimensiIds = $detail->pluck('p5_dimensi_id')->unique()->values();
            $elemenIds = $detail->pluck('p5_elemen_id')->unique()->values();
            $subelemenIds = $detail->pluck('p5_sub_elemen_id')->unique()->values();

            $dimensiList = P5Dimensi::whereIn('id', $dimensiIds)->get();
            $elemenList = P5Elemen::whereIn('id', $elemenIds)->get();
            $subelemenList = P5SubElemen::with('capaian')->whereIn('id', $subelemenIds)->get();

            foreach ($dimensiList as $dimensi) {
                $elemenByDimensi[$dimensi->id] = $elemenList->where('p5_dimensi_id', $dimensi->id)->values();
                $subelemenByDimensi[$dimensi->id] = $subelemenList->filter(function ($sub) use ($elemenList, $dimensi) {
                    $elemen = $elemenList->firstWhere('id', $sub->p5_elemen_id);
                    return $elemen && $elemen->p5_dimensi_id == $dimensi->id;
                })->values();
            }
        }

        // 6. Ambil siswa di kelas dan tahun ajaran dari tahun semester yang dipilih
        $tahunAjaranId = TahunSemester::find($tahunSemesterId)?->tahun_ajaran_id;
        $siswaList = collect();
        if ($kelasId && $tahunAjaranId) {
            $siswaList = KelasSiswa::with('siswa')
                ->where('kelas_id', $kelasId)
                ->where('tahun_ajaran_id', $tahunAjaranId)
                ->orderBy('no_absen')
                ->get();
        }

        // 7. Siswa per kelas untuk tab
        $siswaByKelas = [];
        foreach ($kelasList as $kls) {
            $siswaByKelas[$kls->id] = KelasSiswa::with('siswa')
                ->where('kelas_id', $kls->id)
                ->where('tahun_ajaran_id', $tahunAjaranId)
                ->orderBy('no_absen')
                ->get()
                ->map(function ($ks) {
                    return [
                        'id' => $ks->id,
                        'nama' => $ks->siswa->nama ?? '-',
                    ];
                });
        }

        // 8. Mapping kelas ke fase
        $faseIdByKelas = [];
        foreach ($kelasList as $kls) {
            $faseIdByKelas[$kls->id] = $kls->fase_id ?? $faseId;
        }

        // 9. Ambil nilai jika ada siswa dan proyek
        $nilaiMap = [];
        if ($siswaList->isNotEmpty() && $proyekId) {
            $nilaiP5 = NilaiP5::where('periode', $periode)
                ->where('p5_proyek_id', $proyekId)
                ->whereIn('kelas_siswa_id', $siswaList->pluck('id'))
                ->get();

            $nilaiP5Ids = $nilaiP5->pluck('id');
            // Ambil detail beserta relasi nilaiP5
            $detail = NilaiP5Detail::with('nilaiP5')->whereIn('nilai_p5_id', $nilaiP5Ids)->get();

            foreach ($nilaiP5 as $n) {
                $nilaiMap[$n->kelas_siswa_id]['catatan'] = $n->catatan;
            }
            foreach ($detail as $d) {
                // Cek relasi dan kelas_siswa_id valid
                $kelasSiswaId = $d->nilaiP5 ? $d->nilaiP5->kelas_siswa_id : null;
                if ($kelasSiswaId) {
                    $nilaiMap[$kelasSiswaId][$d->p5_sub_elemen_id]['predikat'] = $d->predikat;
                }
            }
        }

        $breadcrumbs = [
            ['label' => 'Nilai P5'],
        ];
        $title = 'Nilai P5';

        return view('nilai-p5.index', compact(
            'tahunSemesterList',
            'kelasList',
            'siswaByKelas',
            'proyekList',
            'tahunSemesterId',
            'faseId',
            'kelasId',
            'proyekId',
            'periode',
            'dimensiList',
            'elemenByDimensi',
            'subelemenByDimensi',
            'subelemenList',
            'siswaList',
            'nilaiMap',
            'breadcrumbs',
            'title',
            'faseIdByKelas'
        ));
    }

    /**
     * Update batch of Nilai P5.
     */
    public function updateBatch(Request $request)
    {
        $request->validate([
            'tahun_semester_id' => 'required|exists:tahun_semester,id',
            'kelas_id' => 'required|exists:kelas,id',
            'proyek_id' => 'required|exists:p5_proyek,id',
            'periode' => 'required|in:tengah,akhir',
            'nilai' => 'required|array',
        ]);

        $faseId = $request->fase_id;

        DB::beginTransaction();
        try {
            foreach ($request->nilai as $ksId => $item) {
                $kelasSiswaId = $item['kelas_siswa_id'] ?? $ksId;
                if (!$kelasSiswaId) continue;

                $kelasSiswa = KelasSiswa::find($kelasSiswaId);
                if (!$kelasSiswa || !$kelasSiswa->siswa_id) continue;

                // Filter subelemen yang ada predikat
                $predikatFiltered = [];
                foreach ($item as $subelemenId => $data) {
                    if ($subelemenId === 'kelas_siswa_id' || $subelemenId === 'catatan') continue;
                    $predikat = $data['predikat'] ?? null;
                    if ($predikat !== null && $predikat !== '') {
                        $predikatFiltered[$subelemenId] = $predikat;
                    }
                }

                // Jika semua nilai kosong, hapus data
                if (empty($predikatFiltered)) {
                    $nilaiP5 = NilaiP5::where([
                        'kelas_siswa_id' => $kelasSiswaId,
                        'p5_proyek_id' => $request->proyek_id,
                        'tahun_semester_id' => $request->tahun_semester_id,
                        'periode' => $request->periode,
                    ])->first();

                    if ($nilaiP5) {
                        NilaiP5Detail::where('nilai_p5_id', $nilaiP5->id)
                        // ->where('periode', $request->periode)
                        ->delete();
                        $nilaiP5->delete();
                    }
                    continue;
                }

                // Simpan nilai utama
                $nilaiP5 = NilaiP5::updateOrCreate(
                    [
                        'kelas_siswa_id' => $kelasSiswaId,
                        'p5_proyek_id' => $request->proyek_id,
                        'tahun_semester_id' => $request->tahun_semester_id,
                        'periode' => $request->periode,
                    ],
                    [
                        'kelas_siswa_id' => $kelasSiswaId, // <-- WAJIB ADA DI SINI!
                        'siswa_id' => $kelasSiswa->siswa_id,
                        'catatan' => $item['catatan'] ?? null,
                        'is_validated' => false,
                    ]
                );

                // Simpan detail per subelemen
                foreach ($predikatFiltered as $subelemenId => $predikat) {
                    $subelemen = P5SubElemen::with(['elemen', 'capaian'])->find($subelemenId);
                    if (!$subelemen || !$subelemen->elemen) continue;

                    $dimensiId = $subelemen->elemen->p5_dimensi_id;
                    // $capaian = $subelemen->capaian->firstWhere('fase_id', $faseId)?->capaian ?? '';
                    // $deskripsi = trim($subelemen->nama_sub_elemen . ' - ' . $capaian);
$faseIdSiswa = $kelasSiswa->kelas->fase_id ?? $faseId;
    $capaian = $subelemen->capaian->firstWhere('fase_id', $faseIdSiswa)?->capaian ?? '';
    $deskripsi = trim($subelemen->nama_sub_elemen . ' - ' . $capaian);

                    NilaiP5Detail::updateOrCreate(
                        [
                            'nilai_p5_id' => $nilaiP5->id,
                            'p5_sub_elemen_id' => $subelemenId,
                            'p5_dimensi_id' => $dimensiId,
                            // 'periode' => $request->periode,
                        ],
                        [
                            'predikat' => $predikat,
                            'deskripsi' => $deskripsi,
                            'is_validated' => false,
                        ]
                    );
                }
            }

            DB::commit();
            return redirect()->to(role_route('nilai-p5.index', [
                'tahun_semester_id' => $request->tahun_semester_id,
                'kelas_id' => $request->kelas_id,
                'proyek_id' => $request->proyek_id,
                'periode' => $request->periode,
            ]))->with('success', 'Nilai P5 berhasil disimpan.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan: ' . $th->getMessage());
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

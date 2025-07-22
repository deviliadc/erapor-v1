<?php

namespace App\Http\Controllers;

use App\Models\Ekstra;
use App\Models\Kelas;
use App\Models\KelasSiswa;
use App\Models\NilaiEkstra;
use App\Models\ParamEkstra;
use App\Models\TahunSemester;
use Illuminate\Http\Request;

class NilaiEkstraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $tahunSemesterList = TahunSemester::orderByDesc('tahun')->orderByDesc('semester')->get();
        $tahunAktif = TahunSemester::where('is_active', true)->first();

        $tahunSemesterId = $request->tahun_semester_id ?? $tahunAktif?->id;
        $kelasList = Kelas::all();
        $kelasId = $request->kelas_id ?? $kelasList->first()?->id;
        $periode = $request->input('periode', 'tengah');

        $daftarEkstra = Ekstra::all();
        $ekstraId = $request->ekstra_id ?? $daftarEkstra->first()?->id;

        $selectedTahunSemester = TahunSemester::find($tahunSemesterId);

        // Siswa per kelas
        $siswaKelas = KelasSiswa::with('siswa')
            ->where('kelas_id', $kelasId)
            ->where('tahun_semester_id', $tahunSemesterId)
            ->orderBy('no_absen')
            ->get();

        // Siapkan parameter dan nilai per ekstra
        $daftarParameter = [];
        $nilaiMap = [];
        foreach ($daftarEkstra as $ekstra) {
            $daftarParameter[$ekstra->id] = ParamEkstra::where('ekstra_id', $ekstra->id)->get();

            $nilaiList = NilaiEkstra::where('periode', $periode)
                ->where('ekstra_id', $ekstra->id)
                ->whereHas('kelasSiswa', function ($q) use ($tahunSemesterId, $kelasId) {
                    $q->where('tahun_semester_id', $tahunSemesterId)
                        ->where('kelas_id', $kelasId);
                })
                ->get();

            $nilaiMap[$ekstra->id] = [];
            foreach ($nilaiList as $nilai) {
                $nilaiMap[$ekstra->id][$nilai->kelas_siswa_id] = [
                    'param' => $nilai->param_nilai ?? [],
                ];
            }
        }

        $breadcrumbs = [
            ['label' => 'Nilai Ekstrakurikuler', 'url' => route('nilai-ekstra.index')],
        ];
        $title = 'Nilai Ekstrakurikuler';

        return view('nilai-ekstra.index', compact(
            'tahunSemesterList',
            'tahunAktif',
            'kelasList',
            'daftarEkstra',
            'tahunSemesterId',
            'kelasId',
            'ekstraId',
            'daftarParameter',
            'periode',
            'selectedTahunSemester',
            'nilaiMap',
            'siswaKelas',
            'breadcrumbs',
            'title'
        ));
    }


    public function updateBatch(Request $request)
    {
        $request->validate([
            'tahun_semester_id' => 'required|exists:tahun_semester,id',
            'periode' => 'required|in:tengah,akhir',
            'nilai' => 'required|array',
            'nilai.*.kelas_siswa_id' => 'required|exists:kelas_siswa,id',
            'nilai.*.ekstra_id' => 'required|exists:ekstra,id',
            'nilai.*.predikat' => 'required|array',
        ]);

        foreach ($request->nilai as $item) {
            $predikatParam = $item['predikat'] ?? [];
            $avg = collect($predikatParam)->filter(fn($v) => is_numeric($v))->avg();
            $nilaiAkhir = $avg !== null ? ceil($avg) : null;

            $deskripsi = '';
            if (!empty($predikatParam)) {
                $paramIds = array_keys($predikatParam);
                $params = ParamEkstra::whereIn('id', $paramIds)->pluck('parameter', 'id');

                $max = collect($predikatParam)->max();
                $min = collect($predikatParam)->min();

                $tertinggi = collect($predikatParam)->filter(fn($v) => $v == $max)->keys()->first();
                $terendah = collect($predikatParam)->filter(fn($v) => $v == $min)->keys()->first();

                $namaTertinggi = $params[$tertinggi] ?? '';
                $namaTerendah = $params[$terendah] ?? '';

                $text = [
                    0 => 'masih perlu bimbingan dalam',
                    1 => 'masih perlu bimbingan dalam',
                    2 => 'cukup mahir dalam',
                    3 => 'mahir dalam',
                    4 => 'sangat mahir dalam',
                ];

                $deskripsi = "Ananda " . ($item['nama'] ?? '') . " " .
                    ($text[$max] ?? '') . " " . $namaTertinggi .
                    " dan " .
                    ($text[$min] ?? '') . " " . $namaTerendah . ".";
            }

            NilaiEkstra::updateOrCreate(
                [
                    'kelas_siswa_id' => $item['kelas_siswa_id'],
                    'ekstra_id' => $item['ekstra_id'],
                    'periode' => $request->periode,
                ],
                [
                    'param_nilai' => [], // nilai angka jika ada
                    'predikat_param' => $predikatParam,
                    'nilai_akhir' => $nilaiAkhir,
                    'deskripsi' => $deskripsi,
                ]
            );
        }

        return back()->with('success', 'Nilai berhasil disimpan!');
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
        $request->validate([
            'kelas_id' => 'required',
            'ekstra_id' => 'required',
            'tahun_semester_id' => 'required',
            'nilai.*.siswa_id' => 'required|exists:siswa,id',
            'nilai.*.predikat' => 'required|in:A,B,C,D',
            'nilai.*.deskripsi' => 'nullable|string',
        ]);

        foreach ($request->nilai as $item) {
            NilaiEkstra::updateOrCreate(
                [
                    'siswa_id' => $item['siswa_id'],
                    'ekstra_id' => $request->ekstra_id,
                    'tahun_semester_id' => $request->tahun_semester_id,
                ],
                [
                    'predikat' => $item['predikat'],
                    'deskripsi' => $item['deskripsi'] ?? '',
                ]
            );
        }

        return redirect()->route('nilai-ekstra.index')->with('success', 'Nilai berhasil disimpan');
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

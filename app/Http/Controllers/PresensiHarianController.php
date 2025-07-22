<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\KelasSiswa;
use App\Models\PresensiDetail;
use App\Models\PresensiHarian;
use App\Models\TahunSemester;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PresensiHarianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $paginator = PresensiHarian::with('kelas')
        //     ->orderByDesc('tanggal')
        //     ->paginate(10)
        //     ->withQueryString();

        // $data = $paginator->getCollection()->map(function ($item) {
        //     return [
        //         'id' => $item->id,
        //         'tanggal' => $item->tanggal,
        //         'kelas' => $item->kelas->nama ?? '-',
        //         'catatan' => $item->catatan ?? '-',
        //     ];
        // });
        // $paginator->setCollection($data);
        $selectedTahun = $request->input('tahun_semester_id');

        $paginator = PresensiHarian::with('kelas')
            ->when($selectedTahun, function ($query) use ($selectedTahun) {
                $query->whereHas('detail.kelasSiswa', function ($q) use ($selectedTahun) {
                    $q->where('tahun_semester_id', $selectedTahun);
                });
            })
            ->orderByDesc('tanggal')
            ->paginate(10)
            ->withQueryString();

        $data = $paginator->getCollection()->map(function ($item) {
            return [
                'id' => $item->id,
                'tanggal' => $item->tanggal,
                'kelas' => $item->kelas->nama ?? '-',
                'catatan' => $item->catatan ?? '-',
            ];
        });
        $paginator->setCollection($data);

        $breadcrumbs = [
            ['label' => 'Presensi Harian', 'url' => route('presensi-harian.index')],
        ];

        $title = 'Presensi Harian';

        $tahun_semester = TahunSemester::orderByDesc('tahun')->get();

        return view('presensi-harian.index', [
            'data' => $paginator,
            'totalCount' => $paginator->total(),
            'breadcrumbs' => $breadcrumbs,
            'title' => $title,
            'tahun_semester' => $tahun_semester,
            'selectedTahun' => $selectedTahun,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // $kelas = Kelas::all();
        // $siswa = collect();
        // if ($request->kelas_id) {
        //     $siswa = Siswa::whereHas('kelas', function($q) use ($request) {
        //         $q->where('kelas.id', $request->kelas_id);
        //     })->get();
        // }
        // return view('presensi-harian.create', compact('kelas', 'siswa'));
        $kelas = Kelas::all();
        $siswa = collect();

        if ($request->kelas_id) {
            $tahun = TahunSemester::where('is_active', true)->first();

            $siswa = KelasSiswa::with('siswa')
                ->where('kelas_id', $request->kelas_id)
                ->where('tahun_semester_id', $tahun->id ?? null)
                ->orderBy('no_absen')
                ->get();
        }

        return view('presensi-harian.create', compact('kelas', 'siswa'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $tanggal = $request->tanggal;
        $kelas_id = $request->kelas_id;

        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'tanggal' => [
                'required',
                'date',
                function ($attribute, $value, $fail) use ($tanggal, $kelas_id) {
                    $exists = PresensiHarian::where('kelas_id', $kelas_id)
                        ->where('tanggal', $tanggal)
                        ->exists();
                    if ($exists) {
                        $fail('Presensi untuk kelas dan tanggal ini sudah ada.');
                    }
                }
            ],
            'catatan' => 'nullable|string|max:255',
        ]);


        // Cek atau buat presensi_harian
        $presensi = PresensiHarian::updateOrCreate(
            [
                'kelas_id' => $request->kelas_id,
                'tanggal' => $request->tanggal,
            ],
            [
                // 'input_by' => Auth::id(),
                'catatan' => $request->catatan,
            ]
        );

        // Loop setiap siswa
        foreach ($request->status as $kelas_siswa_id => $status) {
            PresensiDetail::updateOrCreate(
                [
                    'presensi_harian_id' => $presensi->id,
                    'kelas_siswa_id' => $kelas_siswa_id,
                ],
                [
                    'status' => $status,
                    'keterangan' => $request->keterangan[$kelas_siswa_id] ?? null,
                ]
            );
        }

        return redirect()->route('presensi-harian.index')->with('success', 'Presensi berhasil disimpan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return redirect()->route('presensi-detail.show', $id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $presensi = PresensiHarian::with('kelas')->findOrFail($id);

        // Agar bisa dipakai di @foreach pada edit.blade.php, bungkus dengan koleksi
        $presensiCollection = collect([$presensi]);

        return view('presensi-harian.edit', [
            'presensi' => $presensiCollection
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'catatan' => 'nullable|string|max:255',
        ]);

        $presensi = PresensiHarian::findOrFail($id);

        $presensi->update([
            'catatan' => $request->catatan,
        ]);

        return redirect()->route('presensi-harian.index')->with('success', 'Catatan presensi berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

    }
}

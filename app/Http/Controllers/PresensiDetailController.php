<?php

namespace App\Http\Controllers;

use App\Models\PresensiHarian;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class PresensiDetailController extends Controller
{
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
        $presensi = PresensiHarian::with(['detail.kelasSiswa.siswa', 'kelas'])->findOrFail($id);

        // Mapping data
        $data = $presensi->detail->map(function ($item) {
            return [
                'id' => $item->id,
                'no_absen' => $item->kelasSiswa->no_absen ?? '-',
                'nama_siswa' => $item->kelasSiswa->siswa->nama ?? '-',
                'status' => $item->status,
                'keterangan' => $item->keterangan ?? '-',
            ];
        });

        // Sorting: no_absen (jika ada), jika tidak, nama_siswa
        $data = $data->sortBy(function ($item) {
            return $item['no_absen'] !== null
                ? str_pad($item['no_absen'], 3, '0', STR_PAD_LEFT) // agar 1 < 10 < 100
                : strtolower($item['nama_siswa']);
        })->values(); // reset keys

        // Paginasi manual
        $paginator = new LengthAwarePaginator(
            $data->forPage(request('page', 1), 10),
            $data->count(),
            10,
            request('page', 1),
            ['path' => request()->url(), 'query' => request()->query()]
        );

        $title = 'Detail Presensi ' . $presensi->tanggal;
        $breadcrumbs = [
            ['label' => 'Presensi Harian', 'url' => route('presensi-harian.index')],
            ['label' => 'Detail Presensi']
        ];

        return view('presensi-detail.show', [
            'presensi' => $presensi,
            'title' => $title,
            'breadcrumbs' => $breadcrumbs,
            'data' => $paginator,
        ]);
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

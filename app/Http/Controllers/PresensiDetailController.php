<?php

namespace App\Http\Controllers;

use App\Models\PresensiDetail;
use App\Models\PresensiHarian;
use App\Models\RekapAbsensi;
use App\Models\TahunSemester;
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

        $title = 'Detail Presensi';
        $breadcrumbs = [
            ['label' => 'Presensi Harian', 'url' => role_route('presensi-harian.index')],
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
        $request->validate([
            'status' => 'required|string',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $presensi_detail = PresensiDetail::findOrFail($id);
        // $presensi_harian_id = $presensi_detail->presensi_harian_id;

        // $presensi_detail->update([
        //     'status' => $request->status,
        //     'keterangan' => $request->keterangan,
        // ]);

        // return redirect()->to(role_route('presensi-harian.show', ['presensi_harian' => $presensi_harian_id]))
        //     ->with('success', 'Presensi berhasil diperbarui.');

        $presensi_harian = $presensi_detail->presensiHarian; // relasi ke presensi_harian
        $tahunSemester = TahunSemester::where('is_active', true)->first();

        if (!$tahunSemester) {
            return back()->withErrors(['tahun_semester_id' => 'Tahun semester aktif tidak ditemukan.']);
        }

        // Simpan status lama untuk koreksi rekap
        $statusLama = $presensi_detail->status;

        // Update presensi detail
        $presensi_detail->update([
            'status' => $request->status,
            'keterangan' => $request->keterangan,
        ]);

        // Ambil atau buat rekap_absensi
        $rekap = RekapAbsensi::firstOrCreate(
            [
                'kelas_siswa_id' => $presensi_detail->kelas_siswa_id,
                'tahun_semester_id' => $tahunSemester->id,
                'periode' => $presensi_harian->periode,
            ],
            [
                // 'total_hadir' => 0,
                'total_sakit' => 0,
                'total_izin' => 0,
                'total_alfa' => 0,
            ]
        );

        // Kurangi count status lama
        switch ($statusLama) {
            // case 'Hadir':
            //     $rekap->decrement('total_hadir');
            //     break;
            case 'Sakit':
                $rekap->decrement('total_sakit');
                break;
            case 'Izin':
                $rekap->decrement('total_izin');
                break;
            case 'Alpha':
                $rekap->decrement('total_alfa');
                break;
        }

        // Tambahkan count status baru
        switch ($request->status) {
            // case 'Hadir':
            //     $rekap->increment('total_hadir');
            //     break;
            case 'Sakit':
                $rekap->increment('total_sakit');
                break;
            case 'Izin':
                $rekap->increment('total_izin');
                break;
            case 'Alpha':
                $rekap->increment('total_alfa');
                break;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

<?php

namespace App\Http\Controllers;

use App\Exports\ReusableExport;
use App\Models\Guru;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ArsipController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function siswa(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $sortBy = $request->input('sortBy', 'id');
        $sortDirection = $request->input('sortDirection', 'asc');
        $search = $request->input('search');

        $columnMap = [
            'id' => 'siswa.id',
            'name' => 'siswa.nama',
            'nipd' => 'siswa.nipd',
            'nisn' => 'siswa.nisn',
            'jenis_kelamin' => 'siswa.jenis_kelamin',
            'kelas' => 'kelas.nama',
            'status' => 'kelas_siswa.status',
        ];

        $query = Siswa::query()
            ->leftJoin('kelas_siswa', 'kelas_siswa.siswa_id', '=', 'siswa.id')
            ->leftJoin('kelas', 'kelas.id', '=', 'kelas_siswa.kelas_id')
            ->select('siswa.*', 'kelas.nama as kelas', 'kelas_siswa.status as status')
            ->where('kelas_siswa.status', '!=', 'Aktif');

        // Filter pencarian
        if ($search) {
            $query->where('siswa.nama', 'like', "%{$search}%")
                ->orWhere('siswa.nipd', 'like', "%{$search}%")
                ->orWhere('siswa.nisn', 'like', "%{$search}%");
        }

        // Sorting
        if (isset($columnMap[$sortBy])) {
            $query->orderBy($columnMap[$sortBy], $sortDirection);
        } else {
            $query->orderBy('siswa.id', 'asc');
        }

        $totalCount = $query->count();
        $paginator = $query->paginate($perPage)->withQueryString();

        $siswa = $paginator->through(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->nama,
                'nipd' => $item->nipd,
                'nisn' => $item->nisn,
                'jenis_kelamin' => $item->jenis_kelamin,
                'kelas' => $item->kelas ?? '-',
                'status' => $item->status ?? '-',
            ];
        });

        $breadcrumbs = [['label' => 'Arsip Siswa']];
        $title = 'Arsip Siswa';

        return view('arsip.siswa', compact('siswa', 'totalCount', 'breadcrumbs', 'title'));
    }

    /**
     * Display a listing of the resource.
     */
    public function guru(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $sortBy = $request->input('sortBy', 'id');
        $sortDirection = $request->input('sortDirection', 'asc');
        $search = $request->input('search');

        $columnMap = [
            'id' => 'guru.id',
            'name' => 'guru.nama',
            'nip' => 'guru.nip',
            'nuptk' => 'guru.nuptk',
            'status' => 'guru.status',
        ];

        $query = Guru::query()
            ->where('status', '!=', 'Aktif'); // hanya guru non-aktif


        if ($search) {
            $query->where('nama', 'like', "%{$search}%")
                ->orWhere('nip', 'like', "%{$search}%");
        }

        if (isset($columnMap[$sortBy])) {
            $query->orderBy($columnMap[$sortBy], $sortDirection);
        } else {
            $query->orderBy('guru.id', 'asc');
        }

        $totalCount = $query->count();
        $paginator = $query->paginate($perPage)->withQueryString();

        $guru = $paginator->through(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->nama,
                'nip' => $item->nip ?? '-',
                'nuptk' => $item->nuptk ?? '-',
                'status' => $item->status,
                'email' => $item->user->email ?? '-',
                'no_hp' => $item->no_hp ?? '-'
            ];
        });

        $breadcrumbs = [['label' => 'Arsip Guru']];
        $title = 'Arsip Guru';

        return view('arsip.guru', compact('guru', 'totalCount', 'breadcrumbs', 'title'));
    }

    public function exportSiswa(Request $request)
    {
        // $data = Siswa::with(['kelasSiswa.kelas'])->get();
        $data = Siswa::with(['kelasSiswa.kelas'])
            ->whereHas('kelasSiswa', function ($q) {
                $q->where('status', '!=', 'Aktif');
            })
            ->get();

        $headings = [
            'Nama',
            'NIPD',
            'NISN',
            'Jenis Kelamin',
            'Kelas',
            'Status'
        ];
        $enumInfo = [
            '',
            '',
            '',
            'Laki-laki/Perempuan',
            '',
            'Aktif/Lulus/Keluar/Mutasi'
        ];

        $formatted = $data->map(function ($siswa) {
            $kelasSiswa = $siswa->kelasSiswa->first();
            $kelasNama = $kelasSiswa && $kelasSiswa->kelas ? $kelasSiswa->kelas->nama : '-';
            $status = $kelasSiswa ? $kelasSiswa->status : '-';
            return [
                $siswa->nama,
                $siswa->nipd,
                $siswa->nisn,
                $siswa->jenis_kelamin,
                $kelasNama,
                $status,
            ];
        })->toArray();

        return Excel::download(
            new ReusableExport($headings, $enumInfo, $formatted),
            'arsip_siswa_' . now()->format('Ymd_His') . '.xlsx'
        );
    }

    public function exportGuru(Request $request)
    {
        // $data = Guru::all();
        $data = Guru::where('status', '!=', 'Aktif')->get();

        $headings = [
            'Nama',
            'NIP',
            'NUPTK',
            'Status'
        ];
        $enumInfo = [
            '',
            '',
            '',
            'Aktif/Pensiun/Mutasi/Resign'
        ];

        $formatted = $data->map(function ($guru) {
            return [
                $guru->nama,
                $guru->nip ? "'" . (string)$guru->nip : '-',
                $guru->nuptk ? "'" . (string)$guru->nuptk : '-',
                $guru->status ?? '-',
            ];
        })->toArray();

        return Excel::download(
            new ReusableExport($headings, $enumInfo, $formatted),
            'arsip_guru_' . now()->format('Ymd_His') . '.xlsx'
        );
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

<?php

namespace App\Http\Controllers;

use App\Models\Bab;
use App\Models\Guru;
use App\Models\GuruKelas;
use App\Models\Kelas;
use App\Models\LingkupMateri;
use App\Models\Mapel;
use App\Models\TujuanPembelajaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class MapelControllerr extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index(Request $request)
    // {
    //     $perPage = $request->input('per_page', 10);

    //     $query = Mapel::query();

    //     if ($search = $request->input('search')) {
    //         $query->where('nama', 'like', "%$search%")
    //             ->orWhere('kode_mapel', 'like', "%$search%");
    //     }

    //     $totalCount = $query->count();
    //     $paginator = $query->paginate($perPage)->withQueryString();

    //     // data untuk tampilan
    //     $mapel = $paginator;

    //     $breadcrumbs = [
    //         ['label' => 'Manage Mata Pelajaran', 'url' => route('mapel.index')]
    //     ];

    //     $title = 'Manage Mata Pelajaran';

    //     return view('mapel.index',  compact('mapel', 'totalCount', 'breadcrumbs', 'title'));
    // }
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);

        // Mapel
        $searchMapel = $request->input('search_mapel');
        $mapelQuery = Mapel::query();
        if ($searchMapel) {
            $mapelQuery->where('nama', 'like', "%$searchMapel%")
                ->orWhere('kode_mapel', 'like', "%$searchMapel%");
        }
        $mapelPaginator = $mapelQuery->paginate($perPage)->withQueryString();
        $mapel = $mapelPaginator->through(fn($item) => [
            'id' => $item->id,
            'nama' => $item->nama,
            'kode_mapel' => $item->kode_mapel,
            'kategori' => $item->kategori,
        ]);
        $mapelTotal = $mapelQuery->count();

        // Bab
        $searchBab = $request->input('search_bab');
        $babQuery = Bab::query();
        if ($searchBab) {
            $babQuery->where('nama', 'like', "%$searchBab%")
                ->orWhere('kode_bab', 'like', "%$searchBab%");
        }
        $babPaginator = $babQuery->paginate($perPage)->withQueryString();
        $bab = $babPaginator->through(fn($item) => [
            'id' => $item->id,
            'nama' => $item->nama,
            'kode_bab' => $item->kode_bab,
            'deskripsi' => $item->deskripsi ?? '-',
        ]);
        $babTotal = $babQuery->count();

        // Lingkup Materi
        $searchLingkupMateri = $request->input('search_lingkup_materi');
        $lingkupMateriQuery = LingkupMateri::query();
        if ($searchLingkupMateri) {
            $lingkupMateriQuery->where('nama', 'like', "%$searchLingkupMateri%")
                ->orWhere('kode_lingkup_materi', 'like', "%$searchLingkupMateri%");
        }
        $lingkupMateriPaginator = $lingkupMateriQuery->paginate($perPage)->withQueryString();
        $lingkupMateri = $lingkupMateriPaginator->through(fn($item) => [
            'id' => $item->id,
            'guru_kelas_id' => $item->guruKelas?->id ?? null,
            'kelas_id' => $item->guruKelas?->kelas_id ?? null,
            'kelas' => $item->guruKelas?->kelas?->nama ?? '-',
            'mapel_id' => $item->guruKelas?->mapel_id ?? null,
            'mapel' => $item->guruKelas?->mapel?->nama ?? '-',
            'bab_id' => $item->bab_id,
            'bab' => $item->bab?->nama ?? '-',
            'nama' => $item->nama,
            // 'kode_lingkup_materi' => $item->kode_lingkup_materi,
            // 'deskripsi' => $item->deskripsi ?? '-',
            'tujuan_pembelajaran_count' => $item->tujuanPembelajaran->count(),
        ]);
        $lingkupMateriTotal = $lingkupMateriQuery->count();

        // Tujuan Pembelajaran
        $searchTujuanPembelajaran = $request->input('search_tujuan_pembelajaran');
        $tujuanPembelajaranQuery = TujuanPembelajaran::with('lingkupMateri.guruKelas.kelas', 'lingkupMateri.guruKelas.mapel', 'lingkupMateri.bab');
        if ($searchTujuanPembelajaran) {
            $tujuanPembelajaranQuery->where('nama', 'like', "%$searchTujuanPembelajaran%")
                ->orWhere('kode_tujuan_pembelajaran', 'like', "%$searchTujuanPembelajaran%");
        }
        $tujuanPembelajaranPaginator = $tujuanPembelajaranQuery->paginate($perPage)->withQueryString();
        $tujuanPembelajaran = $tujuanPembelajaranPaginator->through(fn($item) => [
            'id' => $item->id,
            'subbab' => $item->subbab,
            'tujuan' => $item->tujuan,
            'lingkup_materi_id' => $item->lingkup_materi_id,
            'lingkup_materi' => $item->lingkupMateri?->nama ?? '-',
            'mapel' => $item->lingkupMateri?->guruKelas?->mapel?->nama ?? '-',
            'kelas' => $item->lingkupMateri?->guruKelas?->kelas?->nama ?? '-',
            'bab' => $item->lingkupMateri?->bab?->nama ?? '-',
        ]);
        $tujuanPembelajaranTotal = $tujuanPembelajaranQuery->count();

        // Kelas
        $searchKelas = $request->input('search_kelas');
        $kelasQuery = Kelas::query();
        if ($searchKelas) {
            $kelasQuery->where('nama_kelas', 'like', "%$searchKelas%")
                ->orWhere('kode_kelas', 'like', "%$searchKelas%");
        }
        $kelasPaginator = $kelasQuery->paginate($perPage)->withQueryString();
        $kelas = $kelasPaginator->through(fn($item) => [
            'id' => $item->id,
            'nama_kelas' => $item->nama_kelas,
            'kode_kelas' => $item->kode_kelas,
            'deskripsi' => $item->deskripsi ?? '-',
        ]);
        $kelasTotal = $kelasQuery->count();

        // Guru
        $searchGuru = $request->input('search_guru');
        $guruQuery = Guru::query();
        if ($searchGuru) {
            $guruQuery->where('nama', 'like', "%$searchGuru%")
                ->orWhere('nip', 'like', "%$searchGuru%")
                ->orWhere('email', 'like', "%$searchGuru%");
        }
        $guruPaginator = $guruQuery->paginate($perPage)->withQueryString();
        $guru = $guruPaginator->through(fn($item) => [
            'id' => $item->id,
            'nama' => $item->nama,
            'nip' => $item->nip,
            'email' => $item->user?->email ?? '-',
            'no_hp' => $item->no_hp,
            'alamat' => $item->alamat ?? '-',
            'jenis_kelamin' => $item->jenis_kelamin,
            'status' => $item->status,
        ]);
        $guruTotal = $guruQuery->count();

        // Data breadcrumb & view
        $breadcrumbs = [
            // ['label' => 'Dashboard', 'url' => route('dashboard')],
            ['label' => 'Master Data Mata Pelajaran'],
        ];

        $title = 'Master Data Mata Pelajaran';

        $kelasSelect = Kelas::pluck('nama', 'id')->toArray();
        $mapelSelect = Mapel::pluck('nama', 'id')->toArray();
        $guruSelect = Guru::pluck('nama', 'id')->toArray();
        $babSelect = Bab::pluck('nama', 'id')->toArray();
        $guruKelasSelect = GuruKelas::with('kelas', 'mapel')->get();
        $guruKelasAll = GuruKelas::with('mapel')
        ->where('peran', 'pengajar')
        ->get()
        ->mapWithKeys(function ($gk) {
            return [
                $gk->id => [
                    'kelas_id' => $gk->kelas_id,
                    'mapel' => $gk->mapel->nama ?? '-',
                ]
            ];
        })
        ->toArray();

        $lingkupMateriOptions = $lingkupMateri->mapWithKeys(function ($l) {
            return [
                $l['id'] => $l['nama']
                    . ' (Kelas ' . ($l['kelas'] ?? '-')
                    . ' - ' . ($l['mapel'] ?? '-')
                    . ' - ' . ($l['bab'] ?? '-') . ')'
            ];
        })->prepend('+ Tambah Lingkup Materi', 'tambah');

        return view('mapel.index', compact(
            'title',
            'breadcrumbs',
            'mapel', 'mapelTotal',
            'bab', 'babTotal',
            'lingkupMateri', 'lingkupMateriTotal',
            'tujuanPembelajaran', 'tujuanPembelajaranTotal',
            'kelas', 'kelasTotal',
            'guru', 'guruTotal',
            'kelasSelect', 'mapelSelect', 'babSelect', 'guruSelect', 'guruKelasSelect',
            'guruKelasAll', 'lingkupMateriOptions'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $breadcrumbs = [
            ['label' => 'Manage Mata Pelajaran', 'url' => route('mapel.index')],
            ['label' => 'Tambah Mapel']
        ];
        $title = 'Tambah Mata Pelajaran';

        return view('mapel.create', compact('breadcrumbs', 'title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_mapel' => 'required|string|max:20|unique:mapel,kode_mapel',
            'nama'       => 'required|string|max:100',
            'kategori'   => 'required|string|in:Wajib,Muatan Lokal'
        ]);

        Mapel::create($validated);

        return redirect()->route('mapel.index')->with('success', 'Mata pelajaran berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return redirect()->route(Str::replaceLast('.show', '.index', Route::currentRouteName()));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $mapel = Mapel::findOrFail($id);

        $breadcrumbs = [
            ['label' => 'Manage Mata Pelajaran', 'url' => route('mapel.index')],
            ['label' => 'Edit Mapel']
        ];
        $title = 'Edit Mata Pelajaran';

        return view('mapel.edit', compact('mapel', 'breadcrumbs', 'title'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $mapel = Mapel::findOrFail($id);

        $validated = $request->validate([
            'kode_mapel' => 'required|string|max:20|unique:mapel,kode_mapel,' . $mapel->id,
            'nama'       => 'required|string|max:100',
            'kategori'   => 'required|string|in:Wajib,Muatan Lokal'
        ]);

        $mapel->update($validated);

        return redirect()->route('mapel.index')->with('success', 'Mata pelajaran berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $mapel = Mapel::findOrFail($id);
            $mapel->delete();
            return redirect()->route('mapel.index')->with('success', 'Mata pelajaran berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('mapel.index')->with('error', 'Gagal menghapus mata pelajaran. Pastikan tidak sedang digunakan.');
        }
    }
}

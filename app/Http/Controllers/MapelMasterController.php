<?php

namespace App\Http\Controllers;

use App\Models\Mapel;
use App\Models\Bab;
use App\Models\LingkupMateri;
use App\Models\TujuanPembelajaran;
use App\Models\Kelas;
use App\Models\Guru;
use Illuminate\Http\Request;

class MapelMasterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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
            'nama' => $item->nama,
            'kode_lingkup_materi' => $item->kode_lingkup_materi,
            'deskripsi' => $item->deskripsi ?? '-',
        ]);
        $lingkupMateriTotal = $lingkupMateriQuery->count();

        // Tujuan Pembelajaran
        $searchTujuanPembelajaran = $request->input('search_tujuan_pembelajaran');
        $tujuanPembelajaranQuery = TujuanPembelajaran::query();
        if ($searchTujuanPembelajaran) {
            $tujuanPembelajaranQuery->where('nama', 'like', "%$searchTujuanPembelajaran%")
                ->orWhere('kode_tujuan_pembelajaran', 'like', "%$searchTujuanPembelajaran%");
        }
        $tujuanPembelajaranPaginator = $tujuanPembelajaranQuery->paginate($perPage)->withQueryString();
        $tujuanPembelajaran = $tujuanPembelajaranPaginator->through(fn($item) => [
            'id' => $item->id,
            'nama' => $item->nama,
            'kode_tujuan_pembelajaran' => $item->kode_tujuan_pembelajaran,
            'deskripsi' => $item->deskripsi ?? '-',
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

        return view('mapel.index', compact(
            'title',
            'breadcrumbs',
            'mapel', 'mapelTotal',
            'bab', 'babTotal',
            'lingkupMateri', 'lingkupMateriTotal',
            'tujuanPembelajaran', 'tujuanPembelajaranTotal',
            'kelas', 'kelasTotal',
            'guru', 'guruTotal',
        ));
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
    public function store()
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
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
    public function destroy()
    {
        //
    }
}

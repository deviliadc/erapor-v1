<?php

namespace App\Http\Controllers;

use App\Models\Bab;
use App\Models\Guru;
use App\Models\GuruKelas;
use App\Models\Kelas;
use App\Models\LingkupMateri;
use App\Models\Mapel;
use App\Models\TahunAjaran;
use App\Models\TahunSemester;
use App\Models\TujuanPembelajaran;
use Illuminate\Http\Request;

class MapelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);

        $mapel = $this->getMapelData($request, $perPage);
        $bab = $this->getBabData($request, $perPage);
        $lingkupMateri = $this->getLingkupMateriData($request, $perPage);
        $tujuanPembelajaran = $this->getTujuanPembelajaranData($request, $perPage);
        $kelas = $this->getKelasData($request, $perPage);
        $guru = $this->getGuruData($request, $perPage);
        $kelasMapel = $this->getKelasMapelData($request, $perPage);

        $breadcrumbs = [['label' => 'Data Mata Pelajaran']];
        $title = 'Data Mata Pelajaran';

        return view('mapel.index', array_merge(
            compact('title', 'breadcrumbs'),
            $mapel,
            $bab,
            $lingkupMateri,
            $tujuanPembelajaran,
            $kelas,
            $guru,
            $kelasMapel
        ));
    }

    private function getMapelData(Request $request, $perPage)
    {
        $search = $request->input('search_mapel');

        $query = Mapel::query();
        if ($search) {
            $query->where('nama', 'like', "%$search%")
                ->orWhere('kode_mapel', 'like', "%$search%");
        }

        $paginator = $query->paginate($perPage)->withQueryString();
        $data = $paginator->through(fn($item) => [
            // $data = $paginator->getCollection()->map(fn($item) => [
            'id' => $item->id,
            'kode_mapel' => $item->kode_mapel,
            'nama' => $item->nama,
            'kategori' => $item->kategori,
            // 'agama' => $item->agama ?? '-', // tambahkan kolom agama jika ada
            'urutan' => $item->urutan,
        ]);

        return [
            'mapel' => $data,
            'mapelTotal' => $query->count(),
        ];
    }

    private function getBabData(Request $request, $perPage)
    {
        $query = Bab::query();
        if ($search = $request->input('search_bab')) {
            $query->where('nama', 'like', "%$search%")
                ->orWhere('kode_bab', 'like', "%$search%");
        }

        $paginator = $query->paginate($perPage)->withQueryString();
        return [
            'bab' => $paginator->through(fn($item) => [
                'id' => $item->id,
                'nama' => $item->nama,
                'kode_bab' => $item->kode_bab,
                'deskripsi' => $item->deskripsi ?? '-',
            ]),
            'babTotal' => $query->count(),
            'babSelect' => Bab::pluck('nama', 'id')->toArray(),
        ];
    }

    private function getLingkupMateriData(Request $request, $perPage)
    {
        $kelasSelect = Kelas::pluck('nama', 'id')->toArray();
        $mapelSelect = Mapel::pluck('nama', 'id')->toArray();
        $babSelect = Bab::pluck('nama', 'id')->toArray();

        $query = LingkupMateri::query();
        if ($search = $request->input('search_lingkup_materi')) {
            $query->where('nama', 'like', "%$search%")
                ->orWhere('kode_lingkup_materi', 'like', "%$search%");
        }

        $paginator = $query->paginate($perPage)->withQueryString();
        $data = $paginator->through(fn($item) => [
            'id' => $item->id,
            // 'guru_kelas_id' => $item->guruKelas?->id,
            // 'kelas_id' => $item->guruKelas?->kelas_id,
            // 'kelas' => $item->guruKelas?->kelas?->nama ?? '-',
            // 'mapel_id' => $item->guruKelas?->mapel_id,
            // 'mapel' => $item->guruKelas?->mapel?->nama ?? '-',
            'mapel_id' => $item->mapel_id,
            'mapel' => $item->mapel?->nama ?? '-',
            'kelas_id' => $item->kelas_id,
            'kelas' => $item->kelas?->nama ?? '-',
            'bab_id' => $item->bab_id,
            'bab' => $item->bab?->nama ?? '-',
            'nama' => $item->nama,
            'tujuan_pembelajaran_count' => $item->tujuanPembelajaran->count(),
            'periode' => $item->periode ?? 'tengah', // tambahkan periode jika ada
        ]);

        // $options = $data->mapWithKeys(fn($l) => [
        //     $l['id'] => $l['nama'] . ' (Kelas ' . ($l['kelas'] ?? '-') . ' - ' . ($l['mapel'] ?? '-') . ' - ' . ($l['bab'] ?? '-') . ')'
        // ])->prepend('+ Tambah Lingkup Materi', 'tambah');

        return [
            'kelasSelect' => $kelasSelect,
            'mapelSelect' => $mapelSelect,
            'babSelect' => $babSelect,
            'lingkupMateri' => $data,
            'lingkupMateriTotal' => $query->count(),
            // 'lingkupMateriOptions' => $options,
        ];
    }

    private function getTujuanPembelajaranData(Request $request, $perPage)
    {
        // $query = TujuanPembelajaran::with('lingkupMateri.guruKelas.kelas', 'lingkupMateri.guruKelas.mapel', 'lingkupMateri.bab');
        $query = TujuanPembelajaran::with('lingkupMateri.mapel', 'lingkupMateri.kelas', 'lingkupMateri.bab');
        if ($search = $request->input('search_tujuan_pembelajaran')) {
            $query->where('nama', 'like', "%$search%")
                ->orWhere('kode_tujuan_pembelajaran', 'like', "%$search%");
        }

        $paginator = $query->paginate($perPage)->withQueryString();
        $data = $paginator->through(fn($item) => [
            'id' => $item->id,
            'subbab' => $item->subbab,
            'tujuan' => $item->tujuan,
            'lingkup_materi_id' => $item->lingkup_materi_id,
            'lingkup_materi' => $item->lingkupMateri?->nama ?? '-',
            // 'mapel' => $item->lingkupMateri?->guruKelas?->mapel?->nama ?? '-',
            // 'kelas' => $item->lingkupMateri?->guruKelas?->kelas?->nama ?? '-',
            'mapel_id' => $item->lingkupMateri?->mapel_id,
            'mapel' => $item->lingkupMateri?->mapel?->nama ?? '-',
            'kelas_id' => $item->lingkupMateri?->kelas_id,
            'kelas' => $item->lingkupMateri?->kelas?->nama ?? '-',
            'bab_id' => $item->lingkupMateri?->bab_id,
            'bab' => $item->lingkupMateri?->bab?->nama ?? '-',
        ]);

        $lingkupMateriAll = LingkupMateri::with('kelas', 'mapel', 'bab')->get();
        $lingkupMateriOptions = $lingkupMateriAll->mapWithKeys(function ($l) {
            return [
                $l->id => 'Kelas ' . ($l->kelas->nama ?? '-')
                    . ' - ' . ($l->mapel->nama ?? '-')
                    . ' - ' . ($l->bab->nama ?? '-')
                    . ' - ' . $l->nama
            ];
        });
        return [
            'tujuanPembelajaran' => $data,
            'tujuanPembelajaranTotal' => $query->count(),
            'lingkupMateriOptions' => $lingkupMateriOptions,
        ];
    }

    private function getKelasMapelData(Request $request, $perPage)
    {
        // $tahunAktif = TahunSemester::where('is_active', true)->first();
        // $tahunAktif = TahunAjaran::where('is_active', true)->first();

        $mapelList = GuruKelas::with(['guru', 'mapel', 'kelas'])
            // ->where('tahun_ajaran_id', $tahunAktif?->id)
            ->where('peran', 'pengajar')
            ->paginate($perPage)
            ->withQueryString();

        $mapelData = $mapelList->getCollection()->map(function ($item) {
            return [
                'id' => $item->id,
                'mapel_id' => $item->mapel_id,
                'guru_id' => $item->guru_id,
                'kelas_id' => $item->kelas_id,
                'nama_mapel' => $item->mapel->nama ?? '-',
                'nama_guru' => $item->guru->nama ?? '-',
                'nama_kelas' => $item->kelas->nama_kelas ?? '-',
            ];
        });

        $mapelList->setCollection($mapelData);

        // Ambil semua mapel & guru untuk select input
        $mapelOptions = Mapel::pluck('nama', 'id')->toArray();
        $guruOptions = Guru::pluck('nama', 'id')->toArray();

        return [
            'mapelList' => $mapelList,
            'mapelData' => $mapelData,
            'mapelTotal' => $mapelList->total(),
            'mapelOptions' => $mapelOptions,
            'guruOptions' => $guruOptions,
            // 'tahunAktif' => $tahunAktif,
        ];
    }

    private function getKelasData(Request $request, $perPage)
    {
        $query = Kelas::query();
        if ($search = $request->input('search_kelas')) {
            $query->where('nama_kelas', 'like', "%$search%")
                ->orWhere('kode_kelas', 'like', "%$search%");
        }

        $paginator = $query->paginate($perPage)->withQueryString();
        return [
            'kelas' => $paginator->through(fn($item) => [
                'id' => $item->id,
                'nama_kelas' => $item->nama_kelas,
                'kode_kelas' => $item->kode_kelas,
                'deskripsi' => $item->deskripsi ?? '-',
            ]),
            'kelasTotal' => $query->count(),
            'kelasSelect' => Kelas::pluck('nama', 'id')->toArray(),

        ];
    }

    private function getGuruData(Request $request, $perPage)
    {
        $query = Guru::query();
        if ($search = $request->input('search_guru')) {
            $query->where('nama', 'like', "%$search%")
                ->orWhere('nip', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%");
        }

        $paginator = $query->paginate($perPage)->withQueryString();
        $guruKelasSelect = GuruKelas::with('kelas', 'mapel')->get();
        $guruKelasAll = GuruKelas::with('mapel')
            ->where('peran', 'pengajar')
            ->get()
            ->mapWithKeys(fn($gk) => [
                $gk->id => [
                    'kelas_id' => $gk->kelas_id,
                    'mapel' => $gk->mapel->nama ?? '-',
                ]
            ])->toArray();

        return [
            'guru' => $paginator->through(fn($item) => [
                'id' => $item->id,
                'nama' => $item->nama,
                'nip' => $item->nip,
                'email' => $item->user?->email ?? '-',
                'no_hp' => $item->no_hp,
                'alamat' => $item->alamat ?? '-',
                'jenis_kelamin' => $item->jenis_kelamin,
                'status' => $item->status,
            ]),
            'guruTotal' => $query->count(),
            'guruSelect' => Guru::pluck('nama', 'id')->toArray(),
            'guruKelasSelect' => $guruKelasSelect,
            'guruKelasAll' => $guruKelasAll,
        ];
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // $mapel = new Mapel();

        // return view('mapel.create', [
        //     'mapel' => $mapel,
        // ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_mapel' => 'required|string|max:20|unique:mapel,kode_mapel',
            'nama'       => 'required|string|max:100',
            'kategori'   => 'required|string|in:Wajib,Muatan Lokal',
            'urutan'     => 'required|integer|min:1',
        ]);

        // urutan ngga boleh sama
        if (Mapel::where('urutan', $validated['urutan'])->exists()) {
            return redirect()->back()->withErrors(['urutan' => 'Urutan sudah digunakan.'])->withInput();
        }

        Mapel::create([
            'kode_mapel' => $validated['kode_mapel'],
            'nama'       => $validated['nama'],
            'kategori'   => $validated['kategori'],
            'urutan'     => $validated['urutan'],
        ]);

        return redirect()->to(role_route('mapel.index', ['tab' => $request->tab ?? 'mapel']))->with('success', 'Mata pelajaran berhasil ditambahkan.');
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
        // $mapel = Mapel::findOrFail($id);

        // $item = [
        //     'id' => $mapel->id,
        //     'kode_mapel' => $mapel->kode_mapel,
        //     'nama' => $mapel->nama,
        //     'kategori' => $mapel->kategori,
        // ];

        // return view('mapel.edit', compact('mapel', 'item'));
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
            'kategori'   => 'required|string|in:Wajib,Muatan Lokal',
            'urutan'     => 'required|integer|min:1',
        ]);

        $mapel->update([
            'kode_mapel' => $validated['kode_mapel'],
            'nama'       => $validated['nama'],
            'kategori'   => $validated['kategori']
        ]);

        return redirect()->to(role_route('mapel.index', ['tab' => $request->tab ?? 'mapel']))->with('success', 'Mata pelajaran berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $mapel = Mapel::findOrFail($id);

            // Cek relasi ke GuruKelas, LingkupMateri, Bab, TujuanPembelajaran, dll
            $related = (
                $mapel->guruKelas()->exists() ||
                $mapel->lingkupMateri()->exists() ||
                // $mapel->bab()->exists() ||
                $mapel->tujuanPembelajaran()->exists()
            );

            if ($related) {
                return redirect()->to(role_route('mapel.index', ['tab' => request('tab', 'mapel')]))
                    ->with('error', 'Mata pelajaran tidak bisa dihapus karena masih digunakan pada data lain.');
            }

            $mapel->delete();
            return redirect()->to(role_route('mapel.index', ['tab' => request('tab', 'mapel')]))
                ->with('success', 'Mata pelajaran berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->to(role_route('mapel.index', ['tab' => request('tab', 'mapel')]))
                ->with('error', 'Gagal menghapus mata pelajaran. Pastikan tidak sedang digunakan.');
        }
    }
}

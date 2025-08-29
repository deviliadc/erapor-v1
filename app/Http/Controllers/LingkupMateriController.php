<?php

namespace App\Http\Controllers;

use App\Models\Bab;
use App\Models\GuruKelas;
use App\Models\Kelas;
use App\Models\LingkupMateri;
use App\Models\Mapel;
use App\Models\TujuanPembelajaran;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LingkupMateriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // $kelas = Kelas::pluck('nama', 'id')->toArray();
        // $bab = Bab::pluck('nama', 'id')->toArray();
        // $mapel = Mapel::pluck('nama', 'id')->toArray();
        // $guruKelas = GuruKelas::with(['kelas', 'mapel', 'guru'])
        //     ->where('peran', 'pengajar')
        //     ->get()
        //     ->map(fn($gk) => [
        //         'id' => $gk->id,
        //         'mapel' => $gk->mapel->nama,
        //     ]);

        // return view('lingkup-materi.create', compact(
        //     'kelas',
        //     'bab',
        //     'mapel'
        // ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'mapel_id' => 'required|exists:mapel,id',
            'bab_id' => 'required|exists:bab,id',
            'nama' => [
                'required',
                'string',
                'max:255',
                Rule::unique('lingkup_materi')->where(function ($query) use ($request) {
                    return $query->where('kelas_id', $request->kelas_id)
                        ->where('mapel_id', $request->mapel_id)
                        ->where('bab_id', $request->bab_id);
                }),
            ],
            'periode' => 'required|in:tengah,akhir',
            'semester' => 'required|in:genap,ganjil',
        ]);
        $exists = LingkupMateri::where('kelas_id', $request->kelas_id)
            ->where('mapel_id', $request->mapel_id)
            ->where('bab_id', $request->bab_id)
            ->exists();
        if ($exists) {
            return redirect()->back()->withErrors([
                'bab_id' => 'Lingkup materi untuk kombinasi guru dan bab ini sudah ada.'
            ])->withInput();
        }
        LingkupMateri::create([
            'kelas_id' => $request->kelas_id,
            'mapel_id' => $request->mapel_id,
            'bab_id' => $request->bab_id,
            'nama' => $request->nama,
            'periode' => $request->periode,
            'semester' => $request->semester,
        ]);
        return redirect()->to(role_route('mapel.index', ['tab' => $request->tab ?? 'lingkup-materi']))->with('success', 'Lingkup Materi berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $perPage = $request->input('per_page', 10);
        $search = $request->input('search');
        $lingkupMateri = LingkupMateri::with(['kelas', 'mapel', 'bab'])
            ->where('id', $id)
            ->firstOrFail();
        $query = TujuanPembelajaran::where('lingkup_materi_id', $id);
        if ($search) {
            $query->where('tujuan', 'like', "%$search%")
                ->orWhere('subbab', 'like', "%$search%");
        }
        $paginator = $query->paginate($perPage)->withQueryString();
        $tujuan_pembelajaran = $paginator->through(fn($item) => [
            'id' => $item->id,
            'subbab' => $item->subbab,
            'tujuan_pembelajaran' => $item->tujuan,
        ]);
        $breadcrumbs = [
            ['label' => 'Manage Lingkup Materi', 'url' => role_route('mapel.index', ['tab' => $request->tab ?? 'lingkup-materi'])],
            ['label' => 'Detail Lingkup Materi']
        ];
        $title = 'Detail Lingkup Materi';
        return view('lingkup-materi.show', [
            'lingkupMateri' => $lingkupMateri,
            'tujuan_pembelajaran' => $tujuan_pembelajaran,
            'totalCount' => $paginator->total(),
            'breadcrumbs' => $breadcrumbs,
            'title' => $title,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // $lingkupMateri = LingkupMateri::with('guruKelas.kelas')->findOrFail($id);
        // $kelas = Kelas::pluck('nama', 'id'); // hanya ambil id dan nama
        // $bab = Bab::pluck('nama', 'id');     // hanya ambil id dan nama
        // $mapel = Mapel::pluck('nama', 'id'); // hanya ambil id dan nama
        // $guruKelasAll = GuruKelas::with('mapel')
        //     ->where('peran', 'pengajar')
        //     ->get()
        //     ->mapWithKeys(function ($gk) {
        //         return [
        //             $gk->id => [
        //                 'kelas_id' => $gk->kelas_id,
        //                 'mapel' => $gk->mapel->nama ?? '-',
        //                 'periode' => $gk->periode ?? 'tengah', // tambahkan periode jika ada
        //             ]
        //         ];
        //     })
        //     ->toArray();

        // $breadcrumbs = [
        //     ['label' => 'Manage Lingkup Materi', 'url' => role_route('mapel.index', ['tab' => $request->tab ?? 'lingkup-materi'])],
        //     ['label' => 'Edit Lingkup Materi']
        // ];
        // $title = 'Edit Lingkup Materi';

        // return view('lingkup-materi.edit', compact(
        //     'lingkupMateri',
        //     'kelas',
        //     'bab',
        //     'mapel',
        //     // 'guruKelasAll',
        //     'breadcrumbs',
        //     'title'
        // ));
    }

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, string $id)
    // {
    //     $request->validate([
    //         // 'guru_kelas_id' => 'required|exists:guru_kelas,id',
    //         'kelas_id' => 'required|exists:kelas,id',
    //         'mapel_id' => 'required|exists:mapel,id',
    //         'bab_id' => 'required|exists:bab,id',
    //         'nama' => [
    //             'required',
    //             'string',
    //             'max:255',
    //             Rule::unique('lingkup_materi')->where(function ($query) use ($request) {
    //                 // return $query->where('guru_kelas_id', $request->guru_kelas_id)
    //                 //     ->where('bab_id', $request->bab_id);
    //                 return $query->where('kelas_id', $request->kelas_id)
    //                     ->where('mapel_id', $request->mapel_id)
    //                     ->where('bab_id', $request->bab_id);
    //             })->ignore($id),
    //         ],
    //         'periode' => Rule::in(['tengah', 'akhir']),
    //         'semester' => Rule::in(['genap', 'ganjil']),
    //     ]);

    //     $lingkup_materi = LingkupMateri::findOrFail($id);

    //     $duplicate = LingkupMateri::where('id', '!=', $id)
    //         // ->where('guru_kelas_id', $request->guru_kelas_id)
    //         ->where('kelas_id', $request->kelas_id)
    //         ->where('mapel_id', $request->mapel_id)
    //         ->where('bab_id', $request->bab_id)
    //         ->exists();

    //     if ($duplicate) {
    //         return back()->withErrors(['bab_id' => 'Kombinasi guru dan bab ini sudah digunakan.']);
    //     }

    //     $lingkup_materi->update([
    //         // 'guru_kelas_id' => $request->guru_kelas_id,
    //         'kelas_id' => $request->kelas_id,
    //         'mapel_id' => $request->mapel_id,
    //         'bab_id' => $request->bab_id,
    //         'nama' => $request->nama,
    //         'periode' => $request->periode,
    //         'semester' => $request->semester,
    //     ]);

    //     return redirect()->to(role_route('mapel.index', ['tab' => $request->tab ?? 'lingkup-materi']))->with('success', 'Lingkup Materi berhasil diperbarui.');
    // }
    public function update(Request $request, string $id)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'mapel_id' => 'required|exists:mapel,id',
            'bab_id' => 'required|exists:bab,id',
            'nama' => [
                'required',
                'string',
                'max:255',
                Rule::unique('lingkup_materi')->where(function ($query) use ($request) {
                    return $query->where('kelas_id', $request->kelas_id)
                        ->where('mapel_id', $request->mapel_id)
                        ->where('bab_id', $request->bab_id);
                })->ignore($id),
            ],
            'periode' => Rule::in(['tengah', 'akhir']),
            'semester' => Rule::in(['genap', 'ganjil']),
        ]);

        // Ambil data lama dari database
        $lingkup_materi = LingkupMateri::findOrFail($id);

        // Susun data baru yang diterima dari form
        $newData = [
            'kelas_id' => $request->kelas_id,
            'mapel_id' => $request->mapel_id,
            'bab_id'   => $request->bab_id,
            'nama'     => $request->nama,
            'periode'  => $request->periode,
            'semester' => $request->semester,
        ];

        // Periksa apakah ada perubahan antara data lama dan data baru
        $isChanged = false;
        foreach ($newData as $key => $value) {
            if ($lingkup_materi->{$key} != $value) {
                $isChanged = true;
                break; // Keluar dari loop jika ada perubahan
            }
        }

        // Jika tidak ada perubahan, return tanpa update
        if (!$isChanged) {
            return redirect()->to(role_route('mapel.index', ['tab' => $request->tab ?? 'lingkup-materi']))
                ->with('info', 'Tidak ada perubahan pada data.');
        }

        // Cek jika ada duplikat kombinasi kelas, mapel, bab
        $duplicate = LingkupMateri::where('id', '!=', $id)
            ->where('kelas_id', $request->kelas_id)
            ->where('mapel_id', $request->mapel_id)
            ->where('bab_id', $request->bab_id)
            ->exists();

        if ($duplicate) {
            return back()->withErrors(['bab_id' => 'Kombinasi kelas, mapel, dan bab ini sudah digunakan.']);
        }

        // Update data jika ada perubahan
        $lingkup_materi->update($newData);

        return redirect()->to(role_route('mapel.index', ['tab' => $request->tab ?? 'lingkup-materi']))
            ->with('success', 'Lingkup Materi berhasil diperbarui.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        try {
            $lingkupMateri = LingkupMateri::findOrFail($id);
            $lingkupMateri->delete();

            return redirect()->to(role_route('mapel.index', ['tab' => $request->tab ?? 'lingkup-materi']))->with('success', 'Lingkup Materi berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->to(role_route('mapel.index', ['tab' => $request->tab ?? 'lingkup-materi']))->with('error', 'Gagal menghapus data. Pastikan tidak sedang digunakan.');
        }
    }

    // public function getByKelasMapelBab(Request $request)
    // {
    //     $kelasId = $request->input('k');
    //     $mapelId = $request->input('m');
    //     $babId   = $request->input('b');

    //     $data = LingkupMateri::where('kelas_id', $kelasId)
    //         ->where('mapel_id', $mapelId)
    //         ->where('bab_id', $babId)
    //         ->get(['id', 'lingkup_materi']);

    //     return response()->json($data);
    // }

    // public function getByKelas(Request $request, $kelasId)
    // {
    //     $data = GuruKelas::with('mapel')
    //         ->where('kelas_id', $kelasId)
    //         ->where('peran', 'pengajar')
    //         ->get()
    //         ->map(fn($gk) => [
    //             'id' => $gk->id,
    //             'mapel' => $gk->mapel->nama,
    //         ]);

    //     return response()->json($data);
    // }

    // public function duplikatLingkupMateri(Request $request)
    // {
    //     $request->validate([
    //         'source_guru_kelas_id' => 'required|exists:guru_kelas,id',
    //         'target_guru_kelas_id' => 'required|exists:guru_kelas,id|different:source_guru_kelas_id',
    //     ]);

    //     $sourceId = $request->source_guru_kelas_id;
    //     $targetId = $request->target_guru_kelas_id;

    //     $sourceLingkupList = LingkupMateri::where('guru_kelas_id', $sourceId)->get();

    //     foreach ($sourceLingkupList as $sourceLingkup) {
    //         $newLingkup = LingkupMateri::create([
    //             'guru_kelas_id' => $targetId,
    //             'bab_id' => $sourceLingkup->bab_id,
    //             'nama' => $sourceLingkup->nama,
    //         ]);

    //         foreach ($sourceLingkup->tujuanPembelajaran as $tp) {
    //             TujuanPembelajaran::create([
    //                 'lingkup_materi_id' => $newLingkup->id,
    //                 'nama' => $tp->nama,
    //                 'kode_tujuan_pembelajaran' => $tp->kode_tujuan_pembelajaran,
    //                 'deskripsi' => $tp->deskripsi,
    //             ]);
    //         }
    //     }

    //     return back()->with('success', 'Data lingkup materi & tujuan pembelajaran berhasil diduplikat.');
    // }
}

<?php

namespace App\Http\Controllers;

use App\Models\Bab;
use App\Models\GuruKelas;
use App\Models\Kelas;
use App\Models\LingkupMateri;
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
    //     $perPage = $request->input('per_page', 10);

    //     $query = LingkupMateri::with([
    //         'guruKelas.kelas',
    //         'guruKelas.mapel',
    //         'guruKelas.tahunSemester',
    //         'bab'
    //     ])->withCount('tujuanPembelajaran');

    //     if ($search = $request->input('search')) {
    //         $query->where('nama', 'like', "%$search%")
    //             ->orWhereHas('guruKelas.kelas', fn($q) => $q->where('nama', 'like', "%$search%"))
    //             ->orWhereHas('guruKelas.mapel', fn($q) => $q->where('nama', 'like', "%$search%"))
    //             ->orWhereHas('bab', fn($q) => $q->where('nama', 'like', "%$search%"));
    //     }

    //     $paginator = $query->paginate($perPage)->withQueryString();
    //     $totalCount = $paginator->total();

    //     // Modal
    //     $kelas = Kelas::pluck('nama', 'id');
    //     $bab = Bab::pluck('nama', 'id');
    //     $guruKelasAll = GuruKelas::with('mapel')
    //         ->where('peran', 'pengajar')
    //         ->get()
    //         ->mapWithKeys(function ($gk) {
    //             return [
    //                 $gk->id => [
    //                     'kelas_id' => $gk->kelas_id,
    //                     'mapel' => $gk->mapel->nama ?? '-',
    //                 ]
    //             ];
    //         })
    //         ->toArray();

    //     $lingkup_materi = $paginator;

    //     $breadcrumbs = [
    //         ['label' => 'Manage Lingkup Materi', 'url' => route('lingkup-materi.index')]
    //     ];
    //     $title = 'Manage Lingkup Materi';

    //     return view('lingkup-materi.index', compact('guruKelasAll', 'lingkup_materi', 'totalCount', 'breadcrumbs', 'title', 'kelas', 'bab'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kelas = Kelas::pluck('nama', 'id')->toArray();
        $bab = Bab::pluck('nama', 'id')->toArray();
        $guruKelas = GuruKelas::with(['kelas', 'mapel', 'guru'])
            ->where('peran', 'pengajar')
            ->get()
            ->map(fn($gk) => [
                'id' => $gk->id,
                'mapel' => $gk->mapel->nama,
            ]);

        return view('lingkup-materi.create', compact('kelas', 'bab', 'guruKelas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'guru_kelas_id' => 'required|exists:guru_kelas,id',
            'bab_id' => 'required|exists:bab,id',
            'nama' => [
                'required',
                'string',
                'max:255',
                Rule::unique('lingkup_materi')->where(function ($query) use ($request) {
                    return $query->where('guru_kelas_id', $request->guru_kelas_id)
                        ->where('bab_id', $request->bab_id);
                }),
            ],
            'periode' => 'required|in:tengah,akhir',
        ]);

        $exists = LingkupMateri::where('guru_kelas_id', $request->guru_kelas_id)
            ->where('bab_id', $request->bab_id)
            ->exists();

        if ($exists) {
            return redirect()->back()->withErrors([
                'bab_id' => 'Lingkup materi untuk kombinasi guru dan bab ini sudah ada.'
            ])->withInput();
        }

        LingkupMateri::create([
            'guru_kelas_id' => $request->guru_kelas_id,
            'bab_id' => $request->bab_id,
            'nama' => $request->nama,
            'periode' => $request->periode,
        ]);

        return redirect()->route('mapel.index', ['tab' => $request->tab ?? 'lingkup-materi'])->with('success', 'Lingkup Materi berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $perPage = $request->input('per_page', 10);
        $search = $request->input('search');

        $lingkupMateri = LingkupMateri::with(['guruKelas.kelas', 'guruKelas.mapel', 'bab'])->findOrFail($id);

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
            // 'periode' => $item->periode,
        ]);

        $breadcrumbs = [
            ['label' => 'Manage Lingkup Materi', 'url' => route('mapel.index', ['tab' => $request->tab ?? 'lingkup-materi'])],
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
        $lingkupMateri = LingkupMateri::with('guruKelas.kelas')->findOrFail($id);
        $kelas = Kelas::pluck('nama', 'id'); // hanya ambil id dan nama
        $bab = Bab::pluck('nama', 'id');     // hanya ambil id dan nama
        $guruKelasAll = GuruKelas::with('mapel')
            ->where('peran', 'pengajar')
            ->get()
            ->mapWithKeys(function ($gk) {
                return [
                    $gk->id => [
                        'kelas_id' => $gk->kelas_id,
                        'mapel' => $gk->mapel->nama ?? '-',
                        'periode' => $gk->periode ?? 'tengah', // tambahkan periode jika ada
                    ]
                ];
            })
            ->toArray();

        $breadcrumbs = [
            ['label' => 'Manage Lingkup Materi', 'url' => route('mapel.index', ['tab' => $request->tab ?? 'lingkup-materi'])],
            ['label' => 'Edit Lingkup Materi']
        ];
        $title = 'Edit Lingkup Materi';

        return view('lingkup-materi.edit', compact('lingkupMateri', 'kelas', 'bab', 'guruKelasAll', 'breadcrumbs', 'title'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'guru_kelas_id' => 'required|exists:guru_kelas,id',
            'bab_id' => 'required|exists:bab,id',
            'nama' => [
                'required',
                'string',
                'max:255',
                Rule::unique('lingkup_materi')->where(function ($query) use ($request) {
                    return $query->where('guru_kelas_id', $request->guru_kelas_id)
                        ->where('bab_id', $request->bab_id);
                })->ignore($id),
            ],
            'periode' => Rule::in(['tengah', 'akhir']),
        ]);

        $lingkup_materi = LingkupMateri::findOrFail($id);

        $duplicate = LingkupMateri::where('id', '!=', $id)
            ->where('guru_kelas_id', $request->guru_kelas_id)
            ->where('bab_id', $request->bab_id)
            ->exists();

        if ($duplicate) {
            return back()->withErrors(['bab_id' => 'Kombinasi guru dan bab ini sudah digunakan.']);
        }

        $lingkup_materi->update([
            'guru_kelas_id' => $request->guru_kelas_id,
            'bab_id' => $request->bab_id,
            'nama' => $request->nama,
            'periode' => $request->periode,
        ]);

        return redirect()->route('mapel.index', ['tab' => $request->tab ?? 'lingkup-materi'])->with('success', 'Lingkup Materi berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $lingkupMateri = LingkupMateri::findOrFail($id);
            $lingkupMateri->delete();

            return redirect()->route('mapel.index', ['tab' => $request->tab ?? 'lingkup-materi'])->with('success', 'Lingkup Materi berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('mapel.index', ['tab' => $request->tab ?? 'lingkup-materi'])->with('error', 'Gagal menghapus data. Pastikan tidak sedang digunakan.');
        }
    }

    public function getByKelasMapelBab(Request $request)
    {
        $kelasId = $request->input('k');
        $mapelId = $request->input('m');
        $babId   = $request->input('b');

        $data = LingkupMateri::where('kelas_id', $kelasId)
            ->where('mapel_id', $mapelId)
            ->where('bab_id', $babId)
            ->get(['id', 'lingkup_materi']);

        return response()->json($data);
    }

    public function getByKelas(Request $request, $kelasId)
    {
        $data = GuruKelas::with('mapel')
            ->where('kelas_id', $kelasId)
            ->where('peran', 'pengajar')
            ->get()
            ->map(fn($gk) => [
                'id' => $gk->id,
                'mapel' => $gk->mapel->nama,
            ]);

        return response()->json($data);
    }

    public function duplikatLingkupMateri(Request $request)
    {
        $request->validate([
            'source_guru_kelas_id' => 'required|exists:guru_kelas,id',
            'target_guru_kelas_id' => 'required|exists:guru_kelas,id|different:source_guru_kelas_id',
        ]);

        $sourceId = $request->source_guru_kelas_id;
        $targetId = $request->target_guru_kelas_id;

        $sourceLingkupList = LingkupMateri::where('guru_kelas_id', $sourceId)->get();

        foreach ($sourceLingkupList as $sourceLingkup) {
            $newLingkup = LingkupMateri::create([
                'guru_kelas_id' => $targetId,
                'bab_id' => $sourceLingkup->bab_id,
                'nama' => $sourceLingkup->nama,
            ]);

            foreach ($sourceLingkup->tujuanPembelajaran as $tp) {
                TujuanPembelajaran::create([
                    'lingkup_materi_id' => $newLingkup->id,
                    'nama' => $tp->nama,
                    'kode_tujuan_pembelajaran' => $tp->kode_tujuan_pembelajaran,
                    'deskripsi' => $tp->deskripsi,
                ]);
            }
        }

        return back()->with('success', 'Data lingkup materi & tujuan pembelajaran berhasil diduplikat.');
    }
}

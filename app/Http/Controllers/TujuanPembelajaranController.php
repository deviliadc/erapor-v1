<?php

namespace App\Http\Controllers;

use App\Models\Bab;
use App\Models\GuruKelas;
use App\Models\LingkupMateri;
use App\Models\TujuanPembelajaran;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class TujuanPembelajaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $perPage = $request->input('per_page', 10);

        // $query = TujuanPembelajaran::with('lingkupMateri.guruKelas.kelas', 'lingkupMateri.guruKelas.mapel', 'lingkupMateri.bab');

        // if ($search = $request->input('search')) {
        //     $query->where('tujuan', 'like', "%$search%")
        //         ->orWhereHas('lingkupMateri.guruKelas.mapel', fn($q) => $q->where('nama', 'like', "%$search%"))
        //         ->orWhereHas('lingkupMateri.bab', fn($q) => $q->where('nama', 'like', "%$search%"));
        // }

        // $paginator = $query->paginate($perPage)->withQueryString();
        // $totalCount = $paginator->total();

        // $tujuan_pembelajaran = $paginator->through(fn($item) => [
        //     'id' => $item->id,
        //     'subbab' => $item->subbab,
        //     'tujuan' => $item->tujuan,
        //     'lingkup_materi_id' => $item->lingkup_materi_id,
        //     'lingkup_materi' => optional($item->lingkupMateri)->lingkup_materi,
        //     'mapel' => optional(optional($item->lingkupMateri->guruKelas)->mapel)->nama,
        //     'bab' => optional($item->lingkupMateri->bab)->nama,
        //     'kelas' => optional(optional($item->lingkupMateri->guruKelas)->kelas)->nama,
        // ]);

        // $breadcrumbs = [
        //     ['label' => 'Manage Tujuan Pembelajaran', 'url' => route('tujuan-pembelajaran.index')]
        // ];
        // $title = 'Manage Tujuan Pembelajaran';

        // $lingkupMateri = LingkupMateri::with('guruKelas.kelas', 'guruKelas.mapel', 'bab')->get();
        // $bab = Bab::pluck('nama', 'id');
        // $guruKelas = GuruKelas::with('kelas', 'mapel')->get();

        // $lingkupMateriOptions = $lingkupMateri->mapWithKeys(function($l) {
        //     return [
        //         $l->id => $l->lingkup_materi . ' (Kelas ' . optional($l->guruKelas->kelas)->nama . ' - ' . optional($l->guruKelas->mapel)->nama . ' - ' . optional($l->bab)->nama . ')'
        //     ];
        // })->prepend('+ Tambah Lingkup Materi', 'tambah');

        // return view('tujuan-pembelajaran.index', compact('tujuan_pembelajaran', 'totalCount', 'breadcrumbs', 'title', 'lingkupMateri', 'bab', 'guruKelas', 'lingkupMateriOptions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $bab = Bab::pluck('nama', 'id');
        $guruKelas = GuruKelas::with('kelas', 'mapel')->get();

        $lingkupMateri = LingkupMateri::with('guruKelas.kelas', 'guruKelas.mapel', 'bab')->get();

        $lingkupMateriOptions = $lingkupMateri->mapWithKeys(function ($l) {
            return [
                $l->id => $l->lingkup_materi . ' (Kelas ' . optional($l->guruKelas->kelas)->nama . ' - ' . optional($l->guruKelas->mapel)->nama . ' - ' . optional($l->bab)->nama . ')'
            ];
        })->prepend('+ Tambah Lingkup Materi', 'tambah');

        $breadcrumbs = [
            ['label' => 'Manage Tujuan Pembelajaran', 'url' => route('mapel.index', ['tab' => $request->tab ?? 'tujuan-pembelajaran'])],
            ['label' => 'Tambah Tujuan Pembelajaran']
        ];

        $title = 'Tambah Tujuan Pembelajaran';

        return view('tujuan-pembelajaran.create', compact('bab', 'guruKelas', 'lingkupMateri', 'lingkupMateriOptions', 'breadcrumbs', 'title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $isTambah = $request->lingkup_materi_id === 'tambah';

            $rules = [
                'subbab' => [
                    'nullable',
                    'string',
                    'max:255',
                    function ($attribute, $value, $fail) use ($request, $isTambah) {
                        $lingkupMateriId = $isTambah
                            ? LingkupMateri::where('guru_kelas_id', $request->guru_kelas_id)
                            ->where('bab_id', $request->bab_id)
                            ->where('nama', $request->lingkup_materi)
                            ->value('id')
                            : $request->lingkup_materi_id;

                        if ($value && $lingkupMateriId) {
                            $exists = TujuanPembelajaran::where('subbab', $value)
                                ->where('lingkup_materi_id', $lingkupMateriId)
                                ->exists();
                            if ($exists) {
                                $fail('Subbab sudah ada pada lingkup materi ini.');
                            }
                        }
                    }
                ],
                'tujuan' => 'required|string',
            ];

            if ($isTambah) {
                $rules += [
                    'lingkup_materi_id' => 'required|string|in:tambah',
                    'guru_kelas_id' => 'required|exists:guru_kelas,id',
                    'bab_id' => 'required|exists:bab,id',
                    'lingkup_materi' => 'required|string|max:255',
                ];
            } else {
                $rules['lingkup_materi_id'] = 'required|exists:lingkup_materi,id';
            }

            $validated = $request->validate($rules);

            // Simpan atau ambil ID lingkup materi
            if ($isTambah) {
                $lingkup = LingkupMateri::firstOrCreate([
                    'guru_kelas_id' => $validated['guru_kelas_id'],
                    'bab_id' => $validated['bab_id'],
                    'nama' => $validated['lingkup_materi'],
                ]);
                $lingkupMateriId = $lingkup->id;
            } else {
                $lingkupMateriId = $validated['lingkup_materi_id'];
            }

            TujuanPembelajaran::create([
                'lingkup_materi_id' => $lingkupMateriId,
                'subbab' => $validated['subbab'] ?? null,
                'tujuan' => $validated['tujuan'],
            ]);

            return redirect($request->input('redirect_to', route('mapel.index', ['tab' => $request->tab ?? 'tujuan-pembelajaran'])))
                ->with('success', 'Tujuan Pembelajaran berhasil ditambahkan.');
        } catch (QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                return back()->withErrors(['nama' => 'Data duplikat.'])->withInput();
            }
            return back()->withErrors(['error' => 'Terjadi kesalahan saat menyimpan data.'])->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // $tp = TujuanPembelajaran::with('lingkupMateri.mapel', 'lingkupMateri.bab')->findOrFail($id);

        // $breadcrumbs = [
        //     ['label' => 'Manage Tujuan Pembelajaran', 'url' => route('tujuan-pembelajaran.index')],
        //     ['label' => 'Detail Tujuan Pembelajaran']
        // ];
        // $title = 'Detail Tujuan Pembelajaran';

        // return view('tujuan-pembelajaran.show', [
        //     'tp' => $tp,
        //     'breadcrumbs' => $breadcrumbs,
        //     'title' => $title,
        // ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $tujuan_pembelajaran = TujuanPembelajaran::findOrFail($id);
        $lingkupMateri = LingkupMateri::with('guruKelas.kelas', 'guruKelas.mapel', 'bab')->get();

        $lingkupMateriOptions = $lingkupMateri->mapWithKeys(function ($l) {
            return [
                $l->id => $l->lingkup_materi . ' (Kelas ' . optional($l->guruKelas->kelas)->nama . ' - ' . optional($l->guruKelas->mapel)->nama . ' - ' . optional($l->bab)->nama . ')'
            ];
        })->prepend('+ Tambah Lingkup Materi', 'tambah');

        $breadcrumbs = [
            ['label' => 'Manage Tujuan Pembelajaran', 'url' => route('mapel.index', ['tab' => $request->tab ?? 'tujuan-pembelajaran'])],
            ['label' => 'Edit Tujuan Pembelajaran']
        ];
        $title = 'Edit Tujuan Pembelajaran';

        return view('tujuan-pembelajaran.edit', compact('tujuan_pembelajaran', 'lingkupMateri', 'lingkupMateriOptions', 'breadcrumbs', 'title'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'lingkup_materi_id' => 'required|exists:lingkup_materi,id',
            'subbab' => 'nullable|string|max:255',
            'tujuan' => 'required|string',
        ]);

        $tujuan_pembelajaran = TujuanPembelajaran::findOrFail($id);

        $tujuan_pembelajaran->update([
            'lingkup_materi_id' => $request->lingkup_materi_id,
            'subbab' => $request->subbab,
            'tujuan' => $request->tujuan,
        ]);

        return redirect($request->input('redirect_to', route('mapel.index', ['tab' => $request->tab ?? 'tujuan-pembelajaran'])))
            ->with('success', 'Tujuan Pembelajaran berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $tp = TujuanPembelajaran::findOrFail($id);
            $tp->delete();

            return redirect()->route('mapel.index', ['tab' => $request->tab ?? 'tujuan-pembelajaran'])->with('success', 'Tujuan Pembelajaran berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('mapel.index', ['tab' => $request->tab ?? 'tujuan-pembelajaran'])->with('error', 'Gagal menghapus data. Pastikan tidak sedang digunakan.');
        }
    }
}

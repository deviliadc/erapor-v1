<?php

namespace App\Http\Controllers;

use App\Models\Fase;
use App\Models\P5Proyek;
use App\Models\P5Dimensi;
use App\Models\P5Elemen;
use App\Models\P5SubElemen;
use App\Models\P5ProyekDetail;
use Illuminate\Http\Request;

class P5ProyekController extends Controller
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
        $p5_proyek = new P5Proyek();

        return view('p5-proyek.create', [
            'p5_proyek' => $p5_proyek,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            // 'kelas_id' => 'required|exists:kelas,id',
            // 'p5_tema_id' => 'required|exists:p5_tema,id',
            'nama_proyek' => 'required|string|max:255',
            'deskripsi_proyek' => 'nullable|string|max:1000',
            'tahun_semester_id' => 'required|exists:tahun_semester,id',
            // 'dimensi_id' => 'required|array',
            // 'dimensi_id.*' => 'exists:p5_dimensi,id',
            // 'sub_elemen_id' => 'required|array',
            // 'sub_elemen_id.*' => 'exists:p5_sub_elemen,id',
            // 'guru_id' => 'required|exists:guru,id',
        ]);

        // Ambil guru_id dari sesi jika diperlukan, atau sesuaikan
        // $guruId = auth()->user()->guru->id ?? null;

        // Simpan data proyek
        P5Proyek::create([
            // 'kelas_id' => $validated['kelas_id'],
            // 'guru_id' => $validated['guru_id'],
            // 'p5_tema_id' => $validated['p5_tema_id'],
            'nama_proyek' => $validated['nama_proyek'],
            'deskripsi' => $validated['deskripsi_proyek'] ?? null,
            'tahun_semester_id' => $validated['tahun_semester_id'],
        ]);

        // Simpan ke pivot dimensi dan sub elemen
        // $proyek->dimensi()->sync($validated['dimensi_id']);
        // $proyek->subElemen()->sync($validated['sub_elemen_id']);

        return redirect()->to(role_route('p5.index', ['tab' => $request->tab ?? 'proyek']))
            ->with('success', 'Proyek P5 berhasil ditambahkan.');
    }


    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $proyek = P5Proyek::with([
            'tahunSemester',
            'detail.dimensi',
            'detail.elemen',
            'detail.subElemen',
        ])->findOrFail($id);

        // Modal
        $p5_proyek_id = $proyek->id;
        // $temaId = $proyek->p5_tema_id;

        $faseList = Fase::all()->mapWithKeys(function ($f) {
            return [$f->id => $f->nama];
        });

        $dimensiList = P5Dimensi::all()->map(fn($d) => [
            'id' => $d->id,
            'nama_dimensi' => $d->nama_dimensi,
        ]);
        $elemenList = P5Elemen::all()->map(fn($e) => [
            'id' => $e->id,
            'nama_elemen' => $e->nama_elemen,
            'p5_dimensi_id' => $e->p5_dimensi_id,
        ]);
        $subElemenList = P5SubElemen::all()->map(fn($s) => [
            'id' => $s->id,
            'nama_sub_elemen' => $s->nama_sub_elemen,
            'p5_elemen_id' => $s->p5_elemen_id,
        ]);

        // Pagination
        $perPage = $request->input('per_page', 10);

        $query = P5ProyekDetail::where('p5_proyek_id', $proyek->id);
        $totalCount = $query->count();
        $paginator = $query->paginate($perPage)->withQueryString();

        // Ambil detail proyek dari relasi
        $proyek_detail = $paginator->through(function ($item) use ($faseList) {
            $faseData = collect($faseList)->map(function ($namaFase, $faseId) use ($item) {
                $capaian = $item->subElemen->capaianFase
                    ->where('fase_id', $faseId)
                    ->first();
                return [
                    'fase' => $namaFase,
                    'capaian' => $capaian ? $capaian->capaian : '-',
                ];
            })->values();

            return [
                'id' => $item->id,
                'dimensi_id' => $item->dimensi_id, // <-- pastikan ini id asli
                'elemen_id' => $item->elemen_id,   // <-- pastikan ini id asli
                'sub_elemen_id' => $item->sub_elemen_id, // <-- pastikan ini id asli
                'dimensi' => $item->dimensi->nama_dimensi ?? '-',
                'elemen' => $item->elemen->nama_elemen ?? '-',
                'sub_elemen' => $item->subElemen->nama_sub_elemen ?? '-',
                'faseList' => $faseData,
            ];
        });

        $totalCount = $proyek_detail->count();

        $breadcrumbs = [
            // ['label' => 'Dashboard', 'url' => route('dashboard')],
            ['label' => 'Master Data P5', 'url' => role_route('p5.index')],
            ['label' => 'Detail Proyek'],
        ];
        $title = 'Detail Proyek P5';

        return view('p5-proyek.show', compact(
            'proyek',
            'proyek_detail',
            'p5_proyek_id',
            'totalCount',
            'breadcrumbs',
            'title',
            'faseList',
            'dimensiList',
            'elemenList',
            'subElemenList'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $p5_proyek = P5Proyek::findOrFail($id);

        $item = [
            'id' => $p5_proyek->id,
            'nama_proyek' => $p5_proyek->nama_proyek,
            'deskripsi_proyek' => $p5_proyek->deskripsi,
            // 'kelas_id' => $p5_proyek->kelas_id,
            // 'guru_id' => $p5_proyek->guru_id,
            // 'p5_tema_id' => $p5_proyek->p5_tema_id,
            'tahun_semester_id' => $p5_proyek->tahun_semester_id
        ];

        return view('p5-proyek.edit', compact('p5_proyek', 'item'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $p5_proyek = P5Proyek::findOrFail($id);

        $validated = $request->validate([
            // 'kelas_id' => 'required|exists:kelas,id',
            // 'guru_id' => 'required|exists:guru,id',
            // 'p5_tema_id' => 'required|exists:p5_tema,id',
            'nama_proyek' => 'required|string|max:255',
            'deskripsi_proyek' => 'nullable|string|max:1000',
            'tahun_semester_id' => 'required|exists:tahun_semester,id',
        ]);

        $p5_proyek->update([
            // 'p5_tema_id' => $validated['p5_tema_id'],
            // 'kelas_id' => $validated['kelas_id'],
            // 'guru_id' => $validated['guru_id'],
            'nama_proyek' => $validated['nama_proyek'],
            'deskripsi' => $validated['deskripsi_proyek'] ?? null,
            'tahun_semester_id' => $validated['tahun_semester_id'],
        ]);

        return redirect()->to(role_route('p5.index', ['tab' => $request->tab ?? 'proyek']))->with('success', 'Proyek P5 berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $p5_proyek = P5Proyek::findOrFail($id);
            $p5_proyek->delete();
            return redirect()->to(role_route('p5.index', ['tab' => 'proyek']))->with('success', 'Proyek P5 berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->to(role_route('p5.index', ['tab' => 'proyek']))->with('error', 'Gagal menghapus proyek. Pastikan tidak sedang digunakan.');
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Ekstra;
use App\Models\Kelas;
use App\Models\ParamEkstra;
use App\Models\SiswaEkstra;
use App\Models\TahunAjaran;
use App\Models\TahunSemester;
use Illuminate\Http\Request;

class EkstraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        // $tahunSemesterId = $request->input('tahun_semester_id') ?? TahunSemester::where('is_active', true)->value('id');
        // $tahunSemesterList = TahunSemester::orderByDesc('tahun')->orderByDesc('semester')->get();
        // $tahunAjaranId = $request->input('tahun_ajaran_id') ?? TahunAjaran::where('is_active', true)->value('id');
        // $tahunAjaranList = TahunAjaran::orderByDesc('tahun')->get();

        // $ekstra = $this->getEkstraData($request, $perPage);
        $ekstra = $this->getEkstraData($request, $perPage);
        $parameter = $this->getParameterData($request, $perPage);

        $breadcrumbs = [['label' => 'Master Data Ekstrakurikuler']];
        $title = 'Master Data Ekstrakurikuler';

        return view('ekstrakurikuler.index', array_merge(
            compact(
                'title',
                'breadcrumbs',
                // 'tahunSemesterId',
                // 'tahunSemesterList'
                // 'tahunAjaranId',
                // 'tahunAjaranList'
            ),
            $this->getEkstraData($request, $perPage),
            $this->getParameterData($request, $perPage),
        ));
    }

    private function getEkstraData(Request $request, $perPage)
    {
        $search = $request->input('search');

        $query = Ekstra::query();
        if ($search) {
            $query->where('nama', 'like', "%$search%");
        }

        $paginator = $query->paginate($perPage)->withQueryString();
        $data = $paginator->through(fn($item) => [
            'id' => $item->id,
            'nama' => $item->nama,
            'jumlah_parameter' => $item->paramEkstra()->count(),
        ]);

        return [
            'ekstra' => $data,
            'ekstraTotal' => $query->count(),
        ];
    }

    private function getParameterData(Request $request, $perPage)
    {
        $search = $request->input('search_parameter');

        $query = ParamEkstra::with('ekstra');
        if ($search) {
            $query->whereHas('paramEkstra', function ($q) use ($search) {
                $q->where('parameter', 'like', "%$search%");
            });
        }

        $ekstraList = Ekstra::pluck('nama', 'id');

        $paginator = $query->paginate($perPage)->withQueryString();
        $data = $paginator->through(fn($item) => [
            'id' => $item->id,
            'ekstra_id' => $item->ekstra_id,
            'ekstra' => $item->ekstra ? $item->ekstra->nama : '-',
            'parameter' => $item->parameter,
        ]);

        return [
            'parameterEkstra' => $data,
            'parameterEkstraTotal' => $query->count(),
            'ekstraList' => $ekstraList,
        ];
    }

    // public function kelas($id)
    // {
    //     $ekstra = Ekstra::findOrFail($id);
    //     $ekstraId = $ekstra->id;
    //     $tahunSemesterId = request()->input('tahun_semester_id') ?? TahunSemester::where('is_active', true)->value('id');

    //     // Ambil daftar kelas unik yang mengikuti ekstra ini
    //     $kelas = Kelas::whereIn('id', function ($query) use ($ekstraId, $tahunSemesterId) {
    //         $query->select('kelas_id')
    //             ->from('siswa_ekstra')
    //             ->where('ekstra_id', $ekstraId)
    //             ->where('tahun_semester_id', $tahunSemesterId);
    //     })->get();

    //     return view('ekstrakurikuler.kelas.index', compact('ekstra', 'kelas'));
    // }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // $breadcrumbs = [
        //     ['label' => 'Manage Ekstrakurikuler', 'url' => role_route('ekstra.index')],
        //     ['label' => 'Tambah Ekstrakurikuler']
        // ];
        // $title = 'Tambah Ekstrakurikuler';

        // return view('ekstrakurikuler.create', compact('breadcrumbs', 'title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'       => 'required|string|max:100',
        ]);

        Ekstra::create($validated);

        return redirect()->to(role_route('ekstra.index'))->with('success', 'Ekstrakurikuler berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $perPage = $request->input('per_page', 10);
        $search = $request->input('search');
        $ekstra = Ekstra::findOrFail($id);
        $query = $ekstra->paramEkstra();
        if ($search) {
            $query->where('parameter', 'like', "%$search%");
        }
        $totalCount = $query->count();
        $paginator = $query->paginate($perPage)->withQueryString();
        $param_ekstra = $paginator->through(fn($item) => [
            'id' => $item->id,
            'parameter' => $item->parameter,
        ]);
        $breadcrumbs = [
            ['label' => 'Manage Ekstrakurikuler', 'url' => role_route('ekstra.index')],
            ['label' => 'Detail Ekstrakurikuler']
        ];
        $title = 'Detail Ekstrakurikuler';
        return view('ekstrakurikuler.show', compact(
            'ekstra',
            'param_ekstra',
            'paginator',
            'totalCount',
            'breadcrumbs',
            'title'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $ekstra = Ekstra::findOrFail($id);

        $breadcrumbs = [
            ['label' => 'Manage Ekstrakurikuler', 'url' => role_route('ekstra.index')],
            ['label' => 'Edit Ekstrakurikuler']
        ];
        $title = 'Edit Ekstrakurikuler';

        return view('ekstrakurikuler.edit', compact(
            'ekstra',
            'breadcrumbs',
            'title'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $ekstra = Ekstra::findOrFail($id);

        $validated = $request->validate([
            'nama'       => 'required|string|max:100',
        ]);

        $ekstra->update($validated);

        return redirect()->to(role_route('ekstra.index'))->with('success', 'Ekstrakurikuler berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $ekstra = Ekstra::findOrFail($id);
            $ekstra->delete();
            return redirect()->to(role_route('ekstra.index'))->with('success', 'Ekstrakurikuler berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->to(role_route('ekstra.index'))->with('error', 'Gagal menghapus ekstrakurikuler. Pastikan tidak sedang digunakan.');
        }
    }
}

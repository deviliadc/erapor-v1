<?php

namespace App\Http\Controllers;

use App\Models\Fase;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\P5Capaian;
use App\Models\P5Dimensi;
use App\Models\P5Elemen;
use App\Models\P5Proyek;
use App\Models\P5SubElemen;
use App\Models\P5Tema;
use App\Models\TahunAjaran;
use App\Models\TahunSemester;
use Illuminate\Http\Request;

class P5MasterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);

        // $tema = $this->getTemaData($request, $perPage);
        $dimensi = $this->getDimensiData($request, $perPage);
        $elemen = $this->getElemenData($request, $perPage);
        $subElemen = $this->getSubElemenData($request, $perPage);
        $capaian = $this->getCapaianData($request, $perPage);
        $proyek = $this->getProyekData($request, $perPage);

        $breadcrumbs = [
            ['label' => 'Master Data P5'],
        ];

        $title = 'Master Data P5';

        return view('p5.index', array_merge(
            compact('title', 'breadcrumbs'),
            // $tema,
            $dimensi,
            $elemen,
            $subElemen,
            $capaian,
            $proyek,
        ));
    }

    // private function getTemaData(Request $request, $perPage)
    // {
    //     $searchTema = $request->input('search_tema');
    //     $temaQuery = P5Tema::query();

    //     if ($searchTema) {
    //         $temaQuery->where('nama_tema', 'like', "%$searchTema%");
    //     }

    //     $prefix = 'tema';
    //     $sortBy = $request->input("sortBy_{$prefix}", 'id');
    //     $sortDirection = $request->input("sortDirection_{$prefix}", 'asc');
    //     $columnMap = [
    //         'id' => 'id',
    //         'nama_tema' => 'nama_tema',
    //         'deskripsi_tema' => 'deskripsi',
    //     ];
    //     $temaQuery->orderBy($columnMap[$sortBy] ?? 'id', $sortDirection);

    //     $temaPaginator = $temaQuery->paginate($perPage)->withQueryString();
    //     $tema = $temaPaginator->through(fn($item) => [
    //         'id' => $item->id,
    //         'nama_tema' => $item->nama_tema,
    //         'deskripsi_tema' => $item->deskripsi ?? '-',
    //     ]);
    //     $temaTotal = $temaQuery->count();

    //     return [
    //         'tema' => $tema,
    //         'temaTotal' => $temaTotal,
    //     ];
    // }


    private function getDimensiData(Request $request, $perPage)
    {
        $searchDimensi = $request->input('search_dimensi');
        $dimensiQuery = P5Dimensi::query();

        if ($searchDimensi) {
            $dimensiQuery->where('nama_dimensi', 'like', "%$searchDimensi%");
        }

        $prefix = 'dimensi';
        $sortBy = $request->input("sortBy_{$prefix}", 'id');
        $sortDirection = $request->input("sortDirection_{$prefix}", 'asc');
        $columnMap = [
            'id' => 'id',
            'nama_dimensi' => 'nama_dimensi',
            'deskripsi_dimensi' => 'deskripsi',
        ];
        $dimensiQuery->orderBy($columnMap[$sortBy] ?? 'id', $sortDirection);

        $dimensiPaginator = $dimensiQuery->paginate($perPage)->withQueryString();
        $dimensi = $dimensiPaginator->through(fn($item) => [
            'id' => $item->id,
            'nama_dimensi' => $item->nama_dimensi,
            'deskripsi_dimensi' => $item->deskripsi ?? '-',
        ]);
        $dimensiTotal = $dimensiQuery->count();

        return [
            'dimensi' => $dimensi,
            'dimensiTotal' => $dimensiTotal,
        ];
    }

    private function getElemenData(Request $request, $perPage)
    {
        $searchElemen = $request->input('search_elemen');
        $elemenQuery = P5Elemen::with('dimensi');

        if ($searchElemen) {
            $elemenQuery->where('nama_elemen', 'like', "%$searchElemen%");
        }

        $prefix = 'elemen';
        $sortBy = $request->input("sortBy_{$prefix}", 'id');
        $sortDirection = $request->input("sortDirection_{$prefix}", 'asc');
        $columnMap = [
            'id' => 'id',
            'nama_elemen' => 'nama_elemen',
            'p5_dimensi_id' => 'p5_dimensi_id',
        ];
        $elemenQuery->orderBy($columnMap[$sortBy] ?? 'id', $sortDirection);

        $elemenPaginator = $elemenQuery->paginate($perPage)->withQueryString();
        $elemen = $elemenPaginator->through(fn($item) => [
            'id' => $item->id,
            'p5_dimensi_id' => $item->p5_dimensi_id,
            'nama_dimensi' => $item->dimensi->nama_dimensi ?? '-',
            'nama_elemen' => $item->nama_elemen,
            'deskripsi_elemen' => $item->deskripsi ?? '-',
        ]);
        $elemenTotal = $elemenQuery->count();

        return [
            'elemen' => $elemen,
            'elemenTotal' => $elemenTotal,
        ];
    }


    private function getSubElemenData(Request $request, $perPage)
    {
        $searchSubElemen = $request->input('search_sub_elemen');
        $subElemenQuery = P5SubElemen::with('elemen');

        if ($searchSubElemen) {
            $subElemenQuery->where('nama_sub_elemen', 'like', "%$searchSubElemen%");
        }

        $prefix = 'sub_elemen';
        $sortBy = $request->input("sortBy_{$prefix}", 'id');
        $sortDirection = $request->input("sortDirection_{$prefix}", 'asc');
        $columnMap = [
            'id' => 'id',
            'nama_sub_elemen' => 'nama_sub_elemen',
            'p5_elemen_id' => 'p5_elemen_id',
        ];
        $subElemenQuery->orderBy($columnMap[$sortBy] ?? 'id', $sortDirection);

        $subElemenPaginator = $subElemenQuery->paginate($perPage)->withQueryString();
        $subElemen = $subElemenPaginator->through(fn($item) => [
            'id' => $item->id,
            'p5_elemen_id' => $item->p5_elemen_id,
            'nama_elemen' => $item->elemen->nama_elemen ?? '-',
            'nama_sub_elemen' => $item->nama_sub_elemen,
            'deskripsi_sub_elemen' => $item->deskripsi ?? '-',
        ]);
        $subElemenTotal = $subElemenQuery->count();

        return [
            'subElemen' => $subElemen,
            'subElemenTotal' => $subElemenTotal,
        ];
    }

    private function getCapaianData(Request $request, $perPage)
    {
        $faseList = Fase::pluck('nama', 'id')->toArray();
        $subElemenList = P5SubElemen::pluck('nama_sub_elemen', 'id')->toArray();
        $searchCapaian = $request->input('search_capaian');
        $capaianQuery = P5Capaian::with('fase', 'subElemen.elemen');

        if ($searchCapaian) {
            $capaianQuery->where('capaian', 'like', "%$searchCapaian%");
        }

        $prefix = 'capaian';
        $sortBy = $request->input("sortBy_{$prefix}", 'id');
        $sortDirection = $request->input("sortDirection_{$prefix}", 'asc');
        $columnMap = [
            'id' => 'id',
            'fase_id' => 'fase_id',
            'p5_sub_elemen_id' => 'p5_sub_elemen_id',
            'capaian' => 'capaian',
        ];
        $capaianQuery->orderBy($columnMap[$sortBy] ?? 'id', $sortDirection);

        $capaianPaginator = $capaianQuery->paginate($perPage)->withQueryString();
        $capaian = $capaianPaginator->through(fn($item) => [
            'id' => $item->id,
            'fase_id' => $item->fase_id,
            'nama_fase' => $item->fase->nama ?? '-',
            'p5_sub_elemen_id' => $item->p5_sub_elemen_id,
            'nama_sub_elemen' => $item->subElemen->nama_sub_elemen ?? '-',
            // 'nama_elemen' => $item->subElemen->elemen->nama_elemen ?? '-',
            'capaian' => $item->capaian,
        ]);
        $capaianTotal = $capaianQuery->count();

        return [
            'capaian' => $capaian,
            'capaianTotal' => $capaianTotal,
            'faseList' => $faseList,
            'subElemenList' => $subElemenList,
        ];
    }

    private function getProyekData(Request $request, $perPage)
    {
        $searchProyek = $request->input('search_proyek');
        $proyekQuery = P5Proyek::with(['tahunSemester']);
        if ($searchProyek) {
            $proyekQuery->where('nama_proyek', 'like', "%$searchProyek%");
        }
        $prefix = 'proyek';
        $sortBy = $request->input("sortBy_{$prefix}", 'id');
        $sortDirection = $request->input("sortDirection_{$prefix}", 'asc');
        $columnMap = [
            'id' => 'p5_proyek.id',
            'nama_proyek' => 'p5_proyek.nama_proyek',
            'deskripsi_proyek' => 'p5_proyek.deskripsi',
            // 'p5_tema_id' => 'p5_proyek.p5_tema_id',
            // 'kelas_id' => 'p5_proyek.kelas_id',
            // 'guru_id' => 'p5_proyek.guru_id',
            'tahun_semester_id' => 'p5_proyek.tahun_semester_id',
        ];
        $proyekQuery->orderBy($columnMap[$sortBy] ?? 'p5_proyek.id', $sortDirection);

        $proyekPaginator = $proyekQuery->paginate($perPage)->withQueryString();
        $proyek = $proyekPaginator->through(fn($item) => [
            'id' => $item->id,
            'nama_proyek' => $item->nama_proyek,
            'deskripsi_proyek' => $item->deskripsi ?? '-',
            // 'p5_tema_id' => $item->p5_tema_id,
            // 'nama_tema' => $item->tema->nama_tema ?? '-',
            // 'kelas_id' => $item->kelas_id,
            // 'nama_kelas' => $item->kelas->nama ?? '-',
            // 'guru_id' => $item->guru_id,
            // 'nama_guru' => $item->guru->nama ?? '-',
            // 'dimensi' => $item->dimensi->pluck('nama_dimensi')->implode(', '),
            // 'elemen' => $item->subElemen
            //     ->map(fn($sub) => $sub->elemen->nama_elemen ?? '-')
            //     ->unique()
            //     ->implode(', '),
            // 'sub_elemen' => $item->subElemen->pluck('nama_sub_elemen')->implode(', '),
            'tahun_semester_id' => $item->tahun_semester_id,
            'nama_tahun_semester' => ($item->tahunSemester && $item->tahunSemester->tahunAjaran
                ? $item->tahunSemester->tahunAjaran->tahun . ' - ' . ucfirst($item->tahunSemester->semester)
                : '-'),
        ]);
        $proyekTotal = $proyekQuery->count();

        // INISIASI DATA SELECT UNTUK MODAL CREATE
        // $temaList = P5Tema::pluck('nama_tema', 'id');
        // $dimensiList = P5Dimensi::pluck('nama_dimensi', 'id');
        // $elemenList = P5Elemen::pluck('nama_elemen', 'id');
        // $subElemenList = P5SubElemen::pluck('nama_sub_elemen', 'id')->toArray();

        // $dimensiToSubElemen = [];
        // foreach (P5Dimensi::with('elemen.subElemen')->get() as $dimensi) {
        //     $sub = [];
        //     foreach ($dimensi->elemen as $elemen) {
        //         foreach ($elemen->subElemen as $subElemen) {
        //             $sub[$subElemen->id] = $elemen->nama_elemen . ': ' . $subElemen->nama_sub_elemen;
        //         }
        //     }
        //     $dimensiToSubElemen[$dimensi->id] = $sub;
        // }

        // $dimensiToSubElemen = [];

        // P5Dimensi::with('elemen.subElemen')->get()->each(function ($dimensi) use (&$dimensiToSubElemen) {
        //     $dimensiToSubElemen[$dimensi->id] = [];

        //     foreach ($dimensi->elemen as $elemen) {
        //         foreach ($elemen->subElemen as $subElemen) {
        //             $dimensiToSubElemen[$dimensi->id][$subElemen->id] = "{$elemen->nama_elemen}: {$subElemen->nama_sub_elemen}";
        //         }
        //     }
        // });


        // $tahunSemesterList = TahunSemester::orderBy('tahun', 'desc')
        //     ->orderByRaw("FIELD(semester, 'genap', 'ganjil')") // jika ingin genap dulu, lalu ganjil
        //     ->get()
        //     ->mapWithKeys(function ($item) {
        //         return [
        //             $item->id => $item->tahun . ' - ' . ucfirst($item->semester) . ' - ' . ($item->is_active ? 'Aktif' : 'Tidak Aktif')
        //         ];
        //     });
        $tahunSemesterList = TahunSemester::with('tahunAjaran')
            ->orderByDesc(
                TahunAjaran::select('tahun')
                    ->whereColumn('tahun_ajaran_id', 'tahun_ajaran.id')
            )
            ->orderByRaw("FIELD(semester, 'genap', 'ganjil')")
            ->get()
            ->mapWithKeys(function ($item) {
                return [
                    $item->id => ($item->tahunAjaran ? $item->tahunAjaran->tahun : '-') . ' - ' . ucfirst($item->semester) . ' - ' . ($item->is_active ? 'Aktif' : 'Tidak Aktif')
                ];
            });

        $kelasList = Kelas::pluck('nama', 'id');
        $guruList = Guru::pluck('nama', 'id');

        return [
            'proyek' => $proyek,
            'proyekTotal' => $proyekTotal,
            // 'temaList' => $temaList,
            // 'dimensiList' => $dimensiList,
            // 'elemenList' => $elemenList,
            // 'subElemenList' => $subElemenList,
            // 'dimensiToSubElemen' => $dimensiToSubElemen,
            'tahunSemesterList' => $tahunSemesterList,
            'kelasList' => $kelasList,
            'guruList' => $guruList,
        ];
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

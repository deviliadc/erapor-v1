<?php

namespace App\Http\Controllers;

use App\Models\P5Dimensi;
use App\Models\P5Elemen;
use App\Models\P5Proyek;
use App\Models\P5SubElemen;
use App\Models\P5Tema;
use Illuminate\Http\Request;

class P5MasterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);

        // Tema
        $searchTema = $request->input('search_tema');
        $temaQuery = P5Tema::query();
        if ($searchTema) {
            $temaQuery->where('nama_tema', 'like', "%$searchTema%");
        }
        $temaPaginator = $temaQuery->paginate($perPage)->withQueryString();
        $tema = $temaPaginator->through(fn($item) => [
            'id' => $item->id,
            'nama_tema' => $item->nama_tema,
            'deskripsi_tema' => $item->deskripsi ?? '-' ,
        ]);
        $temaTotal = $temaQuery->count();

        // Dimensi
        $searchDimensi = $request->input('search_dimensi');
        $dimensiQuery = P5Dimensi::query();
        if ($searchDimensi) {
            $dimensiQuery->where('nama_dimensi', 'like', "%$searchDimensi%");
        }
        $dimensiPaginator = $dimensiQuery->paginate($perPage)->withQueryString();
        $dimensi = $dimensiPaginator->through(fn($item) => [
            'id' => $item->id,
            'nama_dimensi' => $item->nama_dimensi,
            'deskripsi_dimensi' => $item->deskripsi ?? '-',
        ]);
        $dimensiTotal = $dimensiQuery->count();

        // Elemen
        $searchElemen = $request->input('search_elemen');
        $elemenQuery = P5Elemen::with('dimensi');
        if ($searchElemen) {
            $elemenQuery->where('nama_elemen', 'like', "%$searchElemen%");
        }
        $elemenPaginator = $elemenQuery->paginate($perPage)->withQueryString();
        $elemen = $elemenPaginator->through(fn($item) => [
            'id' => $item->id,
            'p5_dimensi_id' => $item->p5_dimensi_id,
            'nama_dimensi' => $item->dimensi->nama_dimensi ?? '-',
            'nama_elemen' => $item->nama_elemen,
            'deskripsi_elemen' => $item->deskripsi ?? '-',
        ]);
        $elemenTotal = $elemenQuery->count();

        // Sub Elemen
        $searchSubElemen = $request->input('search_sub_elemen');
        $subElemenQuery = P5SubElemen::with('elemen');
        if ($searchSubElemen) {
            $subElemenQuery->where('nama_sub_elemen', 'like', "%$searchSubElemen%");
        }
        $subElemenPaginator = $subElemenQuery->paginate($perPage)->withQueryString();
        $subElemen = $subElemenPaginator->through(fn($item) => [
            'id' => $item->id,
            'p5_elemen_id' => $item->p5_elemen_id,
            'nama_elemen' => $item->elemen->nama_elemen ?? '-',
            'nama_sub_elemen' => $item->nama_sub_elemen,
            'deskripsi_sub_elemen' => $item->deskripsi ?? '-',
        ]);
        $subElemenTotal = $subElemenQuery->count();

        // Proyek
        $searchProyek = $request->input('search_proyek');
        $proyekQuery = P5Proyek::with(['tema', 'kelas', 'guru', 'tahunSemester', 'dimensi', 'subElemen']);
        if ($searchProyek) {
            $proyekQuery->where('nama_proyek', 'like', "%$searchProyek%");
        }
        $proyekPaginator = $proyekQuery->paginate($perPage)->withQueryString();
        $proyek = $proyekPaginator->through(fn($item) => [
            'id' => $item->id,
            'nama_proyek' => $item->nama_proyek,
            'deskripsi_proyek' => $item->deskripsi ?? '-',
            'p5_tema_id' => $item->p5_tema_id,
            'nama_tema' => $item->tema->nama_tema ?? '-',
            'kelas_id' => $item->kelas_id,
            'nama_kelas' => $item->kelas->nama_kelas ?? '-',
            'guru_id' => $item->guru_id,
            'nama_guru' => $item->guru->nama ?? '-',
            'dimensi' => $item->dimensi->map(fn($dim) => [
                'id' => $dim->id,
                'nama_dimensi' => $dim->nama_dimensi,
            ]),
            'sub_elemen' => $item->subElemen->map(fn($sub) => [
                'id' => $sub->id,
                'nama_sub_elemen' => $sub->nama_sub_elemen,
            ]),
            'tahun_semester_id' => $item->tahun_semester_id,
            'nama_tahun_semester' => $item->tahunSemester->nama_tahun_semester ?? '-',
            // 'waktu_mulai' => $item->waktu_mulai ? $item->waktu_mulai->format('d-m-Y H:i') : '-',
            // 'waktu_selesai' => $item->waktu_selesai ? $item->waktu_selesai->format('d-m-Y H:i') : '-',
        ]);
        $proyekTotal = $proyekQuery->count();

        // Data breadcrumb & view
        $breadcrumbs = [
            // ['label' => 'Dashboard', 'url' => route('dashboard')],
            ['label' => 'Master Data P5'],
        ];

        $title = 'Master Data P5';

        return view('p5.index', compact(
            'title',
            'breadcrumbs',
            'tema', 'temaTotal',
            'dimensi', 'dimensiTotal',
            'elemen', 'elemenTotal',
            'subElemen', 'subElemenTotal'
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

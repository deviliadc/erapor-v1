<?php

namespace App\Http\Controllers;

use App\Models\P5Dimensi;
use App\Models\P5Elemen;
use App\Models\P5SubElemen;
use App\Models\P5Tema;
use Illuminate\Http\Request;

class P5Controller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);

        // Tema
        $temaQuery = P5Tema::query();
        if ($search = $request->input('search_tema')) {
            $temaQuery->where('nama_tema', 'like', "%$search%");
        }
        $temaPaginator = $temaQuery->paginate($perPage)->withQueryString();
        $tema = $temaPaginator->through(fn($item) => [
            'id' => $item->id,
            'nama_tema' => $item->nama_tema,
            'deskripsi_tema' => $item->deskripsi,
        ]);
        $temaTotal = $temaQuery->count();

        // Dimensi
        $dimensiQuery = P5Dimensi::query();
        if ($search = $request->input('search_dimensi')) {
            $dimensiQuery->where('nama_dimensi', 'like', "%$search%");
        }
        $dimensiPaginator = $dimensiQuery->paginate($perPage)->withQueryString();
        $dimensi = $dimensiPaginator->through(fn($item) => [
            'id' => $item->id,
            'nama_dimensi' => $item->nama_dimensi,
            'deskripsi_dimensi' => $item->deskripsi,
        ]);
        $dimensiTotal = $dimensiQuery->count();

        // Elemen
        $elemenQuery = P5Elemen::query();
        if ($search = $request->input('search_elemen')) {
            $elemenQuery->where('nama_elemen', 'like', "%$search%");
        }
        $elemenPaginator = $elemenQuery->paginate($perPage)->withQueryString();
        $elemen = $elemenPaginator->through(fn($item) => [
            'id' => $item->id,
            'dimensi_id' => $item->p5_dimensi_id,
            'nama_elemen' => $item->nama_elemen,
        ]);
        $elemenTotal = $elemenQuery->count();

        // Sub Elemen
        $subElemenQuery = P5SubElemen::query();
        if ($search = $request->input('search_sub_elemen')) {
            $subElemenQuery->where('nama_sub_elemen', 'like', "%$search%");
        }
        $subElemenPaginator = $subElemenQuery->paginate($perPage)->withQueryString();
        $subElemen = $subElemenPaginator->through(fn($item) => [
            'id' => $item->id,
            'elemen_id' => $item->p5_elemen_id,
            'nama_sub_elemen' => $item->nama_sub_elemen,
        ]);
        $subElemenTotal = $subElemenQuery->count();

        // Data breadcrumb & view
        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route('dashboard')],
            ['label' => 'Master Data P5'],
        ];

        $title = 'Master Data P5';

        return view('p5.index', compact(
            'title',
            'breadcrumbs',
            'tema',
            'temaTotal',
            'dimensi',
            'dimensiTotal',
            'elemen',
            'elemenTotal',
            'subElemen',
            'subElemenTotal'
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

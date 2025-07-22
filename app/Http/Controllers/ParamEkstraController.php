<?php

namespace App\Http\Controllers;

use App\Models\Ekstra;
use App\Models\ParamEkstra;
use Illuminate\Http\Request;

class ParamEkstraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $perPage = $request->input('per_page', 10);

        // $query = ParamEkstra::with('ekstra');

        // if ($search = $request->input('search')) {
        //     $query->where('ekstra', 'like', "%$search%");
        // }

        // $totalCount = $query->count();
        // $paginator = $query->paginate($perPage)->withQueryString();

        // $param_ekstra = $paginator->through(function ($item) {
        //     return [
        //         'id' => $item->id,
        //         'ekstra_id' => $item->ekstra_id,
        //         'ekstra' => $item->ekstra ? $item->ekstra->nama : '-',
        //         'parameter' => $item->parameter,
        //     ];
        // });

        // $ekstra = Ekstra::pluck('nama', 'id');

        // $breadcrumbs = [
        //     ['label' => 'Manage Parameter Ekstrakurikuler', 'url' => route('param-ekstra.index')]
        // ];

        // $title = 'Manage Parameter Ekstrakurikuler';

        // return view('param-ekstra.index',  compact('param_ekstra', 'totalCount', 'breadcrumbs', 'title', 'ekstra'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $ekstra = Ekstra::pluck('nama', 'id');

        return view('param-ekstra.create', compact('ekstra'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'ekstra_id'              => 'required|exists:ekstra,id',
            'parameter'           => 'required|string|max:100',
        ]);

        ParamEkstra::create($validated);

        return redirect()->route('ekstra.index', ['tab' => request('tab', 'parameter')])->with('success', 'Parameter Ekstrakurikuler berhasil ditambahkan.');

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
        $param_ekstra = ParamEkstra::findOrFail($id);
        $ekstra = Ekstra::pluck('nama', 'id');

        // $breadcrumbs = [
        //     ['label' => 'Manage Parameter Ekstrakurikuler', 'url' => route('param-ekstra.index')],
        //     ['label' => 'Edit Parameter Ekstrakurikuler']
        // ];
        // $title = 'Edit Parameter Ekstrakurikuler';

        return view('param-ekstra.edit', compact('param_ekstra', 'ekstra', 'breadcrumbs', 'title'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $param_ekstra = ParamEkstra::findOrFail($id);

        $validated = $request->validate([
            'ekstra_id' => 'required|exists:ekstra,id',
            'parameter'            => 'required|string|max:100',

        ]);

        $param_ekstra->update($validated);

        return redirect()->route('ekstra.index', ['tab' => request('tab', 'parameter')])->with('success', 'Parameter Ekstrakurikuler berhasil diperbarui.');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $param_ekstra = ParamEkstra::findOrFail($id);
            $param_ekstra->delete();
            return redirect()->route('ekstra.index', ['tab' => request('tab', 'parameter')])->with('success', 'Parameter Ekstrakurikuler berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('ekstra.index', ['tab' => request('tab', 'parameter')])->with('error', 'Gagal menghapus Parameter Ekstrakurikuler. Pastikan tidak sedang digunakan.');
        }
    }
}

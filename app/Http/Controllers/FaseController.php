<?php

namespace App\Http\Controllers;

use App\Models\Fase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class FaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $search = $request->input('search');
        $query = Fase::query();
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%$search%")
                  ->orWhere('keterangan', 'like', "%$search%");
            });
        }
        $totalCount = $query->count();
        $paginator = $query->paginate($perPage)->withQueryString();
        $fase = $paginator->through(function ($item) {
            return [
                'id' => $item->id,
                'nama' => $item->nama,
                'keterangan' => $item->keterangan,
            ];
        });
        $breadcrumbs = [
            ['label' => 'Manage Fase'],
        ];
        $title = 'Manage Fase';

        return view('fase.index', compact(
            'fase',
            'totalCount',
            'breadcrumbs',
            'title',
            'paginator',
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // $breadcrumbs = [
        //     ['label' => 'Manage Fase', 'url' => role_route('fase.index')],
        //     ['label' => 'Create Fase'],
        // ];

        // $title = 'Create Fase';

        // return view('fase.create', compact('breadcrumbs', 'title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:50|unique:fase',
            'keterangan' => 'nullable|string|max:255',
        ]);

        Fase::create([
            'nama' => $request->nama,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->to(role_route('fase.index'))->with('success', 'Data fase berhasil ditambahkan.');
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
        $kelas = Fase::findOrFail($id);

        $title = 'Edit Fase';
        $breadcrumbs = [
            ['label' => 'Manage Fase', 'url' => role_route('fase.index')],
            ['label' => 'Edit Fase'],
        ];
        return view('fase.edit', compact('fase', 'title', 'breadcrumbs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama' => 'required|string|max:50|unique:fase,nama,' . $id,
            'keterangan' => 'nullable|string|max:255',
        ]);

        $fase = Fase::findOrFail($id);
        $fase->update([
            'nama' => $request->nama,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->to(role_route('fase.index'))->with('success', 'Data fase berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $fase = Fase::findOrFail($id);
        $fase->delete();

        return redirect()->to(role_route('fase.index'))->with('success', 'Data fase berhasil dihapus.');
    }
}

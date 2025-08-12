<?php

namespace App\Http\Controllers;

use App\Models\P5CapaianFase;
use App\Models\P5ProyekDetail;
use Illuminate\Http\Request;

class P5ProyekDetailController extends Controller
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
        $p5_proyek_detail_detail = new P5ProyekDetail();

        return view('p5-proyek.create-detail', [
            'p5_proyek_detail_detail' => $p5_proyek_detail_detail,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'p5_proyek_id' => 'required|exists:p5_proyek,id',
            'dimensi_id' => 'required|exists:p5_dimensi,id',
            'elemen_id' => 'required|exists:p5_elemen,id',
            'sub_elemen_id' => 'required|exists:p5_sub_elemen,id',
        ]);

        // Cek duplikasi
        $exists = P5ProyekDetail::where('p5_proyek_id', $validated['p5_proyek_id'])
            ->where('p5_dimensi_id', $validated['dimensi_id'])
            ->where('p5_elemen_id', $validated['elemen_id'])
            ->where('p5_sub_elemen_id', $validated['sub_elemen_id'])
            ->exists();

        if ($exists) {
            return back()->withInput()->with('error', 'Data detail proyek dengan kombinasi tersebut sudah ada.');
        }

        P5ProyekDetail::create([
            'p5_proyek_id' => $validated['p5_proyek_id'],
            'p5_dimensi_id' => $validated['dimensi_id'],
            'p5_elemen_id' => $validated['elemen_id'],
            'p5_sub_elemen_id' => $validated['sub_elemen_id'],
        ]);

        return redirect()->to(role_route('p5-proyek.show', ['p5_proyek' => $validated['p5_proyek_id']]))
            ->with('success', 'Detail proyek berhasil disimpan.');
    }


    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // $p5_proyek_detail = P5ProyekDetail::findOrFail($id);

        // $item = [
        //     'id' => $p5_proyek_detail->id,
        //     'p5_proyek_id' => $p5_proyek_detail->p5_proyek_id,
        //     'dimensi_id' => $p5_proyek_detail->dimensi_id,
        //     'elemen_id' => $p5_proyek_detail->elemen_id,
        //     'sub_elemen_id' => $p5_proyek_detail->sub_elemen_id,
        // ];

        // return view('p5-proyek.edit-detail', compact('p5_proyek_detail', 'item'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // $p5_proyek_detail = P5ProyekDetail::findOrFail($id);

        // $validated = $request->validate([
        //     'dimensi_id' => 'required|exists:p5_dimensi,id',
        //     'elemen_id' => 'required|exists:p5_elemen,id',
        //     'sub_elemen_id' => 'required|exists:p5_sub_elemen,id',
        // ]);

        // $p5_proyek_detail->update([
        //     'dimensi_id' => $validated['dimensi_id'],
        //     'elemen_id' => $validated['elemen_id'],
        //     'sub_elemen_id' => $validated['sub_elemen_id'],
        // ]);

        // return redirect()->to(role_route('p5-proyek.show', $p5_proyek_detail->p5_proyek_id))
        //     ->with('success', 'Detail proyek berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $p5_proyek_detail = P5ProyekDetail::findOrFail($id);
        $proyekId = $p5_proyek_detail->p5_proyek_id;
        $p5_proyek_detail->delete();

        return redirect()->to(role_route('p5-proyek.show', ['p5_proyek' => $proyekId]))
            ->with('success', 'Detail proyek berhasil dihapus.');
    }
}

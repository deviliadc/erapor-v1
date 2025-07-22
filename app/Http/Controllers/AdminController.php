<?php

namespace App\Http\Controllers;

use App\Models\Ekstra;
use App\Models\Guru;
use App\Models\Mapel;
use App\Models\P5;
use App\Models\Siswa;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $breadcrumbs = [
        //     ['label' => 'Dashboard', 'url' => route('dashboard.admin')],
        // ];

        // $title = 'Dashboard';

        // // Pastikan import model yang diperlukan di atas controller
        // $totalSiswa = Siswa::count();
        // $totalGuru = Guru::count();
        // $totalMapel = Mapel::count();
        // $totalEkstra = Ekstra::count();
        // $totalP5 = P5::count();

        // return view('admin.dashboard', compact(
        //     'breadcrumbs',
        //     'title',
        //     'totalSiswa',
        //     'totalGuru',
        //     'totalMapel',
        //     'totalEkstra',
        //     'totalP5'
        // ));
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
    public function store(Request $request)
    {
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

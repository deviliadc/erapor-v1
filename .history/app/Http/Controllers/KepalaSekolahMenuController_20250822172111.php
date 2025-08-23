<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Siswa;
use App\Models\WaliMurid;
use App\Models\RekapAbsensi;
use App\Models\NilaiMapel;
use App\Models\NilaiEkstra;
use App\Models\NilaiP5;
use App\Models\Rapor;
use Illuminate\Http\Request;

class KepalaSekolahMenuController extends Controller
{
    public function dataGuru()
    {
        $data = Guru::all();
        return view('menu-kepsek.data-guru', compact('data'));
    }

    public function dataSiswa()
    {
        $data = Siswa::all();
        return view('menu-kepsek.data-siswa', compact('data'));
    }

    public function dataWaliMurid()
    {
        $data = WaliMurid::all();
        return view('menu-kepsek.data-wali-murid', compact('data'));
    }

    public function rekapAbsensi()
    {
        $data = RekapAbsensi::with('siswa')->get();
        return view('menu-kepsek.rekap-absensi', compact('data'));
    }

    public function nilaiMapel()
    {
        $data = NilaiMapel::with('siswa', 'mapel')->get();
        return view('menu-kepsek.nilai-mapel', compact('data'));
    }

    public function nilaiEkstra()
    {
        $data = NilaiEkstra::with('siswa', 'ekstra')->get();
        return view('menu-kepsek.nilai-ekstra', compact('data'));
    }

    public function nilaiP5()
    {
        $data = NilaiP5::with('siswa')->get();
        return view('menu-kepsek.nilai-p5', compact('data'));
    }

    // public function rapor()
    // {
    //     $data = Rapor::with('siswa')->get();
    //     return view('menu-kepsek.rapor', compact('data'));
    // }

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

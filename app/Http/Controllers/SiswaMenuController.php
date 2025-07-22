<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\RekapAbsensi;
use App\Models\NilaiMapel;
use App\Models\NilaiEkstra;
use App\Models\NilaiP5;

class SiswaMenuController extends Controller
{
    public function absensi()
    {
        $user = Auth::user();
        $siswa = $user->siswa;
        $absensi = RekapAbsensi::where('siswa_id', $siswa->id)->get();

        return view('menu-siswa.absensi', compact('siswa', 'absensi'));
    }

    public function nilaiMapel()
    {
        $user = Auth::user();
        $siswa = $user->siswa;
        $nilaiMapel = NilaiMapel::where('kelas_siswa_id', $siswa->kelasSiswaAktif()?->id)->get();

        return view('menu-siswa.nilai-mapel', compact('siswa', 'nilaiMapel'));
    }

    public function nilaiEkstra()
    {
        $user = Auth::user();
        $siswa = $user->siswa;
        $nilaiEkstra = NilaiEkstra::where('kelas_siswa_id', $siswa->kelasSiswaAktif()?->id)->get();

        return view('menu-siswa.nilai-ekstra', compact('siswa', 'nilaiEkstra'));
    }

    public function nilaiP5()
    {
        $user = Auth::user();
        $siswa = $user->siswa;
        $nilaiP5 = NilaiP5::where('kelas_siswa_id', $siswa->kelasSiswaAktif()?->id)->get();

        return view('menu-siswa.nilai-p5', compact('siswa', 'nilaiP5'));
    }

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

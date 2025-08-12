<?php

namespace App\Http\Middleware;

use App\Models\GuruKelas;
use App\Models\TahunSemester;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPeranGuru
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    // public function handle(Request $request, Closure $next): Response
    // {
    //     $user = Auth::user();
    //     if (!$user || !$user->guru) {
    //         // Redirect ke login jika belum login atau bukan guru
    //         return redirect()->route('login');
    //     }

    //     $guru = $user->guru;

    //     // Ambil tahun semester aktif
    //     $tahunAktif = TahunSemester::where('aktif', true)->first();
    //     if (!$tahunAktif) {
    //         abort(403, 'Tahun semester aktif tidak ditemukan.');
    //     }

    //     // Ambil semua peran guru di tahun aktif
    //     $peran = GuruKelas::where('guru_id', $guru->id)
    //         ->where('tahun_semester_id', $tahunAktif->id)
    //         ->pluck('peran')
    //         ->unique();

    //     if (!$request->session()->has('peran_aktif')) {
    //         if ($peran->count() > 1) {
    //             // Redirect ke halaman pilihan peran
    //             return redirect()->route('pilih-peran');
    //         } else {
    //             // Auto-set jika cuma satu peran
    //             session(['peran_aktif' => $peran->first()]);
    //         }
    //     }
    //     return $next($request);
    // }
}

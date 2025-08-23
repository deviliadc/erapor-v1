<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\TahunSemester;
use App\Models\ValidasiSemester;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ValidasiSemesterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);

        // Ambil semua semester dengan relasi tahun ajaran
        $semester = TahunSemester::with('tahunAjaran')
            ->orderByDesc('tahun_ajaran_id')
            ->orderBy('semester')
            ->get();

        // Ambil semester aktif
        $semesterAktif = TahunSemester::aktif()->first();

        // Tambahkan label untuk dropdown + tandai aktif
        $semester = $semester->map(function ($s) use ($semesterAktif) {
            $label = $s->tahunAjaran->tahun . ' - ' . $s->semester;
            if ($semesterAktif && $s->id === $semesterAktif->id) {
                $label .= ' (Aktif)';
            }
            $s->label = $label;
            return $s;
        });

        // Default filter ke semester aktif
        $semesterId = $request->input('tahun_semester', $semesterAktif?->id);

        // Query validasi sesuai filter semester
        $query = ValidasiSemester::with('tahunSemester.tahunAjaran', 'validator')
            ->where('tahun_semester_id', $semesterId);

        $totalCount = $query->count();
        $paginator = $query->paginate($perPage)->withQueryString();

        $validasi = $paginator->through(fn($item) => [
            'id' => $item->id,
            'tipe' => $item->tipe,
            'is_validated' => $item->is_validated,
            'validated_at' => $item->validated_at,
            'validator_name' => $item->validator?->name,
            'validator_username' => $item->validator?->username,
            'tahun_semester' => $item->tahunSemester
                ? $item->tahunSemester->tahunAjaran->tahun . ' - ' . $item->tahunSemester->semester
                : '-',
        ]);

        return view('validasi-semester.index', compact(
            'validasi',
            'totalCount',
            'paginator',
            'semester',
            'semesterId'
        ));
    }


    public function validateType(ValidasiSemester $validasiSemester)
    {
        $validasiSemester->update([
            'is_validated' => true,
            'validated_at' => now(),
            'validated_by' => Auth::id(),
        ]);

        return redirect()->back()->with('success', "{$validasiSemester->tipe} berhasil divalidasi!");
    }

    public function cancelValidation(ValidasiSemester $validasiSemester)
    {
        $validasiSemester->update([
            'is_validated' => false,
            'validated_at' => null,
            'validated_by' => null,
        ]);

        return redirect()->back()->with('success', "{$validasiSemester->tipe} validasi dibatalkan!");
    }

    //     public function validateAll(Request $request)
    // {
    //     ValidasiSemester::where('is_validated', false)->update([
    //         'is_validated' => true,
    //         'validated_at' => now(),
    //         'validated_by' => Auth::id(),
    //     ]);

    //     return redirect()->back()->with('success', 'Semua validasi berhasil dilakukan!');
    // }

    // public function validateAll(Request $request)
    // {
    //     $semesterId = $request->input('tahun_semester');

    //     // Query data yang belum divalidasi dan sesuai filter semester
    //     $query = ValidasiSemester::where('is_validated', false);

    //     if ($semesterId) {
    //         $query->where('tahun_semester_id', $semesterId);
    //     }

    //     $pending = $query->get();

    //     if ($pending->isEmpty()) {
    //         // Kalau tidak ada data yang bisa divalidasi
    //         return redirect()->back()->with('error', 'Tidak ada data yang bisa divalidasi untuk tahun semester ini.');
    //     }

    //     // Optional: cek kelengkapan data
    //     foreach ($pending as $item) {
    //         if (!$item->tahun_semester_id || !$item->tipe) {
    //             return redirect()->back()->with('error', "Data {$item->id} belum lengkap dan tidak bisa divalidasi.");
    //         }
    //     }

    //     // Validasi semua data yang sesuai
    //     $pending->each(function ($item) {
    //         $item->update([
    //             'is_validated' => true,
    //             'validated_at' => now(),
    //             'validated_by' => Auth::id(),
    //         ]);
    //     });

    //     return redirect()->back()->with('success', 'Semua data yang sesuai filter berhasil divalidasi!');
    // }

    public function validateAll(Request $request)
    {
        $semesterId = $request->input('semester_id');

        $query = ValidasiSemester::where('is_validated', false)
            ->when($semesterId, fn($q) => $q->where('tahun_semester_id', $semesterId));

        $pending = $query->get();

        if ($pending->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada data yang bisa divalidasi untuk filter ini.');
        }

        $pending->each(function ($item) {
            $item->update([
                'is_validated' => true,
                'validated_at' => now(),
                'validated_by' => Auth::id(),
            ]);
        });

        return redirect()->back()->with('success', 'Semua data yang sesuai filter berhasil divalidasi!');
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

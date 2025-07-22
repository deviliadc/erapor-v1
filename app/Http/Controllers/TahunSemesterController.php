<?php

namespace App\Http\Controllers;

use App\Models\KelasSiswa;
use App\Models\TahunSemester;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class TahunSemesterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);

        $query = TahunSemester::query();

        $totalCount = $query->count();
        $paginator = $query->paginate($perPage)->withQueryString();

        $tahun_semester = $paginator->through(function ($item) {
            $siswa_count = KelasSiswa::where('tahun_semester_id', $item->id)->count();
            return [
                'id' => $item->id,
                'tahun' => $item->tahun,
                'semester' => $item->semester,
                'status' => $item->is_active,
                'siswa_count' => $siswa_count, // jumlah siswa
            ];
        });

        $breadcrumbs = [
            ['label' => 'Manage Tahun Ajaran & Semester', 'url' => route('tahun-semester.index')],
        ];

        $title = 'Manage Tahun Ajaran & Semester';

        return view('tahun-semester.index', compact('tahun_semester', 'totalCount', 'breadcrumbs', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $breadcrumbs = [
            ['label' => 'Manage Tahun Ajaran & Semester', 'url' => route('tahun-semester.index')],
            ['label' => 'Create Tahun Ajaran & Semester'],
        ];

        $title = 'Create Tahun Ajaran & Semester';

        return view('tahun-semester.create', compact('breadcrumbs', 'title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tahun' => 'required|string|max:9|regex:/^\d{4}\/\d{4}$/', // contoh: 2024/2025
            'semester' => 'required|in:Ganjil,Genap',
            'is_active' => 'nullable|boolean',
        ]);

        $isActive = $request->has('is_active');

        if ($isActive) {
            TahunSemester::where('is_active', true)->update(['is_active' => false]);
        }

        TahunSemester::create([
            'tahun' => $validated['tahun'],
            'semester' => $validated['semester'],
            'is_active' => $isActive,
        ]);

        return redirect()->route('tahun-semester.index')->with('success', 'Data berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $tahunSemester = TahunSemester::findOrFail($id);

        // Ambil semua kelas_siswa pada tahun semester ini dengan paging
        $perPage = request()->input('per_page', 10);
        $kelasSiswaQuery = KelasSiswa::with('siswa')
            ->where('tahun_semester_id', $tahunSemester->id);

        $totalCount = $kelasSiswaQuery->count();
        $kelasSiswaPaginator = $kelasSiswaQuery->paginate($perPage)->withQueryString();

        // Hitung jumlah siswa, laki-laki, perempuan dari paginator
        $siswa_count = $kelasSiswaPaginator->total();
        $l_count = $kelasSiswaPaginator->getCollection()->where('siswa.jenis_kelamin', 'Laki-laki')->count();
        $p_count = $kelasSiswaPaginator->getCollection()->where('siswa.jenis_kelamin', 'Perempuan')->count();

        $tahun_semester_detail = $kelasSiswaPaginator->through(function ($item) use ($tahunSemester, $siswa_count, $l_count, $p_count) {
            return [
                'id' => $item->id,
                'tahun_semester' => $tahunSemester->tahun . ' - ' . $tahunSemester->semester,
                // 'nama_siswa' => $item->siswa->nama ?? '-',
                // 'jenis_kelamin' => $item->siswa->jenis_kelamin ?? '-',
                // 'no_absen' => $item->no_absen ?? '-',
                'siswa_count' => $siswa_count,
        'l_count' => $l_count,
        'p_count' => $p_count,
            ];
        });

        $breadcrumbs = [
            ['label' => 'Manage Tahun Ajaran & Semester', 'url' => route('tahun-semester.index')],
            ['label' => $tahunSemester->tahun . ' - ' . $tahunSemester->semester],
        ];

        $title = 'Detail Tahun Ajaran & Semester';

        // Untuk filter dropdown
        $tahunSemesterSelect = TahunSemester::all()->map(function ($ts) {
            return [
                'id' => $ts->id,
                'name' => $ts->tahun . ' - ' . $ts->semester,
            ];
        });

        return view('tahun-semester.show', compact(
            'tahunSemester',
            'tahun_semester_detail',
            'totalCount',
            'breadcrumbs',
            'title',
            'tahunSemesterSelect',
            'siswa_count',
            'l_count',
            'p_count'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $item = TahunSemester::findOrFail($id);

        $title = 'Edit Tahun Ajaran & Semester';

        $breadcrumbs = [
            ['label' => 'Manage Tahun Ajaran & Semester', 'url' => route('tahun-semester.index')],
            ['label' => 'Edit Tahun Ajaran & Semester'],
        ];

        return view('tahun-semester.edit', compact('item', 'title', 'breadcrumbs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'tahun' => 'required|string|max:9|regex:/^\d{4}\/\d{4}$/',
            'semester' => 'required|in:Ganjil,Genap',
            'is_active' => 'nullable|boolean',
        ]);

        $item = TahunSemester::findOrFail($id);
        $isActive = $request->has('is_active');

        // Cek jika user ingin menonaktifkan tahun semester yang sedang aktif
        if (!$isActive && $item->is_active) {
            // Hitung jumlah tahun semester yang aktif selain yang sedang diedit
            $activeCount = TahunSemester::where('is_active', true)->where('id', '!=', $id)->count();

            // Jika tidak ada tahun semester aktif lain, tolak update
            if ($activeCount === 0) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['is_active' => 'Minimal harus ada satu Tahun Semester yang aktif.']);
            }
        }

        if ($isActive) {
            TahunSemester::where('is_active', true)->where('id', '!=', $id)->update(['is_active' => false]);
        }

        $item->update([
            'tahun' => $validated['tahun'],
            'semester' => $validated['semester'],
            'is_active' => $isActive,
        ]);

        return redirect()->route('tahun-semester.index')->with('success', 'Data berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $item = TahunSemester::findOrFail($id);

        // Cegah penghapusan jika tahun semester sedang aktif
        if ($item->is_active) {
            return redirect()->route('tahun-semester.index')
                ->with('error', 'Tahun Semester yang sedang aktif tidak dapat dihapus.');
        }

        $item->delete();

        return redirect()->route('tahun-semester.index')
            ->with('success', 'Data berhasil dihapus.');
    }
}

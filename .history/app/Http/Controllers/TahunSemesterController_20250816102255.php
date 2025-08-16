<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\KelasSiswa;
use App\Models\TahunSemester;
use Illuminate\Http\Request;

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
            $siswa_count = KelasSiswa::where('tahun_ajaran_id', $item->tahun_ajaran_id)->count();
            return [
                'id' => $item->id,
                // 'tahun' => $item->tahun_ajaran_id,
                'tahun' => $item->tahunAjaran->tahun,
                'semester' => $item->semester,
                'status' => $item->is_active,
                'siswa_count' => $siswa_count,
            ];
        });
        $breadcrumbs = [
            ['label' => 'Manage Tahun Ajaran & Semester'],
        ];
        $title = 'Manage Tahun Ajaran & Semester';

        return view('tahun-semester.index', compact('tahun_semester', 'totalCount', 'breadcrumbs', 'title'));
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
        $validated = $request->validate([
            'tahun_ajaran_id' => 'required|exists:tahun_ajaran,id',
            // 'tahun' => 'required|string|max:9|regex:/^\d{4}\/\d{4}$/', // contoh: 2024/2025
            'semester' => 'required|in:Ganjil,Genap',
            'is_active' => 'nullable|boolean',
        ]);
        $isActive = $request->has('is_active');
        if ($isActive) {
            TahunSemester::where('is_active', true)->update(['is_active' => false]);
        }
        TahunSemester::create([
            // 'tahun' => $validated['tahun'],
            'tahun_ajaran_id' => $validated['tahun_ajaran_id'],
            'semester' => $validated['semester'],
            'is_active' => $isActive,
        ]);

        return redirect()->to(role_route('tahun-semester.index'))->with('success', 'Data berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // $tahunSemester = TahunSemester::findOrFail($id);
        $tahunSemester = TahunSemester::with('tahunAjaran')->findOrFail($id);
        $kelasList = Kelas::all();

        // Ambil semua kelas_siswa pada tahun semester ini dengan paging
        $perPage = request()->input('per_page', 10);
        $kelasSiswaQuery = KelasSiswa::with('siswa', 'kelas')
            ->where('tahun_ajaran_id', $tahunSemester->tahun_ajaran_id);

        $totalCount = $kelasSiswaQuery->count();
        $kelasSiswaPaginator = $kelasSiswaQuery->paginate($perPage)->withQueryString();

        // Hitung jumlah siswa, laki-laki, perempuan dari paginator
        $siswa_count = $kelasSiswaPaginator->total();
        $l_count = $kelasSiswaPaginator->getCollection()->where('siswa.jenis_kelamin', 'Laki-laki')->count();
        $p_count = $kelasSiswaPaginator->getCollection()->where('siswa.jenis_kelamin', 'Perempuan')->count();

        // Data chart per kelas
        $kelasGenderChart = [
            'labels' => [],
            'laki' => [],
            'perempuan' => [],
        ];
        foreach ($kelasList as $kelas) {
            $laki = KelasSiswa::where('kelas_id', $kelas->id)
                ->where('tahun_ajaran_id', $tahunSemester->tahun_ajaran_id)
                ->whereHas('siswa', fn($q) => $q->where('jenis_kelamin', 'Laki-laki'))->count();
            $perempuan = KelasSiswa::where('kelas_id', $kelas->id)
                ->where('tahun_ajaran_id', $tahunSemester->tahun_ajaran_id)
                ->whereHas('siswa', fn($q) => $q->where('jenis_kelamin', 'Perempuan'))->count();
            $kelasGenderChart['labels'][] = $kelas->nama;
            $kelasGenderChart['laki'][] = $laki;
            $kelasGenderChart['perempuan'][] = $perempuan;
        }

        $tahun_semester_detail = $kelasSiswaPaginator->through(function ($item) use ($tahunSemester, $siswa_count, $l_count, $p_count) {
            return [
                'id' => $item->id,
                'nama' => $item->siswa->nama ?? '-',
                'kelas' => $item->kelas->nama ?? '-',
                'nipd' => $item->siswa->nipd ?? '-',
                'nisn' => $item->siswa->nisn ?? '-',
                'jenis_kelamin' => $item->siswa->jenis_kelamin ?? '-',
                // 'tahun_semester' => $tahunSemester->tahun . ' - ' . $tahunSemester->semester,
                // 'nama_siswa' => $item->siswa->nama ?? '-',
                // 'jenis_kelamin' => $item->siswa->jenis_kelamin ?? '-',
                // 'no_absen' => $item->no_absen ?? '-',
                // 'siswa_count' => $siswa_count,
                // 'l_count' => $l_count,
                // 'p_count' => $p_count,
            ];
        });

        $breadcrumbs = [
            ['label' => 'Manage Tahun Ajaran & Semester', 'url' => role_route('tahun-semester.index')],
            // ['label' => $tahunSemester->tahun . ' - ' . $tahunSemester->semester],
            
        ];

        $title = 'Detail Tahun Ajaran & Semester';

        return view('tahun-semester.show', compact(
            'tahunSemester',
            'tahun_semester_detail',
            'totalCount',
            'breadcrumbs',
            'title',
            // 'tahunSemesterSelect',
            'siswa_count',
            'l_count',
            'p_count',
            'kelasGenderChart',
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // $item = TahunSemester::findOrFail($id);

        // $title = 'Edit Tahun Ajaran & Semester';

        // $breadcrumbs = [
        //     ['label' => 'Manage Tahun Ajaran & Semester', 'url' => role_route('tahun-semester.index')],
        //     ['label' => 'Edit Tahun Ajaran & Semester'],
        // ];

        // return view('tahun-semester.edit', compact(
        //     'item',
        //     // 'title',
        //     // 'breadcrumbs'
        // ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            // 'tahun' => 'required|string|max:9|regex:/^\d{4}\/\d{4}$/',
            'tahun_ajaran_id' => 'required|exists:tahun_ajaran,id',
            'semester' => 'required|in:Ganjil,Genap',
            'is_active' => 'nullable|boolean',
        ]);
        $item = TahunSemester::findOrFail($id);
        $isActive = $request->has('is_active');

        if (!$isActive && $item->is_active) {
            $activeCount = TahunSemester::where('is_active', true)->where('id', '!=', $id)->count();
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
            // 'tahun' => $validated['tahun'],
            'tahun_ajaran_id' => $validated['tahun_ajaran_id'],
            'semester' => $validated['semester'],
            'is_active' => $isActive,
        ]);

        return redirect()->to(role_route('tahun-semester.index'))->with('success', 'Data berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $item = TahunSemester::findOrFail($id);
        if ($item->is_active) {
            return redirect()->to(role_route('tahun-semester.index'))
                ->with('error', 'Tahun Semester yang sedang aktif tidak dapat dihapus.');
        }
        $item->delete();

        return redirect()->to(role_route('tahun-semester.index'))
            ->with('success', 'Data berhasil dihapus.');
    }
}

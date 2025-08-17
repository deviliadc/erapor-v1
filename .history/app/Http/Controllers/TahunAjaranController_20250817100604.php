<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\KelasSiswa;
use App\Models\TahunAjaran;
use App\Models\TahunSemester;
use Illuminate\Http\Request;

class TahunAjaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
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
        $validated = $request->validate([
            'tahun' => 'required|string|max:9|regex:/^\d{4}\/\d{4}$/',
            // 'mulai' => 'required|date',
            // 'selesai' => 'required|date|after:mulai',
            'is_active' => 'nullable|boolean',
        ]);
        $isActive = $request->has('is_active');
        if ($isActive) {
            TahunAjaran::where('is_active', true)->update(['is_active' => false]);
        }
        TahunAjaran::create([
            'tahun' => $validated['tahun'],
            'mulai' => $validated['mulai'],
            'selesai' => $validated['selesai'],
            'is_active' => $isActive,
        ]);

        return redirect()->to(role_route('tahun-semester.index', ['tab' => $request->tab ?? 'tahun-ajaran']))->with('success', 'Data berhasil ditambahkan.');
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
            ['label' => $tahunSemester->tahunAjaran->tahun . ' - ' . $tahunSemester->semester],
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
    // public function update(Request $request, string $id)
    // {
    //     $validated = $request->validate([
    //         'tahun' => 'required|string|max:9|regex:/^\d{4}\/\d{4}$/',
    //         'mulai' => 'required|date',
    //         'selesai' => 'required|date|after:mulai',
    //         'is_active' => 'nullable|boolean',
    //     ]);
    //     $item = TahunAjaran::findOrFail($id);
    //     $isActive = $request->has('is_active');
    //     if ($isActive) {
    //         TahunAjaran::where('is_active', true)->where('id', '!=', $id)->update(['is_active' => false]);
    //     }
    //     $item->update([
    //         'tahun' => $validated['tahun'],
    //         'mulai' => $validated['mulai'],
    //         'selesai' => $validated['selesai'],
    //         'is_active' => $isActive,
    //     ]);


    //     return redirect()->to(role_route('tahun-semester.index', ['tab' => $request->tab ?? 'tahun-ajaran']))->with('success', 'Data berhasil diperbarui.');
    // }
 public function update(Request $request, string $id)
{
    $validated = $request->validate([
        'tahun'   => 'required|string|max:9|regex:/^\d{4}\/\d{4}$/',
        'mulai'   => 'required|date',
        'selesai' => 'required|date|after:mulai',
        'is_active' => 'nullable|boolean',
    ]);

    $item = TahunAjaran::findOrFail($id);
    $isActive = $request->has('is_active');

    if ($isActive) {
        // Nonaktifkan semua tahun ajaran lain
        TahunAjaran::where('id', '!=', $id)->update(['is_active' => false]);

        // Aktifkan salah satu tahun semester terkait (utamakan Ganjil)
        $semesterGanjil = $item->tahunSemester()->where('semester', 'Ganjil')->first();
        if ($semesterGanjil) {
            // Aktifkan Ganjil, nonaktifkan semester lain
            TahunSemester::where('tahun_ajaran_id', $item->id)
                ->update(['is_active' => false]);
            $semesterGanjil->update(['is_active' => true]);
        } else {
            // Jika tidak ada semester sama sekali, matikan semua tahun semester
            TahunSemester::update(['is_active' => false]);
        }
    } else {
        // Tahun Ajaran HARUS ada 1 yang aktif
        $activeCount = TahunAjaran::where('is_active', true)->where('id', '!=', $id)->count();
        if ($activeCount === 0 && $item->is_active) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['is_active' => 'Minimal harus ada satu Tahun Ajaran yang aktif.']);
        }
    }

    $item->update([
        'tahun'   => $validated['tahun'],
        'mulai'   => $validated['mulai'],
        'selesai' => $validated['selesai'],
        'is_active' => $isActive,
    ]);

    return redirect()->to(role_route('tahun-semester.index', ['tab' => $request->tab ?? 'tahun-ajaran']))
        ->with('success', 'Data berhasil diperbarui.');
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $item = TahunAjaran::findOrFail($id);
        if ($item->is_active) {
            return redirect()->to(role_route('tahun-ajaran.index', ['tab' => $request->tab ?? 'tahun-ajaran']))
                ->with('error', 'Tahun Ajaran yang sedang aktif tidak dapat dihapus.');
        }
        $item->delete();

        return redirect()->to(role_route('tahun-semester.index', ['tab' => $request->tab ?? 'tahun-ajaran']))
            ->with('success', 'Data berhasil dihapus.');
    }
}

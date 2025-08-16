<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Siswa;
use App\Models\User;
use App\Models\Kelas;
use App\Models\GuruKelas;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Exports\ReusableExport;
use App\Imports\ReusableImport;
use App\Exports\ReusableTemplateExport;
use App\Models\KelasSiswa;
use App\Models\TahunAjaran;
use App\Models\TahunSemester;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $perPage = $request->input('per_page', 10);
        $sortBy = $request->input('sortBy', 'id');
        $sortDirection = $request->input('sortDirection', 'asc');

        // Mapping kolom frontend ke kolom DB
        $columnMap = [
            'id' => 'siswa.id',
            'name' => 'siswa.nama',
            'nipd' => 'siswa.nipd',
            'nisn' => 'siswa.nisn',
            'jenis_kelamin' => 'siswa.jenis_kelamin',
            'tempat_lahir' => 'siswa.tempat_lahir',
            'tanggal_lahir' => 'siswa.tanggal_lahir',
            'nama_ayah' => 'siswa.nama_ayah',
            'nama_ibu' => 'siswa.nama_ibu',
            'nama_wali' => 'siswa.nama_wali',
            'pendidikan_sebelumnya' => 'siswa.pendidikan_sebelumnya',
            'alamat' => 'siswa.alamat',
            'no_hp' => 'siswa.no_hp',
            'no_hp_wali' => 'siswa.no_hp',
            'email' => 'users.email',
            // 'status' => 'kelasSiswa.status',
        ];

        // $query = Siswa::query()
        //     ->leftJoin('users', 'users.id', '=', 'siswa.user_id')
        //     ->select(
        //         'siswa.*',
        //         'users.email'
        //     );
        $tahunAktif = TahunSemester::where('is_active', 1)->first();
        // $query = Siswa::whereHas('kelasSiswa', function ($q) use ($tahunAktif) {
        //     $q->where('tahun_semester_id', $tahunAktif->id)
        //         ->where('status', 'Aktif');
        // })
        //     ->with('user')
        //     ->with(['kelasSiswa' => function ($q) use ($tahunAktif) {
        //         $q->where('tahun_semester_id', $tahunAktif->id);
        //     }])
        //     ->select('siswa.*');
        $query = Siswa::with('user')
            ->with(['kelasSiswa' => function ($q) use ($tahunAktif) {
                // $q->where('tahun_semester_id', $tahunAktif->id);
                $q->where('tahun_ajaran_id', $tahunAktif->tahun_ajaran_id);
            }])
            ->select('siswa.*');

        // Batasan akses data
        if ($user->hasRole('guru')) {
            // Cek peran guru di tabel guru_kelas
            $guru = $user->guru;
            $isWali = GuruKelas::where('guru_id', $guru->id)
                ->where('peran', 'wali') // pastikan peran wali
                ->exists();

            if ($isWali) {
                // Guru wali: akses siswa di kelas yang diampu
                $kelasIds = GuruKelas::where('guru_id', $guru->id)
                    ->where('peran', 'wali')
                    ->pluck('kelas_id');
                $query->whereExists(function ($q) use ($kelasIds) {
                    $q->selectRaw(1)
                        ->from('kelas_siswa')
                        ->whereRaw('kelas_siswa.siswa_id = siswa.id')
                        ->whereIn('kelas_siswa.kelas_id', $kelasIds);
                });
            } else {
                // Guru pengajar: tidak bisa akses data siswa
                abort(403, 'Anda tidak memiliki akses ke data siswa.');
            }
        }
        // Admin/kepala sekolah: akses semua siswa

        // Search filter
        if ($request->filled('search')) {
            $q = $request->input('search');
            $query->where('nama', 'like', "%{$q}%")
                ->orWhere('nipd', 'like', "%{$q}%")
                ->orWhere('nisn', 'like', "%{$q}%");
        }

        // Sorting aman berdasarkan kolom yang dimapping
        if (isset($columnMap[$sortBy])) {
            $query->orderBy($columnMap[$sortBy], $sortDirection);
        } else {
            $query->orderBy('siswa.id', 'asc'); // default sorting
        }

        $totalCount = $query->count();
        $paginator = $query->paginate($perPage)->withQueryString();
        // $siswa = $paginator->through(fn($item) => [
        //     'id' => $item->id,
        //     'name' => $item->nama,
        //     'nipd' => $item->nipd,
        //     'nisn' => $item->nisn,
        //     'jenis_kelamin' => $item->jenis_kelamin,
        //     'tempat_lahir' => $item->tempat_lahir,
        //     'tanggal_lahir' => $item->tanggal_lahir,
        //     'pendidikan_sebelumnya' => $item->pendidikan_sebelumnya ?: '-',
        //     'alamat' => $item->alamat ?: '-',
        //     'no_hp' => $item->no_hp ?: '-',
        //     'email' => $item->user?->email ?: '-',
        //     'status' => $item->status ?: '-',
        //     // Data wali murid langsung dari tabel siswa
        //     'nama_ayah' => $item->nama_ayah ?: '-',
        //     'pekerjaan_ayah' => $item->pekerjaan_ayah ?: '-',
        //     'nama_ibu' => $item->nama_ibu ?: '-',
        //     'pekerjaan_ibu' => $item->pekerjaan_ibu ?: '-',
        //     'nama_wali' => $item->nama_wali ?: '-',
        //     'pekerjaan_wali' => $item->pekerjaan_wali ?: '-',
        //     'no_hp_wali' => $item->no_hp_wali ?: '-',
        //     'alamat_wali' => $item->alamat_wali ?: '-',
        // ]);
        $siswa = $paginator->through(function ($item) {
            $kelasSiswaAktif = $item->kelasSiswa->first();
            $kelasNama = '-';
            $statusKelas = '-';
            if ($kelasSiswaAktif) {
                $kelasNama = $kelasSiswaAktif->kelas ? $kelasSiswaAktif->kelas->nama : '-';
                $statusKelas = $kelasSiswaAktif->status ?? '-';
            }

            return [
                'id' => $item->id,
                'name' => $item->nama,
                'nipd' => $item->nipd,
                'nisn' => $item->nisn,
                'jenis_kelamin' => $item->jenis_kelamin,
                'tempat_lahir' => $item->tempat_lahir,
                'tanggal_lahir' => $item->tanggal_lahir,
                'pendidikan_sebelumnya' => $item->pendidikan_sebelumnya ?: '-',
                'alamat' => $item->alamat ?: '-',
                'no_hp' => $item->no_hp ?: '-',
                'email' => $item->user?->email ?: '-',
                'status' => $statusKelas,
                'kelas' => $kelasNama,
                // Data wali murid langsung dari tabel siswa
                'nama_ayah' => $item->nama_ayah ?: '-',
                'pekerjaan_ayah' => $item->pekerjaan_ayah ?: '-',
                'nama_ibu' => $item->nama_ibu ?: '-',
                'pekerjaan_ibu' => $item->pekerjaan_ibu ?: '-',
                'nama_wali' => $item->nama_wali ?: '-',
                'pekerjaan_wali' => $item->pekerjaan_wali ?: '-',
                'no_hp_wali' => $item->no_hp_wali ?: '-',
                'alamat_wali' => $item->alamat_wali ?: '-',
            ];
        });

        $breadcrumbs = [
            ['label' => 'Manage Siswa']
        ];

        $title = 'Manage Siswa';

        return view('siswa.index', compact(
            'siswa',
            'totalCount',
            'breadcrumbs',
            'title'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kelasOptions = Kelas::pluck('nama', 'id');
        // $tahunAktif = TahunSemester::where('is_active', 1)->first();
        $tahunAktif = TahunAjaran::where('is_active', 1)->first();

        $breadcrumbs = [
            ['label' => 'Manage Siswa', 'url' => role_route('siswa.index')],
            ['label' => 'Create Siswa'],
        ];
        $title = 'Create Siswa';

        return view('siswa.create', compact(
            'kelasOptions',
            'tahunAktif',
            'breadcrumbs',
            'title'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nipd' => 'required|string|unique:siswa,nipd',
            'nisn' => 'required|string|unique:siswa,nisn',
            'jenis_kelamin' => ['required', Rule::in(['Laki-laki', 'Perempuan'])],
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'required|date',
            'pendidikan_sebelumnya' => 'nullable|string|max:255',
            'alamat' => 'nullable|string|max:255',
            'no_hp' => [
                'nullable',
                'string',
                'max:20',
                'regex:/^(0|62)[0-9]{9,}$/'
            ],
            'email' => 'nullable|email|unique:users,email',
            'kelas_id' => 'required|exists:kelas,id',
            'status' => ['required', Rule::in(['Aktif', 'Lulus', 'Keluar', 'Mutasi'])],
            // Data wali murid langsung
            'nama_ayah' => 'nullable|string|max:255',
            'pekerjaan_ayah' => 'nullable|string|max:255',
            'nama_ibu' => 'nullable|string|max:255',
            'pekerjaan_ibu' => 'nullable|string|max:255',
            'nama_wali' => 'nullable|string|max:255',
            'pekerjaan_wali' => 'nullable|string|max:255',
            'no_hp_wali' => [
                'nullable',
                'string',
                'max:20',
                'regex:/^(0|62)[0-9]{9,}$/'
            ],
            'alamat_wali' => 'nullable|string|max:255',
        ]);

        // Format nomor HP siswa dan wali
        $noHpSiswa = !empty($validated['no_hp']) ? preg_replace('/[^0-9]/', '', $validated['no_hp']) : null;
        if ($noHpSiswa && str_starts_with($noHpSiswa, '0')) {
            $noHpSiswa = '62' . substr($noHpSiswa, 1);
        }
        $noHpWali = !empty($validated['no_hp_wali']) ? preg_replace('/[^0-9]/', '', $validated['no_hp_wali']) : null;
        if ($noHpWali && str_starts_with($noHpWali, '0')) {
            $noHpWali = '62' . substr($noHpWali, 1);
        }

        // Buat user siswa
        $tanggal = Carbon::parse($validated['tanggal_lahir']);
        $passwordPlain = $tanggal->format('dmY');
        $username = $validated['nisn'];
        $user = User::create([
            'name' => $validated['nama'],
            'email' => $validated['email'] ?? null,
            'username' => $username,
            'password' => Hash::make($passwordPlain),
        ]);
        $roleSiswa = Role::where('name', 'siswa')->first();
        $user->roles()->attach($roleSiswa);

        // Simpan data siswa (termasuk wali murid)
        $siswa = Siswa::create([
            'user_id' => $user->id,
            'nama' => $validated['nama'],
            'nipd' => $validated['nipd'],
            'nisn' => $validated['nisn'],
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'tempat_lahir' => $validated['tempat_lahir'] ?? null,
            'tanggal_lahir' => $validated['tanggal_lahir'],
            'pendidikan_sebelumnya' => $validated['pendidikan_sebelumnya'] ?? null,
            'alamat' => $validated['alamat'] ?? null,
            'no_hp' => $noHpSiswa,
            // 'status' => $validated['status'],
            'nama_ayah' => $validated['nama_ayah'],
            'pekerjaan_ayah' => $validated['pekerjaan_ayah'] ?? null,
            'nama_ibu' => $validated['nama_ibu'],
            'pekerjaan_ibu' => $validated['pekerjaan_ibu'] ?? null,
            'nama_wali' => $validated['nama_wali'] ?? null,
            'pekerjaan_wali' => $validated['pekerjaan_wali'] ?? null,
            'no_hp_wali' => $noHpWali,
            'alamat_wali' => $validated['alamat_wali'] ?? null,
        ]);

        // Simpan kelas siswa di tahun ajaran aktif
        $tahunSemesterAktif = TahunSemester::where('is_active', 1)->first();
if ($tahunSemesterAktif && !empty($validated['kelas_id'])) {
    $tahunAjaranId = $tahunSemesterAktif->tahun_ajaran_id;
    // Cek apakah sudah ada data kelas siswa di tahun semester aktif
    $sudahAda = KelasSiswa::where('siswa_id', $siswa->id)
        // ->where('tahun_semester_id', $tahunAktif->id)
        ->where('tahun_ajaran_id', $tahunAjaranId)
        ->where('kelas_id', $validated['kelas_id'])
        ->exists();

            if (!$sudahAda) {
                KelasSiswa::create([
                    'siswa_id' => $siswa->id,
                    // 'tahun_semester_id' => $tahunAktif->id,
                    'tahun_ajaran_id' => $tahunAjaranId,
                    'kelas_id' => $validated['kelas_id'],
                    'status' => $validated['status'],
                ]);
            } else {
                // Jika sudah ada, update status saja
                KelasSiswa::where('siswa_id', $siswa->id)
                    // ->where('tahun_semester_id', $tahunAktif->id)
                    ->where('tahun_ajaran_id', $tahunAjaranId)
                    ->where('kelas_id', $validated['kelas_id'])
                    ->update([
                        'status' => $validated['status'],
                    ]);
            }
        }

        return redirect()->to(role_route('siswa.index'))
            ->with('success', "Siswa berhasil ditambahkan. Username: <b>{$username}</b>, password: <b>{$passwordPlain}</b>");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $siswa = Siswa::findOrFail($id);

        // Ambil semua riwayat kelas siswa dari tabel kelas_siswa sesuai siswa
        $riwayatKelas = KelasSiswa::with(['kelas', 'tahunAjaran'])
            ->where('siswa_id', $siswa->id)
            ->orderByDesc('tahun_ajaran_id')
            ->get()
            ->map(function ($item, $idx) {
                return [
                    'id' => $item->id, // <-- tambahkan id dari kelas_siswa
                    'no' => $idx + 1,
                    // 'tahun' => $item->tahunSemester ? $item->tahunSemester->tahun : '-',
                    // 'semester' => $item->tahunSemester ? $item->tahunSemester->semester : '-',
                    'tahun' => $item->tahunAjaran && $item->tahunAjaran->tahun
                        ? $item->tahunAjaran->tahun . ' ' . $item->tahunAjaran->semester
                        : '-',
                    'kelas' => $item->kelas ? $item->kelas->nama : '-',
                    'status' => $item->status ?? '-',
                ];
            });

        $totalCount = $riwayatKelas->count();

        $breadcrumbs = [
            ['label' => 'Siswa', 'url' => role_route('siswa.index')],
            ['label' => $siswa->nama],
        ];

        $title = 'Detail Siswa';

        return view('siswa.show', compact(
            'siswa',
            'breadcrumbs',
            'title',
            'riwayatKelas',
            'totalCount'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = Auth::user();
        if ($user->hasRole('kepala_sekolah')) {
            abort(403, 'Kepala sekolah tidak boleh mengedit data siswa.');
        }

        $siswa = Siswa::with('user', 'kelasSiswa')->findOrFail($id);
        $kelasOptions = Kelas::pluck('nama', 'id');
        $tahunAktif = TahunAjaran::where('is_active', 1)->first();

        // Ambil kelas dan status dari kelas_siswa tahun aktif
        $kelasSiswaAktif = $siswa->kelasSiswa->where('tahun_ajaran_id', $tahunAktif?->id)->first();
        $selectedKelasId = $kelasSiswaAktif ? $kelasSiswaAktif->kelas_id : null;
        $selectedStatus = $kelasSiswaAktif ? $kelasSiswaAktif->status : null;

        $breadcrumbs = [
            ['label' => 'Manage Siswa', 'url' => role_route('siswa.index')],
            ['label' => 'Edit Siswa'],
        ];

        $title = 'Edit Siswa';

        return view('siswa.edit', compact(
            'siswa',
            'kelasOptions',
            'tahunAktif',
            'selectedKelasId',
            'selectedStatus',
            'breadcrumbs',
            'title'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $siswa = Siswa::with('user')->findOrFail($id);
        $user = $siswa->user;

        if ($user->roles()->whereIn('name', ['guru', 'wali_kelas'])->exists()) {
            return back()->withErrors('User sudah berperan guru atau wali kelas.');
        }

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nipd' => ['required', Rule::unique('siswa', 'nipd')->ignore($siswa->id)],
            'nisn' => ['required', Rule::unique('siswa', 'nisn')->ignore($siswa->id)],
            'jenis_kelamin' => ['required', Rule::in(['Laki-laki', 'Perempuan'])],
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'pendidikan_sebelumnya' => 'nullable|string|max:255',
            'alamat' => 'nullable|string|max:255',
            'no_hp' => [
                'nullable',
                'string',
                'max:20',
                'regex:/^(0|62)[0-9]{9,}$/',
            ],
            'email' => ['nullable', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'kelas_id' => ['required', Rule::exists('kelas', 'id')],
            'status' => ['required', Rule::in(['Aktif', 'Lulus', 'Keluar', 'Mutasi'])],
            // Data wali murid langsung
            'nama_ayah' => 'nullable|string|max:255',
            'pekerjaan_ayah' => 'nullable|string|max:255',
            'nama_ibu' => 'nullable|string|max:255',
            'pekerjaan_ibu' => 'nullable|string|max:255',
            'nama_wali' => 'nullable|string|max:255',
            'pekerjaan_wali' => 'nullable|string|max:255',
            'no_hp_wali' => [
                'nullable',
                'string',
                'max:20',
                'regex:/^(0|62)[0-9]{9,}$/',
            ],
            'alamat_wali' => 'nullable|string|max:255'
        ]);

        // Format nomor HP siswa
        if (!empty($validated['no_hp'])) {
            $noHp = preg_replace('/[^0-9]/', '', $validated['no_hp']);
            if (str_starts_with($noHp, '0')) {
                $noHp = '62' . substr($noHp, 1);
            }
            $validated['no_hp'] = $noHp;
        }

        // Format nomor HP wali
        if (!empty($validated['no_hp_wali'])) {
            $noHpWali = preg_replace('/[^0-9]/', '', $validated['no_hp_wali']);
            if (str_starts_with($noHpWali, '0')) {
                $noHpWali = '62' . substr($noHpWali, 1);
            }
            $validated['no_hp_wali'] = $noHpWali;
        }

        // Update user
        $user->update([
            'name' => $validated['nama'],
            'email' => $validated['email'],
            'username' => $validated['nisn'],
        ]);

        // Update siswa (termasuk data wali murid langsung)
        $siswa->update([
            'nama' => $validated['nama'],
            'nipd' => $validated['nipd'],
            'nisn' => $validated['nisn'],
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'tempat_lahir' => $validated['tempat_lahir'] ?? null,
            'tanggal_lahir' => $validated['tanggal_lahir'],
            'pendidikan_sebelumnya' => $validated['pendidikan_sebelumnya'] ?? null,
            'alamat' => $validated['alamat'] ?? null,
            'no_hp' => $validated['no_hp'] ?? null,
            // 'status' => $validated['status'],
            // Data wali murid langsung
            'nama_ayah' => $validated['nama_ayah'],
            'pekerjaan_ayah' => $validated['pekerjaan_ayah'] ?? null,
            'nama_ibu' => $validated['nama_ibu'],
            'pekerjaan_ibu' => $validated['pekerjaan_ibu'] ?? null,
            'nama_wali' => $validated['nama_wali'] ?? null,
            'pekerjaan_wali' => $validated['pekerjaan_wali'] ?? null,
            'no_hp_wali' => $validated['no_hp_wali'] ?? null,
            'alamat_wali' => $validated['alamat_wali'] ?? null,
        ]);

        $tahunAktif = TahunSemester::where('is_active', 1)->first();
        if ($tahunAktif && !empty($validated['kelas_id'])) {
            $sudahAda = KelasSiswa::where('siswa_id', $siswa->id)
                // ->where('tahun_semester_id', $tahunAktif->id)
                ->where('tahun_ajaran_id', $tahunAktif->tahun_ajaran_id)
                ->where('kelas_id', $validated['kelas_id'])
                ->exists();

            if (!$sudahAda) {
                KelasSiswa::create([
                    'siswa_id' => $siswa->id,
                    'tahun_semester_id' => $tahunAktif->id,
                    'kelas_id' => $validated['kelas_id'],
                    'status' => $validated['status'],
                ]);
            } else {
                KelasSiswa::where('siswa_id', $siswa->id)
                    // ->where('tahun_semester_id', $tahunAktif->id)
                    ->where('tahun_ajaran_id', $tahunAktif->tahun_ajaran_id)
                    ->where('kelas_id', $validated['kelas_id'])
                    ->update([
                        'status' => $validated['status'],
                    ]);
            }
        }

        return redirect()->to(role_route('siswa.index'))
            ->with('success', 'Data siswa berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $siswa = Siswa::with(['user', 'kelasSiswa'])->findOrFail($id);

        // Cek apakah siswa masih terhubung di kelas_siswa
        if ($siswa->kelasSiswa()->exists()) {
            return redirect()->to(role_route('siswa.index'))
                ->with('error', 'Siswa tidak dapat dihapus karena masih terdaftar di kelas.');
        }

        if ($siswa->wali && $siswa->wali->siswa()->count() === 1) {
            $siswa->wali->delete();
        }
        if ($siswa->user) {
            $siswa->user->delete();
        }
        $siswa->delete();

        return redirect()->to(role_route('siswa.index'))
            ->with('success', 'Siswa berhasil dihapus.');
    }

    public function kelasAktif()
    {
        $tahunAktif = TahunSemester::where('is_active', 1)->first();
        if (!$tahunAktif) return null;
        $kelasSiswa = $this->kelasSiswa()
            // ->where('tahun_semester_id', $tahunAktif->id)->first();
            ->where('tahun_ajaran_id', $tahunAktif->tahun_ajaran_id)->first();
        return $kelasSiswa ? $kelasSiswa->kelas : null;
    }

    public function editKelas()
    {
        $siswa = Siswa::with('user', 'kelas')->paginate(10); // tampilkan semua siswa
        $kelas = Kelas::all(); // untuk dropdown
        return view('siswa.atur-kelas', compact('siswa', 'kelas'));
    }

    public function updateKelas(Request $request)
    {
        foreach ($request->kelas as $siswaId => $kelasId) {
            Siswa::where('id', $siswaId)->update([
                'kelas_id' => $kelasId
            ]);
        }
        return redirect()->back()->with('success', 'Kelas siswa berhasil diperbarui.');
    }

    public function export(Request $request)
    {
        $type = $request->input('type', 'excel');
        $tanggal = now()->format('Ymd_His');
        $filename = $request->input('filename', "data_siswa_{$tanggal}");
        $tahunAktif = TahunSemester::where('is_active', 1)->first();

        // $data = Siswa::with(['kelas', 'waliMurid'])->get();
        $data = Siswa::all();
        // data siswa Aktif pada tahun ajaran ini dengan status aktif
        // $data = Siswa::where('status', 'Aktif')->get();
        // Siswa::whereHas('kelasSiswa', function ($q) use ($tahunAktif) {
        //     $q->where('tahun_semester_id', $tahunSemesterId);
        // })
        //     ->where('status', 'aktif')
        //     ->get();

        $headings = [
            'Nama',
            'NIPD',
            'NISN',
            'Jenis Kelamin',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Pendidikan Sebelumnya',
            'Agama',
            'Kelas',
            'Status',
            'Alamat',
            'No HP',
            'Email',
            'Nama Ayah',
            'Pekerjaan Ayah',
            'Nama Ibu',
            'Pekerjaan Ibu',
            'Nama Wali',
            'Pekerjaan Wali',
            'No HP Wali',
            'Alamat Wali'
        ];
        $enumInfo = [
            '',
            '',
            '',
            'Laki-laki/Perempuan',
            '',
            'yyyy-mm-dd',
            '',
            'Islam/Kristen/Katolik/Hindu/Buddha/Konghucu',
            '',
            'Aktif/Lulus/Keluar/Mutasi',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            ''
        ];
        $formatted = $data->map(function ($siswa) use ($tahunAktif) {
            // Ambil kelas siswa pada tahun ajaran aktif
            $kelasNama = '-';
            if ($tahunAktif) {
                $kelasSiswa = $siswa->kelasSiswa()
                    // ->where('tahun_semester_id', $tahunAktif->id)->first();
                    ->where('tahun_ajaran_id', $tahunAktif->tahun_ajaran_id)->first();
                if ($kelasSiswa && $kelasSiswa->kelas) {
                    $kelasNama = $kelasSiswa->kelas->nama;
                }
            }

            return [
                $siswa->nama,
                $siswa->nipd,
                $siswa->nisn,
                $siswa->jenis_kelamin,
                $siswa->tempat_lahir,
                $siswa->tanggal_lahir,
                $siswa->pendidikan_sebelumnya ?? '-',
                $siswa->agama,
                $kelasNama,
                $siswa->status ? $siswa->status : '-',
                $siswa->alamat,
                $siswa->no_hp ?? '-',
                $siswa->email ?? '-',
                $siswa->nama_ayah ?? '-',
                $siswa->pekerjaan_ayah ?? '-',
                $siswa->nama_ibu ?? '-',
                $siswa->pekerjaan_ibu ?? '-',
                $siswa->nama_wali ?? '-',
                $siswa->pekerjaan_wali ?? '-',
                $siswa->no_hp_wali ?? '-',
                $siswa->alamat_wali ?? '-',
            ];
        })->toArray();

        // if ($type === 'pdf') {
        //     $pdf = Pdf::loadView('siswa.export-pdf', [
        //         'headings' => $headings,
        //         'rows' => $formatted,
        //     ]);
        //     return $pdf->download("{$filename}.pdf");
        // }
        // if ($type === 'pdf') {
        //     $pdf = Pdf::loadView('exports.reusable-pdf', [
        //         'headings' => $headings,
        //         'rows' => $formatted,
        //         'title' => 'Data Siswa',
        //     ]);

        //     // Pilih orientasi: 'landscape' atau 'portrait'
        //     $orientation = $request->input('orientation', 'landscape'); // default landscape
        //     $pdf->setPaper('a4', $orientation);

        //     return $pdf->download("{$filename}.pdf");
        // }

        // Default: Excel
        return Excel::download(
            new ReusableExport($headings, $enumInfo, $formatted),
            "{$filename}.xlsx"
        );
    }

    public function template()
    {
        // Header template: huruf besar dan spasi, urutan sesuai database
        $headers = [
            'Nama',
            'NIPD',
            'NISN',
            'Jenis Kelamin',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Pendidikan Sebelumnya',
            'Agama',
            'Kelas',
            'Status',
            'Alamat',
            'No HP',
            'Email',
            'Nama Ayah',
            'Pekerjaan Ayah',
            'Nama Ibu',
            'Pekerjaan Ibu',
            'Nama Wali',
            'Pekerjaan Wali',
            'No HP Wali',
            'Alamat Wali'
        ];

        $enumInfo = [
            '',
            '',
            '',
            'Laki-laki/Perempuan',
            '',
            'yyyy-mm-dd',
            '',
            'Islam/Kristen/Katolik/Hindu/Buddha/Konghucu',
            '1/2/3/4/5/6',
            'Aktif/Lulus/Keluar/Mutasi',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            ''
        ];

        return Excel::download(
            new ReusableTemplateExport([$headers, $enumInfo]),
            'template_siswa.xlsx'
        );
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        if (!$request->hasFile('file')) {
            return response()->json([
                'status' => 'error',
                'message' => 'File tidak ditemukan pada request.'
            ], 422);
        }
        if (!$request->file('file')->isValid()) {
            return response()->json([
                'status' => 'error',
                'message' => 'File upload tidak valid.'
            ], 422);
        }

        $success = 0;
        $failed = 0;
        $errors = [];
        $headerError = false;

        // Field yang wajib ada di header (huruf besar, spasi, urutan sama template)
        $requiredHeaders = [
            'Nama',
            'NIPD',
            'NISN',
            'Jenis Kelamin',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Pendidikan Sebelumnya',
            'Agama',
            'Kelas',
            'Status',
            'Alamat',
            'No HP',
            'Email',
            'Nama Ayah',
            'Pekerjaan Ayah',
            'Nama Ibu',
            'Pekerjaan Ibu',
            'Nama Wali',
            'Pekerjaan Wali',
            'No HP Wali',
            'Alamat Wali'
        ];

        Excel::import(
            new ReusableImport(function ($row, $index) use (&$success, &$failed, &$errors, &$headerError, $requiredHeaders) {
                $data = $row->toArray();

                // Debug: log data yang diproses
                // if ($index > 1) {
                //     \Log::info('Import Siswa Data', ['index' => $index, 'data' => $data]);
                // }

                if ($index === 0) {
                    // baris 1 = header, cek apakah sesuai $requiredHeaders (harus urut sama)
                    if ($data !== $requiredHeaders) {
                        // error header tidak sesuai
                        throw new \Exception('Header file tidak sesuai.');
                    }
                    return; // skip header baris 1
                }
                // Baris kedua: enum/keterangan, diskip
                if ($index === 1) return;

                if ($headerError) return;

                $dataArr = $row->toArray();
                $data = [];
                foreach ($requiredHeaders as $i => $key) {
                    $data[$key] = $dataArr[$i] ?? null;
                }

                // Mapping field sesuai header template
                $data = [
                    'nama' => $data['Nama'] ?? null,
                    'nipd' => $data['NIPD'] ?? null,
                    'nisn' => $data['NISN'] ?? null,
                    'jenis_kelamin' => $data['Jenis Kelamin'] ?? null,
                    'tempat_lahir' => $data['Tempat Lahir'] ?? null,
                    'tanggal_lahir' => $data['Tanggal Lahir'] ?? null,
                    'pendidikan_sebelumnya' => $data['Pendidikan Sebelumnya'] ?? null,
                    'agama' => $data['Agama'] ?? null,
                    'kelas' => $data['Kelas'] ?? null, // Kelas tidak digunakan di sini, hanya untuk validasi
                    'status' => $data['Status'] ?? null,
                    'alamat' => $data['Alamat'] ?? null,
                    'no_hp' => $data['No HP'] ?? null,
                    'email' => $data['Email'] ?? null,
                    'nama_ayah' => $data['Nama Ayah'] ?? null,
                    'pekerjaan_ayah' => $data['Pekerjaan Ayah'] ?? null,
                    'nama_ibu' => $data['Nama Ibu'] ?? null,
                    'pekerjaan_ibu' => $data['Pekerjaan Ibu'] ?? null,
                    'nama_wali' => $data['Nama Wali'] ?? null,
                    'pekerjaan_wali' => $data['Pekerjaan Wali'] ?? null,
                    'no_hp_wali' => $data['No HP Wali'] ?? null,
                    'alamat_wali' => $data['Alamat Wali'] ?? null,
                ];

                // Mapping jenis kelamin
                if (isset($data['jenis_kelamin'])) {
                    $jk = strtolower(trim($data['jenis_kelamin']));
                    if ($jk === 'l' || $jk === 'laki-laki' || $jk === 'laki laki') $data['jenis_kelamin'] = 'Laki-laki';
                    elseif ($jk === 'p' || $jk === 'perempuan') $data['jenis_kelamin'] = 'Perempuan';
                }

                // Mapping tanggal lahir
                if (!empty($data['tanggal_lahir'])) {
                    // Jika berupa angka (serial Excel), konversi ke tanggal
                    if (is_numeric($data['tanggal_lahir'])) {
                        // Excel serial date to PHP date
                        $unix = ($data['tanggal_lahir'] - 25569) * 86400;
                        $data['tanggal_lahir'] = gmdate('Y-m-d', $unix);
                    } else {
                        $tgl = preg_replace('/[^0-9]/', '', $data['tanggal_lahir']);
                        if (strlen($tgl) === 6) {
                            $tahun = substr($tgl, 4, 2);
                            $tahun = $tahun > 50 ? '19' . $tahun : '20' . $tahun;
                            $data['tanggal_lahir'] = $tahun . '-' . substr($tgl, 2, 2) . '-' . substr($tgl, 0, 2);
                        } elseif (strlen($tgl) === 8) {
                            $data['tanggal_lahir'] = substr($tgl, 4, 4) . '-' . substr($tgl, 2, 2) . '-' . substr($tgl, 0, 2);
                        } elseif (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $data['tanggal_lahir'])) {
                            [$d, $m, $y] = explode('/', $data['tanggal_lahir']);
                            $data['tanggal_lahir'] = "$y-$m-$d";
                        }
                    }
                }

                // Mapping status
                if (isset($data['status'])) {
                    $status = strtolower(trim($data['status']));
                    if ($status === 'aktif') $data['status'] = 'Aktif';
                    elseif ($status === 'lulus') $data['status'] = 'Lulus';
                    elseif ($status === 'keluar') $data['status'] = 'Keluar';
                    elseif ($status === 'mutasi') $data['status'] = 'Mutasi';
                }

                // Mapping agama
                if (isset($data['agama'])) {
                    $agama = strtolower(trim($data['agama']));
                    $mapAgama = [
                        'islam' => 'Islam',
                        'kristen' => 'Kristen',
                        'katolik' => 'Katolik',
                        'hindu' => 'Hindu',
                        'buddha' => 'Buddha',
                        'konghucu' => 'Konghucu'
                    ];
                    foreach ($mapAgama as $key => $val) {
                        if ($agama === $key) $data['agama'] = $val;
                    }
                }

                // Validasi duplikat NIPD/NISN/email
                $duplikat = [];
                if (!empty($data['nipd']) && \App\Models\Siswa::where('nipd', $data['nipd'])->exists()) {
                    $duplikat[] = 'NIPD';
                }
                if (!empty($data['nisn']) && \App\Models\Siswa::where('nisn', $data['nisn'])->exists()) {
                    $duplikat[] = 'NISN';
                }
                if (!empty($data['email']) && \App\Models\User::where('email', $data['email'])->exists()) {
                    $duplikat[] = 'Email';
                }
                if (count($duplikat) > 0) {
                    $failed++;
                    $errors[] = ($data['nama'] ?? '(Tanpa Nama)') . ' duplikat: ' . implode(', ', $duplikat);
                    return;
                }

                // Cast ke string agar validasi string berhasil
                $data['nipd'] = isset($data['nipd']) ? (string) $data['nipd'] : null;
                $data['nisn'] = isset($data['nisn']) ? (string) $data['nisn'] : null;
                $data['no_hp'] = isset($data['no_hp']) ? (string) $data['no_hp'] : null;
                $data['no_hp_wali'] = isset($data['no_hp_wali']) ? (string) $data['no_hp_wali'] : null;

                $validator = Validator::make($data, [
                    'nama' => 'required|string|max:255',
                    'nipd' => 'required|string',
                    'nisn' => 'required|string',
                    'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
                    'tempat_lahir' => 'nullable|string|max:255',
                    'tanggal_lahir' => 'required|date',
                    'pendidikan_sebelumnya' => 'nullable|string|max:255',
                    'agama' => 'required|in:Islam,Kristen,Katolik,Hindu,Buddha,Konghucu',
                    'alamat' => 'nullable|string|max:255',
                    'no_hp' => 'nullable|string|max:20',
                    'email' => 'nullable|email',
                    'kelas' => 'nullable',
                    'status' => 'required|in:Aktif,Lulus,Keluar,Mutasi',
                    'nama_ayah' => 'nullable|string|max:255',
                    'pekerjaan_ayah' => 'nullable|string|max:255',
                    'nama_ibu' => 'nullable|string|max:255',
                    'pekerjaan_ibu' => 'nullable|string|max:255',
                    'nama_wali' => 'nullable|string|max:255',
                    'pekerjaan_wali' => 'nullable|string|max:255',
                    'no_hp_wali' => 'nullable|string|max:20',
                    'alamat_wali' => 'nullable|string|max:255',
                ]);

                if ($validator->fails()) {
                    $failed++;
                    $errMsg = ($data['nama'] ?? '(Tanpa Nama)') . ' error: ' . implode(', ', $validator->errors()->all());
                    $errors[] = $errMsg;
                    return;
                }

                // Format nomor HP siswa dan wali ke format 62xxxxxxxxxxx
                $formatHp = function ($no) {
                    if (!$no) return '';
                    $no = preg_replace('/[^0-9]/', '', $no); // hanya angka
                    if (str_starts_with($no, '0')) {
                        return '62' . substr($no, 1);
                    }
                    if (str_starts_with($no, '62')) {
                        return $no;
                    }
                    // Jika awalan bukan 0 dan bukan 62, tambahkan 62 di depan
                    return '62' . $no;
                };

                // Buat user siswa
                $password = Carbon::parse($data['tanggal_lahir'])->format('dmY');
                $user = User::create([
                    'name' => $data['nama'],
                    'email' => $data['email'] ?? null,
                    'username' => $data['nisn'],
                    'password' => Hash::make($password),
                ]);
                $role = Role::where('name', 'siswa')->first();
                $user->roles()->attach($role);

                // Bersihkan data sebelum simpan
                foreach (
                    [
                        'alamat',
                        'alamat_wali',
                        'pendidikan_sebelumnya',
                        'tempat_lahir',
                        'nama_ayah',
                        'pekerjaan_ayah',
                        'nama_ibu',
                        'pekerjaan_ibu',
                        'nama_wali',
                        'pekerjaan_wali',
                        'no_hp',
                        'no_hp_wali'
                    ] as $field
                ) {
                    if (!isset($data[$field]) || $data[$field] === null || $data[$field] === '?' || trim($data[$field]) === '?') {
                        $data[$field] = '';
                    }
                }

                // Simpan siswa
                Siswa::create([
                    'user_id' => $user->id,
                    'nama' => $data['nama'],
                    'nipd' => $data['nipd'],
                    'nisn' => $data['nisn'],
                    'jenis_kelamin' => $data['jenis_kelamin'],
                    'tempat_lahir' => $data['tempat_lahir'],
                    'tanggal_lahir' => $data['tanggal_lahir'],
                    'pendidikan_sebelumnya' => $data['pendidikan_sebelumnya'],
                    'agama' => $data['agama'],
                    'kelas' => $data['kelas'] ?? null,
                    'status' => $data['status'] ?? 'Aktif',
                    'alamat' => $data['alamat'],
                    'no_hp' => $formatHp($data['no_hp']),
                    'nama_ayah' => $data['nama_ayah'],
                    'pekerjaan_ayah' => $data['pekerjaan_ayah'],
                    'nama_ibu' => $data['nama_ibu'],
                    'pekerjaan_ibu' => $data['pekerjaan_ibu'],
                    'nama_wali' => $data['nama_wali'],
                    'pekerjaan_wali' => $data['pekerjaan_wali'],
                    'no_hp_wali' => $formatHp($data['no_hp_wali']),
                    'alamat_wali' => $data['alamat_wali'],
                ]);
                $success++;

                $siswaBaru = Siswa::where('nisn', $data['nisn'])->first();
                $tahunAktif = TahunSemester::where('is_active', 1)->first();
                if ($siswaBaru && $tahunAktif && !empty($data['kelas'])) {
                    $kelasObj = Kelas::where('nama', $data['kelas'])->first();
                    if ($kelasObj) {
                        // Cek apakah sudah ada data kelas siswa di tahun semester aktif
                        $sudahAda = KelasSiswa::where('siswa_id', $siswaBaru->id)
                            // ->where('tahun_semester_id', $tahunAktif->id)
                            ->where('tahun_ajaran_id', $tahunAktif->tahun_ajaran_id)
                            ->where('kelas_id', $kelasObj->id)
                            ->exists();

                        if ($sudahAda) {
                            $failed++;
                            $errors[] = "{$data['nama']} sudah terdaftar di kelas {$kelasObj->nama} tahun {$tahunAktif->tahun} semester {$tahunAktif->semester}";
                        } else {
                            KelasSiswa::updateOrCreate([
                                'siswa_id' => $siswaBaru->id,
                                'tahun_semester_id' => $tahunAktif->id,
                            ], [
                                'kelas_id' => $kelasObj->id,
                                'status' => $data['status'] ?? 'Aktif',
                            ]);
                        }
                    }
                }
            }),
            $request->file('file')
        );

        $msg = "Import selesai. Berhasil: {$success}, Gagal: {$failed}";
        if ($failed > 0) {
            $msg .= ". Data gagal: " . implode('; ', $errors);
        }

        if ($request->ajax()) {
            if ($headerError || $failed > 0) {
                return response()->json([
                    'status' => 'error',
                    'message' => $msg,
                    'errors' => $errors
                ], 422);
            }
            return response()->json([
                'status' => 'success',
                'message' => $msg
            ]);
        }

        if ($headerError) {
            return back()->with('error', implode('<br>', $errors));
        }
        return redirect()->to(role_route('siswa.index'))->with('success', $msg);
    }
}

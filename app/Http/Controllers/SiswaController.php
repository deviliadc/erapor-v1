<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Siswa;
use App\Models\User;
use App\Models\WaliMurid;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Exports\ReusableExport;
use App\Models\Kelas;
use Maatwebsite\Excel\Facades\Excel;

class SiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $sortBy = $request->input('sortBy', 'id');
        $sortDirection = $request->input('sortDirection', 'asc');

        // Mapping kolom frontend ke kolom DB
        $columnMap = [
            'id' => 'siswa.id',
            'name' => 'siswa.nama',
            'nis' => 'siswa.nis',
            'nisn' => 'siswa.nisn',
            'jenis_kelamin' => 'siswa.jenis_kelamin',
            'tempat_lahir' => 'siswa.tempat_lahir',
            'tanggal_lahir' => 'siswa.tanggal_lahir',
            'nama_ayah' => 'wali.nama_ayah',
            'nama_ibu' => 'wali.nama_ibu',
            'nama_wali' => 'wali.nama_wali',
            'pendidikan_sebelumnya' => 'siswa.pendidikan_sebelumnya',
            'alamat' => 'siswa.alamat',
            'no_hp' => 'siswa.no_hp',
            'no_hp_wali' => 'wali.no_hp',
            'email' => 'users.email',
            'status' => 'siswa.status',
        ];

        $query = Siswa::query()
            ->leftJoin('wali_murid as wali', 'wali.id', '=', 'siswa.wali_murid_id')
            ->leftJoin('users', 'users.id', '=', 'siswa.user_id')
            ->select(
                'siswa.*',
                'wali.nama_ayah',
                'wali.nama_ibu',
                'wali.nama_wali',
                'wali.no_hp as no_hp_wali',
                'users.email'
            );

        // Search filter
        if ($request->filled('search')) {
            $q = $request->input('search');
            $query->where('nama', 'like', "%{$q}%")
                ->orWhere('nis', 'like', "%{$q}%")
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
        $siswa = $paginator->through(fn($item) => [
            'id' => $item->id,
            'name' => $item->nama,
            'nis' => $item->nis,
            'nisn' => $item->nisn,
            'jenis_kelamin' => $item->jenis_kelamin,
            'tempat_lahir' => $item->tempat_lahir,
            'tanggal_lahir' => $item->tanggal_lahir,
            'nama_ayah' => $item->wali?->nama_ayah ?? '-',
            'nama_ibu' => $item->wali?->nama_ibu ?? '-',
            'nama_wali' => $item->wali?->nama_wali ?? '-',
            'pendidikan_sebelumnya' => $item->pendidikan_sebelumnya ?? '-',
            'alamat' => $item->alamat ?? '-',
            'no_hp' => $item->no_hp ?? '-',
            'no_hp_wali' => $item->wali?->no_hp ?? '-',
            'email' => $item->user?->email ?? '-',
            'status' => $item->status,
        ]);

        $breadcrumbs = [
            ['label' => 'Manage Siswa', 'url' => route('siswa.index')]
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
        $wali_list = WaliMurid::all();

        $breadcrumbs = [
            ['label' => 'Manage Siswa', 'url' => route('siswa.index')],
            ['label' => 'Create Siswa'],
        ];

        $title = 'Create Siswa';

        return view('siswa.create', compact('wali_list', 'breadcrumbs', 'title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi dasar siswa
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nis' => 'required|string|unique:siswa,nis',
            'nisn' => 'required|string|unique:siswa,nisn',
            'jenis_kelamin' => ['required', Rule::in(['Laki-laki', 'Perempuan'])],
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'required|date',
            'pendidikan_sebelumnya' => 'nullable|string|max:255',
            'alamat' => 'nullable|string|max:500',
            'no_hp' => [
                'nullable',
                'string',
                'max:20',
                'regex:/^(0|62)[0-9]{9,}$/'
            ],
            'email' => 'required|email|unique:users,email',
            'status' => ['required', Rule::in(['Aktif', 'Lulus', 'Keluar', 'Mutasi'])],
            'wali_murid_id' => 'nullable|string', // bisa 'baru' atau ID numerik
        ]);

        // Format no_hp siswa ke internasional (awalan 62)
        $noHpSiswa = null;
        if (!empty($validated['no_hp'])) {
            $noHpSiswa = preg_replace('/[^0-9]/', '', $validated['no_hp']);
            if (str_starts_with($noHpSiswa, '0')) {
                $noHpSiswa = '62' . substr($noHpSiswa, 1);
            }
        }

        // Tangani data wali murid
        $wali_murid_id = null;

        if ($request->wali_murid_id === 'baru') {
            // Validasi data wali baru
            $request->validate([
                'nama_ayah' => 'required|string|max:255',
                'nama_ibu' => 'required|string|max:255',
                'nama_wali' => 'nullable|string|max:255',
                'no_hp_wali' => [
                    'nullable',
                    'string',
                    'max:20',
                    'regex:/^(0|62)[0-9]{9,}$/'
                ],
                'pekerjaan_ayah' => 'nullable|string|max:255',
                'pekerjaan_ibu' => 'nullable|string|max:255',
                'pekerjaan_wali' => 'nullable|string|max:255',
                'alamat_wali' => 'nullable|string|max:500',
            ]);

            // Format no_hp wali
            $noHpWali = preg_replace('/[^0-9]/', '', $request->no_hp_wali);
            if ($noHpWali && str_starts_with($noHpWali, '0')) {
                $noHpWali = '62' . substr($noHpWali, 1);
            }

            // Simpan wali baru
            $waliMurid = WaliMurid::create([
                'nama_ayah' => $request->nama_ayah,
                'nama_ibu' => $request->nama_ibu,
                'nama_wali' => $request->nama_wali,
                'no_hp' => $noHpWali,
                'pekerjaan_ayah' => $request->pekerjaan_ayah,
                'pekerjaan_ibu' => $request->pekerjaan_ibu,
                'pekerjaan_wali' => $request->pekerjaan_wali,
                'alamat' => $request->alamat_wali,
            ]);

            $wali_murid_id = $waliMurid->id;
        } elseif (is_numeric($request->wali_murid_id)) {
            $exists = WaliMurid::where('id', $request->wali_murid_id)->exists();
            if (!$exists) {
                return back()->withErrors('Wali murid tidak ditemukan.');
            }
            $wali_murid_id = $request->wali_murid_id;
        }

        // Format password siswa dari tanggal lahir (ddmmyyyy)
        $tanggal = Carbon::parse($validated['tanggal_lahir']);
        $passwordPlain = $tanggal->format('dmY');
        $username = $validated['nisn'];

        // Buat user
        $user = User::create([
            'name' => $validated['nama'],
            'email' => $validated['email'],
            'username' => $username,
            'password' => Hash::make($passwordPlain),
        ]);

        // Cek email/role tabrakan
        if (
            $user->roles()->whereIn('name', ['guru', 'wali_kelas'])->exists() ||
            User::where('email', $validated['email'])
            ->whereHas('roles', fn($q) => $q->whereIn('name', ['guru', 'wali_kelas']))
            ->exists()
        ) {
            $user->delete(); // rollback
            return back()->withErrors('Email sudah digunakan oleh guru atau wali kelas.');
        }

        // Pasang role siswa
        $roleSiswa = Role::where('name', 'siswa')->first();
        $user->roles()->attach($roleSiswa);

        // Simpan data siswa
        Siswa::create([
            'user_id' => $user->id,
            'wali_murid_id' => $wali_murid_id,
            'nama' => $validated['nama'],
            'nis' => $validated['nis'],
            'nisn' => $validated['nisn'],
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'tempat_lahir' => $validated['tempat_lahir'] ?? null,
            'tanggal_lahir' => $validated['tanggal_lahir'],
            'pendidikan_sebelumnya' => $validated['pendidikan_sebelumnya'] ?? null,
            'alamat' => $validated['alamat'] ?? null,
            'no_hp' => $noHpSiswa,
            'status' => $validated['status'],
        ]);

        return redirect()
            ->route('siswa.index')
            ->with('success', "Siswa berhasil ditambahkan. Username: <b>{$username}</b>, password: <b>{$passwordPlain}</b>");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $siswa = Siswa::with('wali')->findOrFail($id);

        $breadcrumbs = [
            ['label' => 'Siswa', 'url' => route('siswa.index')],
            ['label' => $siswa->nama],
        ];

        $title = 'Detail Siswa';

        return view('siswa.show', compact('siswa', 'breadcrumbs', 'title'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $siswa = Siswa::with(['user', 'wali'])->findOrFail($id);
        $wali_siswa = $siswa->wali;
        $wali_list = WaliMurid::all();

        // Buat opsi wali
        $waliOptions = $wali_list->mapWithKeys(function ($wali) {
            $label = collect([$wali->nama_ayah, $wali->nama_ibu, $wali->nama_wali])
                ->filter()
                ->implode(' - ');
            return [$wali->id => $label];
        })->toArray();

        // Tambahkan opsi tambah wali baru
        $waliOptions['baru'] = '+ Tambah Wali Baru';

        $selectedWali = old('wali_murid_id', $siswa->wali_murid_id);

        $breadcrumbs = [
            ['label' => 'Manage Siswa', 'url' => route('siswa.index')],
            ['label' => 'Edit Siswa'],
        ];

        $title = 'Edit Siswa';

        return view('siswa.edit', compact(
            'siswa', 'wali_siswa', 'wali_list', 'waliOptions', 'selectedWali', 'breadcrumbs', 'title'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $siswa = Siswa::with(['user.roles', 'wali'])->findOrFail($id);
        $user = $siswa->user;

        if ($user->roles()->whereIn('name', ['guru', 'wali_kelas'])->exists()) {
            return back()->withErrors('User sudah berperan guru atau wali kelas.');
        }

        // Simpan wali lama sebelum update
        $waliLamaId = $siswa->wali_murid_id;

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nis' => ['required', Rule::unique('siswa', 'nis')->ignore($siswa->id)],
            'nisn' => ['required', Rule::unique('siswa', 'nisn')->ignore($siswa->id)],
            'jenis_kelamin' => ['required', Rule::in(['Laki-laki', 'Perempuan'])],
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'pendidikan_sebelumnya' => 'nullable|string|max:255',
            'alamat' => 'nullable|string|max:500',
            'no_hp' => [
                'nullable',
                'string',
                'max:20',
                'regex:/^(0|62)[0-9]{9,}$/',
            ],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'status' => ['required', Rule::in(['Aktif', 'Lulus', 'Keluar', 'Mutasi'])],
            'wali_murid_id' => [
                'required',
                function ($attribute, $value, $fail) {
                    if ($value !== 'baru' && !is_null($value) && !WaliMurid::where('id', $value)->exists()) {
                        $fail('Wali murid yang dipilih tidak valid.');
                    }
                }
            ],
        ]);

        // Format nomor HP siswa
        if (!empty($validated['no_hp'])) {
            $noHp = preg_replace('/[^0-9]/', '', $validated['no_hp']);
            if (str_starts_with($noHp, '0')) {
                $noHp = '62' . substr($noHp, 1);
            }
            $validated['no_hp'] = $noHp;
        }

        // Update user dan siswa
        $user->update([
            'name' => $validated['nama'],
            'email' => $validated['email'],
            'username' => $validated['nisn'], // tetap pakai NISN sebagai username
        ]);
        $updateData = collect($validated)->except('wali_murid_id')->toArray();

// Update siswa tanpa wali dulu
$siswa->update($updateData);


        // --- Penanganan Wali Murid ---

        $inputWaliId = $request->wali_murid_id;

        if ($inputWaliId === 'baru') {
            // Validasi data wali baru
            $request->validate([
                'nama_ayah'       => 'required|string|max:255',
                'nama_ibu'        => 'required|string|max:255',
                'nama_wali'       => 'nullable|string|max:255',
                'no_hp_wali'      => [
                    'nullable',
                    'string',
                    'max:20',
                    'regex:/^(0|62)[0-9]{9,}$/',
                ],
                'pekerjaan_ayah'  => 'nullable|string|max:255',
                'pekerjaan_ibu'   => 'nullable|string|max:255',
                'pekerjaan_wali'  => 'nullable|string|max:255',
                'alamat_wali'     => 'nullable|string|max:500',
            ]);

            $noHpWali = preg_replace('/[^0-9]/', '', $request->no_hp_wali);
            if ($noHpWali && str_starts_with($noHpWali, '0')) {
                $noHpWali = '62' . substr($noHpWali, 1);
            }

            // Simpan wali baru
            $waliBaru = WaliMurid::create([
                'nama_ayah'       => $request->nama_ayah,
                'nama_ibu'        => $request->nama_ibu,
                'nama_wali'       => $request->nama_wali,
                'no_hp'           => $noHpWali,
                'pekerjaan_ayah'  => $request->pekerjaan_ayah,
                'pekerjaan_ibu'   => $request->pekerjaan_ibu,
                'pekerjaan_wali'  => $request->pekerjaan_wali,
                'alamat'          => $request->alamat_wali,
            ]);

            // Set wali baru ke siswa
            $siswa->wali_murid_id = $waliBaru->id;
            $siswa->save();

            // Hapus wali lama jika tidak dipakai
            if ($waliLamaId && Siswa::where('wali_murid_id', $waliLamaId)->count() === 0) {
                WaliMurid::find($waliLamaId)?->delete();
            }
        } elseif ($inputWaliId) {
            // Jika ganti wali dari dropdown
            if ($inputWaliId != $waliLamaId) {
                $siswa->wali_murid_id = $inputWaliId;
                $siswa->save();

                // Hapus wali lama kalau tidak ada anak lagi
                if ($waliLamaId && Siswa::where('wali_murid_id', $waliLamaId)->count() === 0) {
                    WaliMurid::find($waliLamaId)?->delete();
                }
            }
        } else {
            // Update wali lama (inline)
            if ($siswa->wali) {
                $request->validate([
                    'nama_ayah'       => 'required|string|max:255',
                    'nama_ibu'        => 'required|string|max:255',
                    'nama_wali'       => 'nullable|string|max:255',
                    'no_hp_wali'      => [
                        'nullable',
                        'string',
                        'max:20',
                        'regex:/^(0|62)[0-9]{9,}$/',
                    ],
                    'pekerjaan_ayah'  => 'nullable|string|max:255',
                    'pekerjaan_ibu'   => 'nullable|string|max:255',
                    'pekerjaan_wali'  => 'nullable|string|max:255',
                    'alamat_wali'     => 'nullable|string|max:500',
                ]);

                $noHpWali = preg_replace('/[^0-9]/', '', $request->no_hp_wali);
                if ($noHpWali && str_starts_with($noHpWali, '0')) {
                    $noHpWali = '62' . substr($noHpWali, 1);
                }

                $siswa->wali->update([
                    'nama_ayah'       => $request->nama_ayah,
                    'nama_ibu'        => $request->nama_ibu,
                    'nama_wali'       => $request->nama_wali,
                    'no_hp'           => $noHpWali,
                    'pekerjaan_ayah'  => $request->pekerjaan_ayah,
                    'pekerjaan_ibu'   => $request->pekerjaan_ibu,
                    'pekerjaan_wali'  => $request->pekerjaan_wali,
                    'alamat'          => $request->alamat_wali,
                ]);
            }
        }

        return redirect()
            ->route('siswa.index')
            ->with('success', 'Data siswa berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $siswa = Siswa::with(['user', 'wali', 'kelasSiswa'])->findOrFail($id);

        // Cek apakah siswa masih terhubung di kelas_siswa
        if ($siswa->kelasSiswa()->exists()) {
            return redirect()
                ->route('siswa.index')
                ->with('error', 'Siswa tidak dapat dihapus karena masih terdaftar di kelas.');
        }

        if ($siswa->wali && $siswa->wali->siswa()->count() === 1) {
            $siswa->wali->delete();
        }
        if ($siswa->user) {
            $siswa->user->delete();
        }
        $siswa->delete();

        return redirect()
            ->route('siswa.index')
            ->with('success', 'Siswa berhasil dihapus.');
    }

    public function export()
    {
        $data = Siswa::with(['kelas', 'waliMurid'])->get();

        $headings = [
            'Nama',
            'NIS',
            'NISN',
            'Jenis Kelamin',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Pendidikan Sebelumnya',
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
        $formatted = $data->map(function ($siswa) {
            $wali = $siswa->waliMurid;
            return [
                $siswa->nama,
                $siswa->nis,
                $siswa->nisn,
                $siswa->jenis_kelamin,
                $siswa->tempat_lahir,
                $siswa->tanggal_lahir,
                $siswa->pendidikan_sebelumnya,
                $siswa->alamat,
                $siswa->no_hp,
                $siswa->email,
                optional($wali)->nama_ayah,
                optional($wali)->pekerjaan_ayah,
                optional($wali)->nama_ibu,
                optional($wali)->pekerjaan_ibu,
                optional($wali)->nama_wali ?? '-',
                optional($wali)->pekerjaan_wali ?? '-',
                optional($wali)->no_hp_wali,
                optional($wali)->alamat_wali,
            ];
        })->toArray();

        return Excel::download(new ReusableExport($headings, $formatted), 'data_siswa.xlsx');
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
}

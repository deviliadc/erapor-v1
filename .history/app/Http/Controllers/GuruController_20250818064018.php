<?php

namespace App\Http\Controllers;

use App\Exports\ReusableExport;
use App\Exports\ReusableTemplateExport;
use App\Imports\ReusableImport;
use App\Models\Guru;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;

class GuruController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $sortBy = $request->input('sortBy', 'id');
        $sortDirection = $request->input('sortDirection', 'asc');

        $columnMap = [
            'id' => 'guru.id',
            'name' => 'guru.nama',
            'nuptk' => 'guru.nuptk',
            'nip' => 'guru.nip',
            'email' => 'users.email', // jika sorting berdasarkan relasi
            'no_hp' => 'guru.no_hp',
            // 'alamat' => 'guru.alamat',
            // 'jenis_kelamin' => 'guru.jenis_kelamin',
            'status' => 'guru.status',
        ];


        $query = Guru::query()
            ->leftJoin('users', 'users.id', '=', 'guru.user_id')
            ->select(
                'guru.*',
                'users.email'
            );

        // Filter pencarian
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('nama', 'like', "%{$search}%")
                ->orWhere('nip', 'like', "%{$search}%");
        }

        // Sorting aman berdasarkan kolom yang dimapping
        if (isset($columnMap[$sortBy])) {
            $query->orderBy($columnMap[$sortBy], $sortDirection);
        } else {
            $query->orderBy('guru.id', 'asc'); // default sorting
        }

        $totalCount = $query->count();
        $paginator = $query->paginate($perPage)->withQueryString();

        $guru = $paginator->through(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->nama,
                'nuptk' => $item->nuptk ?? '-',
                'nip' => $item->nip ?? '-',
                'email' => $item->user?->email ?? '-',
                'no_hp' => $item->no_hp ?? '-',
                // 'alamat' => $item->alamat ?? '-',
                // 'jenis_kelamin' => $item->jenis_kelamin,
                'status' => $item->status,
            ];
        });

        $breadcrumbs = [['label' => 'Manage Guru']];
        $title = 'Manage Guru';

        return view('guru.index', compact('guru', 'totalCount', 'breadcrumbs', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // $breadcrumbs = [
        //     ['label' => 'Manage Guru', 'url' => route('guru.index')],
        //     ['label' => 'Create Guru'],
        // ];

        // $title = 'Create Guru';

        // return view('guru.create', compact('breadcrumbs', 'title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:users,email',
            'nama' => 'required|string|max:255',
            'nip' => 'nullable|string|max:20|unique:guru,nip',
            'no_hp' => [
                'nullable',
                'string',
                'max:20',
                'regex:/^(0|62)[0-9]{9,}$/'
            ],
            // 'alamat' => 'nullable|string|max:500',
            // 'jenis_kelamin' => ['required', Rule::in(['Laki-laki', 'Perempuan'])],
            'status' => ['required', Rule::in(['Aktif', 'Pensiun', 'Mutasi', 'Resign'])],
        ]);

        // Normalisasi no_hp
        if (!empty($validated['no_hp'])) {
            $noHp = preg_replace('/[^0-9]/', '', $validated['no_hp']);
            if (str_starts_with($noHp, '0')) {
                $noHp = '62' . substr($noHp, 1);
            }
            $validated['no_hp'] = $noHp;
        }

        try {
            $namaDepan = strtolower(str_replace(' ', '', explode(' ', $validated['nama'])[0]));
            $kodeUnik = $validated['nip'] ? substr($validated['nip'], 0, 4) : str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
            $username = $namaDepan . $kodeUnik;

            // Buat username unik
            $originalUsername = $username;
            $i = 1;
            while (User::where('username', $username)->exists()) {
                $username = $originalUsername . $i++;
            }

            $password = 'password123';

            $user = User::create([
                'username' => $username,
                'email' => $validated['email'],
                'password' => Hash::make($password),
            ]);

            if ($user->roles()->where('name', 'siswa')->exists()) {
                $user->delete();
                return back()->withErrors('User ini sudah memiliki role siswa dan tidak bisa dijadikan guru.');
            }

            $guruRole = Role::where('name', 'guru')->first();
            $user->roles()->attach($guruRole);

            Guru::create([
                'user_id' => $user->id,
                'nama' => $validated['nama'],
                'nip' => $validated['nip'],
                'no_hp' => $validated['no_hp'],
                // 'alamat' => $validated['alamat'],
                // 'jenis_kelamin' => $validated['jenis_kelamin'],
                'status' => $validated['status'],
            ]);

            return redirect()->to(role_route('guru.index'))
                ->with('success', "Data guru berhasil ditambahkan. Username: <strong>{$username}</strong> | Password: <strong>{$password}</strong>");
        } catch (\Exception $e) {
            report($e); // log error
            return back()->withErrors('Gagal menyimpan data guru: ' . $e->getMessage());
        }
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
        $guru = Guru::findOrFail($id);

        // $breadcrumbs = [
        //     ['label' => 'Manage Guru', 'url' => route('guru.index')],
        //     ['label' => 'Edit Guru'],
        // ];

        // $title = 'Edit Guru';

        return view('guru.edit', compact('guru',));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $guru = Guru::with('user.roles')->findOrFail($id);
        $user = $guru->user;

        // Cek apakah user valid
        if (!$user) {
            return back()->withErrors('Data user tidak ditemukan untuk guru ini.');
        }

        if ($user->hasRole('siswa')) {
            return back()->withErrors('User ini sudah memiliki role siswa dan tidak bisa dijadikan guru.');
        }

        // Validasi Input
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nip' => ['nullable', 'string', 'max:20', Rule::unique('guru', 'nip')->ignore($guru->id)],
            'no_hp' => [
                'nullable',
                'string',
                'max:20',
                'regex:/^(0|62)[0-9]{9,}$/'
            ],
            // 'alamat' => 'nullable|string|max:500',
            // 'jenis_kelamin' => ['required', Rule::in(['Laki-laki', 'Perempuan'])],
            'status' => ['required', Rule::in(['Aktif', 'Pensiun', 'Mutasi', 'Resign'])],
        ]);

        // Normalisasi dan simpan no_hp
        if (!empty($validated['no_hp'])) {
            $noHp = preg_replace('/[^0-9]/', '', $validated['no_hp']);
            if (str_starts_with($noHp, '0')) {
                $noHp = '62' . substr($noHp, 1);
            }
            $validated['no_hp'] = $noHp;
        }

        // Update data guru
        $guru->update($validated);
        $user->update(['name' => $validated['nama']]);

        return redirect()->to(role_route('guru.index'))->with('success', 'Data guru berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $guru = Guru::with('user')->findOrFail($id);
        $user = $guru->user;

        $guru->delete();

        // Optional: ikut hapus user jika tidak digunakan di tempat lain
        if ($user && $user->roles->count() === 1 && $user->hasRole('guru')) {
            $user->delete();
        }

        return redirect()->to(role_route('guru.index'))->with('success', 'Data guru berhasil dihapus.');
    }

    public function export(Request $request)
    {
        $type = $request->input('type', 'excel');
        $tanggal = now()->format('Ymd_His');
        $filename = $request->input('filename', "data_guru_{$tanggal}");

        $data = \App\Models\Guru::with('user')->get();

        $headings = [
            'Nama',
            'NIP',
            'NUPTK',
            'Email',
            'No HP',
            'Status'
        ];
        $enumInfo = [
            '',
            '',
            '',
            '',
            '',
            'Aktif/Pensiun/Mutasi/Resign'
        ];

        $formatted = $data->map(function ($guru) {
            return [
                $guru->nama,
                $guru->nip ?? '-',
                $guru->nuptk ?? '-',
                $guru->user?->email ?? '-',
                $guru->no_hp ?? '-',
                $guru->status ?? '-',
            ];
        })->toArray();

        return Excel::download(
            new ReusableExport($headings, $enumInfo, $formatted),
            "{$filename}.xlsx"
        );
    }

    public function template()
    {
        $headers = [
            'Nama',
            'NIP',
            'NUPTK',
            'Email',
            'No HP',
            'Status'
        ];
        $enumInfo = [
            '',
            '',
            '',
            '',
            '',
            'Aktif/Pensiun/Mutasi/Resign'
        ];

        return Excel::download(
            new ReusableTemplateExport([$headers, $enumInfo]),
            'template_guru.xlsx'
        );
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        if (!$request->hasFile('file') || !$request->file('file')->isValid()) {
            return back()->with('error', 'File tidak ditemukan atau tidak valid.');
        }

        $success = 0;
        $failed = 0;
        $errors = [];
        $headerError = false;

        $requiredHeaders = [
            'Nama',
            'NIP',
            'NUPTK',
            'Email',
            'No HP',
            'Status'
        ];

        Excel::import(
            new ReusableImport(function ($row, $index) use (&$success, &$failed, &$errors, &$headerError, $requiredHeaders) {
                $dataArr = $row->toArray();
                if ($index === 0) {
                    if ($dataArr !== $requiredHeaders) {
                        throw new \Exception('Header file tidak sesuai.');
                    }
                    return;
                }
                if ($index === 1) return;
                if ($headerError) return;

                $data = [];
                foreach ($requiredHeaders as $i => $key) {
                    $data[$key] = $dataArr[$i] ?? null;
                }

                // Validasi duplikat NIP/email
                $duplikat = [];
                if (!empty($data['NIP']) && Guru::where('nip', $data['NIP'])->exists()) {
                    $duplikat[] = 'NIP';
                }
                if (!empty($data['Email']) && User::where('email', $data['Email'])->exists()) {
                    $duplikat[] = 'Email';
                }
                if (count($duplikat) > 0) {
                    $failed++;
                    $errors[] = ($data['Nama'] ?? '(Tanpa Nama)') . ' duplikat: ' . implode(', ', $duplikat);
                    return;
                }

                // Validasi dan mapping status
                $status = strtolower(trim($data['Status'] ?? ''));
                $statusMap = [
                    'aktif' => 'Aktif',
                    'pensiun' => 'Pensiun',
                    'mutasi' => 'Mutasi',
                    'resign' => 'Resign'
                ];
                $data['Status'] = $statusMap[$status] ?? 'Aktif';

                // Validasi data
                $validator = Validator::make([
                    'nama' => $data['Nama'],
                    'nip' => $data['NIP'],
                    'nuptk' => $data['NUPTK'],
                    'email' => $data['Email'],
                    'no_hp' => $data['No HP'],
                    'status' => $data['Status'],
                ], [
                    'nama' => 'required|string|max:255',
                    'nip' => 'nullable|string|max:20',
                    'nuptk' => 'nullable|string|max:20',
                    'email' => 'required|email',
                    'no_hp' => 'nullable|string|max:20',
                    'status' => 'required|in:Aktif,Pensiun,Mutasi,Resign',
                ]);

                if ($validator->fails()) {
                    $failed++;
                    $errors[] = ($data['Nama'] ?? '(Tanpa Nama)') . ' error: ' . implode(', ', $validator->errors()->all());
                    return;
                }

                // Buat user guru
                $username = strtolower(str_replace(' ', '', explode(' ', $data['Nama'])[0])) . ($data['NIP'] ? substr($data['NIP'], 0, 4) : rand(1000, 9999));
                $password = 'password123';
                $user = User::create([
                    'username' => $username,
                    'email' => $data['Email'],
                    'password' => Hash::make($password),
                ]);
                $roleGuru = Role::where('name', 'guru')->first();
                $user->roles()->attach($roleGuru);

                // Simpan guru
                Guru::create([
                    'user_id' => $user->id,
                    'nama' => $data['Nama'],
                    'nip' => $data['NIP'],
                    'nuptk' => $data['NUPTK'],
                    'no_hp' => $data['No HP'],
                    'status' => $data['Status'],
                ]);
                $success++;
            }),
            $request->file('file')
        );

        $msg = "Import selesai. Berhasil: {$success}, Gagal: {$failed}";
        if ($failed > 0) {
            $msg .= ". Data gagal: " . implode('; ', $errors);
        }

        return redirect()->to(role_route('guru.index'))->with('success', $msg);
    }
}

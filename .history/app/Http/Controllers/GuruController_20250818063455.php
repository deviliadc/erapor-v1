<?php

namespace App\Http\Controllers;

use App\Exports\ReusableExport;
use App\Models\Guru;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

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

    public function export()
    {
        return Excel::download(new ReusableExport(), 'data_guru.xlsx');
    }

}

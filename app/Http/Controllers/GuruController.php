<?php

namespace App\Http\Controllers;

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

        $query = Guru::with('user');

        // Search filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('nama', 'like', "%{$search}%")
                ->orWhere('nip', 'like', "%{$search}%");
        }

        $totalCount = $query->count();
        $users = $query->paginate($perPage)->withQueryString();

        // Format data untuk tampilan
        $data = $users->through(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->nama,
                'nip' => $user->nip,
                'email' => $user->user?->email ?? '-',
                'no_hp' => $user->no_hp,
                'alamat' => $user->alamat,
                'jenis_kelamin' => $user->jenis_kelamin,
            ];
        });

        $breadcrumbs = [
            ['label' => 'Manage Guru', 'url' => route('admin.guru.index')],
        ];

        $title = 'Manage Guru';

        return view('admin.guru.index', compact('users', 'totalCount', 'breadcrumbs', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $breadcrumbs = [
            ['label' => 'Manage Guru', 'url' => route('admin.guru.index')],
            ['label' => 'Create Guru'],
        ];

        $title = 'Create Guru';

        return view('admin.guru.create', compact('breadcrumbs', 'title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:users,email',
            'nama' => 'required|string|max:255',
            'nip' => 'nullable|string|unique:guru,nip|max:20',
            'no_hp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string|max:500',
            'jenis_kelamin' => ['required', Rule::in(['Laki-laki', 'Perempuan'])],
        ]);

        try {
            // Ambil nama depan
            $namaDepan = strtolower(str_replace(' ', '', explode(' ', $validated['nama'])[0]));

            // 4 digit awal dari NIP, atau 4 digit random
            $kodeUnik = $validated['nip'] ? substr($validated['nip'], 0, 4) : str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);

            // Gabungkan jadi username
            $username = $namaDepan . $kodeUnik;

            // Pastikan username unik
            $i = 1;
            $originalUsername = $username;
            while (User::where('username', $username)->exists()) {
                $username = $originalUsername . $i;
                $i++;
            }

            $user = User::create([
                'username' => $username,
                'email' => $validated['email'],
                'password' => Hash::make('password123'), // Default password
            ]);

            // Ambil role guru dan attach ke user
            $guruRole = Role::where('name', 'guru')->first();

            // Cek apakah user sudah punya role siswa
            if ($user->roles()->where('name', 'siswa')->exists()) {
                // Optional: hapus user jika baru dibuat
                $user->delete();
                return back()->withErrors('User ini sudah memiliki role siswa dan tidak bisa dijadikan guru.');
            }

            $user->roles()->attach($guruRole);

            Guru::create([
                'user_id' => $user->id,
                'nama' => $validated['nama'],
                'nip' => $validated['nip'],
                'no_hp' => $validated['no_hp'],
                'alamat' => $validated['alamat'],
                'jenis_kelamin' => $validated['jenis_kelamin'],
            ]);

            return redirect()->route('admin.guru.index')
                ->with('success', "Data guru berhasil ditambahkan. Username: <strong>{$username}</strong> | Password: <strong>password123</strong>");
        } catch (\Exception $e) {
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

        $breadcrumbs = [
            ['label' => 'Manage Guru', 'url' => route('admin.guru.index')],
            ['label' => 'Edit Guru'],
        ];

        $title = 'Edit Guru';

        return view('admin.guru.edit', compact('guru', 'breadcrumbs', 'title'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $guru = Guru::with('user.roles')->findOrFail($id); // Pastikan load user & roles
        $user = $guru->user;

        // Cek apakah user valid
        if (!$user) {
            return back()->withErrors('Data user tidak ditemukan untuk guru ini.');
        }

        // Cek apakah user memiliki role siswa
        if ($user && $user->hasRole('siswa')) {
            return back()->withErrors('User ini sudah memiliki role siswa dan tidak bisa dijadikan guru.');
        }

        // Validasi input
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nip' => ['nullable', 'string', 'max:20', Rule::unique('guru', 'nip')->ignore($guru->id)],
            'no_hp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string|max:500',
            'jenis_kelamin' => ['required', Rule::in(['Laki-laki', 'Perempuan'])],
        ]);

        // Update data guru
        $guru->update($validated);

        // Optional: update juga user name
        $user->update([
            'name' => $validated['nama'],
        ]);

        return redirect()->route('admin.guru.index')
            ->with('success', 'Data guru berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $guru = Guru::findOrFail($id);
        $guru->delete();

        return redirect()->route('admin.guru.index')
            ->with('success', 'Data guru berhasil dihapus.');
    }
}

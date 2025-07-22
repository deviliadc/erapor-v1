<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class SiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);

        $query = Siswa::with('user');

        // Search filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('nama', 'like', "%{$search}%")
                ->orWhere('nis', 'like', "%{$search}%")
                ->orWhere('nisn', 'like', "%{$search}%");
        }

        $totalCount = $query->count();
        $paginator = $query->paginate($perPage)->withQueryString();

        // Format data untuk tampilan
        $siswa = $paginator->through(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->nama,
                'nis' => $user->nis,
                'nisn' => $user->nisn,
                'jenis_kelamin' => $user->jenis_kelamin,
                'tempat_lahir' => $user->tempat_lahir,
                'tanggal_lahir' => $user->tanggal_lahir,
                'pendidikan_sebelumnya' => $user->pendidikan_sebelumnya,
                'alamat' => $user->alamat,
                'no_hp' => $user->no_hp,
                'email' => $user->user?->email ?? '-',
            ];
        });

        $breadcrumbs = [
            ['label' => 'Manage Siswa', 'url' => route('admin.siswa.index')]
        ];

        $title = 'Manage User';

        return view('admin.siswa.index',  compact('siswa', 'totalCount', 'breadcrumbs', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $breadcrumbs = [
            ['label' => 'Manage Siswa', 'url' => route('admin.siswa.index')],
            ['label' => 'Create Siswa'],
        ];

        $title = 'Create Siswa';

        return view('admin.siswa.create', compact('breadcrumbs', 'title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nis' => 'required|unique:siswa',
            'nisn' => 'required|unique:siswa',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tempat_lahir' => 'nullable|string',
            'tanggal_lahir' => 'required|date',
            'pendidikan_sebelumnya' => 'nullable|string',
            'alamat' => 'nullable|string',
            'no_hp' => 'nullable|string',
            'email' => 'required|email|unique:users',
        ]);

        // Format password dari tanggal lahir (ddmmyyyy)
        $tanggalLahir = \Carbon\Carbon::parse($validated['tanggal_lahir']);
        $password = $tanggalLahir->format('dmY'); // contoh: 12062010

        // Gunakan NISN sebagai username
        $username = $validated['nisn'];

        // Create User account
        $user = User::create([
            'name' => $validated['nama'],
            'email' => $validated['email'],
            'username' => $username,
            'password' => Hash::make($password),
        ]);

        // Ambil role siswa dan attach ke user
        $siswaRole = Role::where('name', 'siswa')->first();

        if ($user->roles()->whereIn('name', ['guru', 'wali_kelas'])->exists()) {
            $user->delete(); // jika user baru dibuat
            return back()->withErrors('User ini sudah memiliki role guru atau wali kelas dan tidak bisa dijadikan siswa.');
        }

        $user->roles()->attach($siswaRole);

        // Create Siswa profile
        Siswa::create([
            'user_id' => $user->id,
            'nama' => $validated['nama'],
            'nis' => $validated['nis'],
            'nisn' => $validated['nisn'],
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'tempat_lahir' => $validated['tempat_lahir'],
            'tanggal_lahir' => $validated['tanggal_lahir'],
            'pendidikan_sebelumnya' => $validated['pendidikan_sebelumnya'],
            'alamat' => $validated['alamat'],
            'no_hp' => $validated['no_hp'],
        ]);

        return redirect()->route('admin.siswa.index')->with('success', 'Siswa berhasil ditambahkan.');
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
        $siswa = Siswa::with('user')->findOrFail($id);

        $breadcrumbs = [
            ['label' => 'Manage Siswa', 'url' => route('admin.siswa.index')],
            ['label' => 'Edit Siswa'],
        ];

        $title = 'Edit Siswa';

        return view('admin.siswa.edit', compact('siswa', 'breadcrumbs', 'title'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $siswa = Siswa::findOrFail($id);
        $user = $siswa->user;

        // Tambahan validasi role
        if ($user->roles()->whereIn('name', ['guru', 'wali_kelas'])->exists()) {
            return back()->withErrors('User ini sudah memiliki role guru atau wali kelas dan tidak bisa dijadikan siswa.');
        }

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nis' => ['required', Rule::unique('siswas')->ignore($siswa->id)],
            'nisn' => ['required', Rule::unique('siswas')->ignore($siswa->id)],
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tempat_lahir' => 'nullable|string',
            'tanggal_lahir' => 'nullable|date',
            'pendidikan_sebelumnya' => 'nullable|string',
            'alamat' => 'nullable|string',
            'no_hp' => 'nullable|string',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
        ]);

        // Update user
        $user->update([
            'name' => $validated['nama'],
            'email' => $validated['email'],
        ]);

        // Update siswa
        $siswa->update([
            'nama' => $validated['nama'],
            'nis' => $validated['nis'],
            'nisn' => $validated['nisn'],
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'tempat_lahir' => $validated['tempat_lahir'],
            'tanggal_lahir' => $validated['tanggal_lahir'],
            'pendidikan_sebelumnya' => $validated['pendidikan_sebelumnya'],
            'alamat' => $validated['alamat'],
            'no_hp' => $validated['no_hp'],
        ]);

        return redirect()->route('admin.siswa.index')->with('success', 'Data siswa berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $siswa = Siswa::findOrFail($id);
        $siswa->user()->delete(); // hapus user terkait
        $siswa->delete();         // hapus siswa

        return redirect()->route('admin.siswa.index')->with('success', 'Siswa berhasil dihapus.');
    }
}

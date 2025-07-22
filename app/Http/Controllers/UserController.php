<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Role;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);

        $query = User::with(['siswa', 'guru', 'roles']);

        // Search
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('username', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhereHas('guru', function ($q2) use ($search) {
                        $q2->where('nama', 'like', "%{$search}%");
                    })
                    ->orWhereHas('siswa', function ($q3) use ($search) {
                        $q3->where('nama', 'like', "%{$search}%");
                    });
            });
        }

        // Role Filter
        if ($request->filled('role_filter')) {
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('roles.id', $request->role_filter);
            });
        }

        // Total data tanpa pagination
        $totalCount = $query->count();

        //Pagination + QueryString (agar filter/search tetap)
        $users = $query->paginate($perPage)->withQueryString();

        // Format data untuk tampilan
        $data = $users->through(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->guru?->nama ?? $user->siswa?->nama ?? '-',
                'username' => $user->username,
                'email' => $user->email,
                'roles' => $user->roles->pluck('name')->join(', '),
            ];
        });

        // Semua role untuk filter dropdown
        $roles = Role::all();

        $breadcrumbs = [['label' => 'Manage User']];

        $title = 'Manage User';

        return view('admin.user.index', compact('data', 'roles', 'users', 'totalCount', 'breadcrumbs', 'title'));
    }

    public function profile()
    {
        return view('profile.profile'); // ganti 'profile' sesuai dengan nama file Blade kamu
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();

        $breadcrumbs = [
            ['label' => 'Manage User', 'url' => route('admin.user.index')],
            ['label' => 'Create User'],
        ];

        $title = 'Create User';

        return view('admin.user.index', compact('roles', 'breadcrumbs', 'title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|unique:users,username',
            'email' => 'nullable|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'profile_photo' => 'nullable|image|max:2048',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id',
        ]);

        // Cek role apakah termasuk guru atau siswa
        $roleGuru = Role::where('name', 'guru')->first();
        $roleSiswa = Role::where('name', 'siswa')->first();

        $hasGuruRole = in_array($roleGuru->id, $request->roles ?? []);
        $hasSiswaRole = in_array($roleSiswa->id, $request->roles ?? []);

        try {
            if ($request->hasFile('profile_photo')) {
                $path = $request->file('profile_photo')->store('profile_photos', 'public');
                $validated['profile_photo'] = $path;
            }

            $validated['password'] = Hash::make($validated['password']);
            $user = User::create($validated);

            if ($hasGuruRole && Siswa::where('user_id', $user->id)->exists()) {
                $user->delete();
                return back()->withErrors(['roles' => 'User ini sudah terdaftar sebagai Siswa, tidak bisa menjadi Guru juga.'])->withInput();
            }
            if ($hasSiswaRole && Guru::where('user_id', $user->id)->exists()) {
                $user->delete();
                return back()->withErrors(['roles' => 'User ini sudah terdaftar sebagai Guru, tidak bisa menjadi Siswa juga.'])->withInput();
            }

            $user->roles()->sync($request->roles);

            return redirect()->route('admin.user.index')->with('success', 'Data berhasil disimpan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data.');
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
        $user = User::with('roles')->findOrFail($id);
        $roles = Role::all();

        $breadcrumbs = [
            ['label' => 'Manage User', 'url' => route('admin.user.index')],
            ['label' => 'Edit User'],
        ];

        $title = 'Edit User';

        return view('admin.user.edit', compact('user', 'roles', 'breadcrumbs', 'title'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'username' => 'required|unique:users,username,' . $user->id,
            'email' => 'required|email|unique:users,email,' . $user->id,
            'roles' => 'array|required'
        ]);

        $roleGuru = Role::where('name', 'guru')->first();
        $roleSiswa = Role::where('name', 'siswa')->first();

        $hasGuruRole = in_array($roleGuru->id, $request->roles ?? []);
        $hasSiswaRole = in_array($roleSiswa->id, $request->roles ?? []);

        // Validasi agar user_id tidak muncul di kedua tabel sekaligus
        if ($hasGuruRole && Siswa::where('user_id', $user->id)->exists()) {
            return back()->withErrors(['roles' => 'User ini sudah terdaftar sebagai Siswa, tidak bisa menjadi Guru juga.'])->withInput();
        }

        if ($hasSiswaRole && Guru::where('user_id', $user->id)->exists()) {
            return back()->withErrors(['roles' => 'User ini sudah terdaftar sebagai Guru, tidak bisa menjadi Siswa juga.'])->withInput();
        }

        try {
            $user->update([
                'username' => $request->username,
                'email' => $request->email,
            ]);

            if ($request->filled('password')) {
                $user->password = bcrypt($request->password);
                $user->save();
            }

            $user->roles()->sync($request->roles);

            return redirect()->route('admin.user.index')->with('success', 'Data berhasil disimpan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::with('roles')->findOrFail($id);

        // Cegah hapus diri sendiri
        if (Auth::check() && Auth::user()->id == $user->id) {
            return redirect()->route('admin.user.index')
                ->with('error', 'Anda tidak bisa menghapus akun Anda sendiri.');
        }

        // Cek apakah user yang akan dihapus punya role 'admin'
        $isAdmin = $user->roles->contains(function ($role) {
            return $role->name === 'admin'; // atau pakai id === 1 kalau ID role admin = 1
        });

        if ($isAdmin) {
            // Hitung jumlah user yang punya role admin
            $adminCount = User::whereHas('roles', function ($query) {
                $query->where('name', 'admin'); // atau where('id', 1);
            })->count();

            if ($adminCount <= 1) {
                return redirect()->route('admin.user.index')
                    ->with('error', 'Tidak bisa menghapus satu-satunya admin.');
            }
        }

        $user->delete();

        return redirect()->route('admin.user.index')
            ->with('success', 'User berhasil dihapus.');
    }

    public function template(string $id)
    {
        return response()->download(public_path('templates/user_template.xlsx'));
    }

    public function export(string $id)
    {
        // $format = $request->query('format', 'excel');

        // if ($format === 'pdf') {
        //     return (new UsersExport)->download('users.pdf', \Maatwebsite\Excel\Excel::DOMPDF);
        // }

        // return Excel::download(new UsersExport, 'users.xlsx');
    }

    public function import(string $id)
    {
        // $request->validate([
        //     'file' => 'required|mimes:xlsx,xls'
        // ]);

        // Excel::import(new UsersImport, $request->file('file'));

        // return redirect()->route('admin.user.index')->with('success', 'Data user berhasil diimpor.');
    }
}

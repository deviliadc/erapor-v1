<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Role;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Exports\GenericExport;
use App\Exports\ReusableExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

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

        $totalCount = $query->count();

        $paginator = $query->paginate($perPage)->withQueryString();

        $users = $paginator->through(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->guru?->nama ?? $item->siswa?->nama ?? '-',
                'username' => $item->username,
                'email' => $item->email,
                'roles' => $item->roles->pluck('name')->join(', '),
                'role_ids' => $item->roles->pluck('id')->all(),
            ];
        });

        // Roles untuk filter dan form di modal
        $roles = Role::all()->map(function ($role) {
            $role->label = Str::of($role->name)->replace('_', ' ')->title();
            return $role;
        });
        // $roles = Role::pluck('name', 'name')->toArray();

        $breadcrumbs = [['label' => 'Manage User']];
        $title = 'Manage User';

        return view('user.index', compact('users', 'roles', 'totalCount', 'breadcrumbs', 'title', 'paginator'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all()->map(function ($role) {
            $role->label = Str::of($role->name)->replace('_', ' ')->title();
            return $role;
        });

        return view('user.create', compact('roles'));
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
            // if ($request->hasFile('profile_photo')) {
            //     $path = $request->file('profile_photo')->store('profile_photos', 'public');
            //     $validated['profile_photo'] = $path;
            // }

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

            // return redirect()->route('user.index')->with('success', 'Data berhasil disimpan.');
            return redirect()->to(role_route('user.index'))->with('success', 'Data berhasil disimpan.');
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
        $roles = Role::all()->map(function ($role) {
            $role->label = Str::of($role->name)->replace('_', ' ')->title();
            return $role;
        });

        return view('user.edit', compact('user', 'roles',));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'username' => 'required|unique:users,username,' . $user->id,
            'email' => 'nullable|email|unique:users,email,' . $user->id,
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

            return redirect()->to(role_route('user.index'))->with('success', 'Data berhasil disimpan.');
            // return redirect()->route('user.index')->with('success', 'Data berhasil disimpan.');
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
            return redirect()->to(role_route('user.index'))
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
                return redirect()->to(role_route('user.index'))
                    ->with('error', 'Tidak bisa menghapus satu-satunya ');
            }
        }

        // Jika user adalah siswa, hapus wali jika tidak dipakai siswa lain
        if ($user->siswa) {
            $wali = $user->siswa->wali;
            if ($wali && $wali->siswa()->count() === 1) {
                $wali->delete();
            }
            $user->siswa->delete();
        }

        $user->delete();

        return redirect()->to(role_route('user.index'))->with('success', 'Data berhasil dihapus.');
        // return redirect()->route('user.index')->with('success', 'User berhasil dihapus.');
    }

    // public function template(string $id)
    // {
    //     return response()->download(public_path('templates/user_template.xlsx'));
    // }

    public function export(Request $request)
    {
        $type = $request->input('type', 'excel');
        $tanggal = now()->format('Ymd_His');
        $filename = $request->input('filename', "data_user_{$tanggal}");
    
        $query = User::with(['siswa', 'guru', 'roles']);
    
        // Filter pencarian
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('username', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhereHas('guru', fn($q2) => $q2->where('nama', 'like', "%{$search}%"))
                    ->orWhereHas('siswa', fn($q3) => $q3->where('nama', 'like', "%{$search}%"));
            });
        }
    
        // Filter role
        if ($request->filled('role_filter')) {
            $query->whereHas('roles', fn($q) => $q->where('roles.id', $request->role_filter));
        }
    
        $data = $query->get()->map(function ($user) {
            return [
                $user->guru?->nama ?? $user->siswa?->nama ?? '-',
                $user->username,
                $user->email,
                $user->roles->pluck('name')->join(', '),
            ];
        })->toArray();
    
        $headings = ['Nama', 'Username', 'Email', 'Roles'];
        $enumInfo = [
            '',
            '',
            '',
            'admin/guru/siswa/wali_kelas'
        ];
    
        // Jika data kosong, beri minimal 1 baris kosong agar file tetap terbuat
        if (empty($data)) {
            $data[] = ['', '', '', ''];
        }
    
        // Export Excel
        return Excel::download(
            new \App\Exports\ReusableExport($headings, $enumInfo, $data),
            $filename . '.xlsx'
        );
    }

    public function import(string $id)
    {
        // $request->validate([
        //     'file' => 'required|mimes:xlsx,xls'
        // ]);

        // Excel::import(new UsersImport, $request->file('file'));

        // return redirect()->route('user.index')->with('success', 'Data user berhasil diimpor.');
    }
}

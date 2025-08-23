<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

function homeRouteForUser(): string
{
    $user = Auth::user();
    $role = strtolower($user?->role ?? '');

    return match ($role) {
        'admin' => route('admin.dashboard'),
        'guru' => route('guru.dashboard'),
        'siswa' => route('dashboard.siswa'),
        // 'wali_kelas' => route('dashboard.wali-kelas'),
        'kepala_sekolah' => route('kepala-sekolah.dashboard'),
        default => route('dashboard'), // fallback aman
    };
}

function role_route(string $name, array $params = []): string
{
    $user = Auth::user();
    $role = strtolower($user?->role ?? '');
    $routeName = $role . '.' . $name;

    if (Route::has($routeName)) {
        return route($routeName, $params);
    }

    // Cek tanpa prefix role
    if (Route::has($name)) {
        return route($name, $params);
    }

    return route('dashboard');
}
// function role_route(string $name, array $params = []): string
// {
//     $user = Auth::user();

//     // Ambil role name dari relasi atau atribut langsung
//     $role = strtolower(
//         $user?->role
//         ?? $user?->roles?->first()?->name
//         ?? ''
//     );

//     $routeName = $role . '.' . $name;

//     if (Route::has($routeName)) {
//         return route($routeName, $params);
//     }

//     if (Route::has($name)) {
//         return route($name, $params);
//     }

//     return route('dashboard');
// }
function nextTahunAjaran($tahun)
{
    // Format: '2024/2025'
    if (preg_match('/^(\d{4})\/(\d{4})$/', $tahun, $m)) {
        $awal = (int)$m[1] + 1;
        $akhir = (int)$m[2] + 1;
        return "{$awal}/{$akhir}";
    }
    return $tahun;
}

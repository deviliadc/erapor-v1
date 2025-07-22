<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

function homeRouteForUser(): string
{
    $user = Auth::user();
    $role = strtolower($user?->role ?? '');

    return match ($role) {
        'admin' => route('dashboard.admin'),
        'guru' => route('dashboard.guru'),
        'siswa' => route('dashboard.siswa'),
        'wali_kelas' => route('dashboard.wali-kelas'),
        'kepala_sekolah' => route('dashboard.kepala-sekolah'),
        default => route('dashboard'), // fallback aman
    };
}

// if (!function_exists('homeRouteForUser')) {
//     function homeRouteForUser(): string
//     {
//         $user = Auth::user();
//         if (!$user) return '/';

//         $role = strtolower($user->role ?? '');

//         return match ($role) {
//             'admin' => route('admin.dashboard'),
//             'guru' => route('guru.dashboard'),
//             'siswa' => route('siswa.dashboard'),
//             'wali_kelas' => route('wali-kelas.dashboard'),
//             'kepala_sekolah' => route('kepsek.dashboard'),
//             default => '/',
//         };
//     }
// }

// function role_route($name, $params = [])
// {
//     $user = Auth::user();

//     // Gunakan accessor 'role' yang sudah dibuat
//     $role = strtolower($user?->role ?? '');

//     $routeName = $role . '.' . $name;

//     if (Route::has($routeName)) {
//         return route($routeName, $params);
//     }

//     return url('/');
// }

// function role_route($name)
// {
//     $user = Auth::user();
//     $role = strtolower($user?->role ?? '');
//     $routeName = $role . '.' . $name;

//     return Route::has($routeName) ? $routeName : '/';
// }


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

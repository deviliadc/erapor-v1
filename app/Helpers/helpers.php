<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

if (!function_exists('homeRouteForUser')) {
    function homeRouteForUser()
    {
        $user = Auth::user();

        if (!$user) {
            return url('/'); // fallback untuk guest
        }

        return match ($user->role) {
            'admin' => route('admin.dashboard'),
            'kepala_sekolah' => route('kepsek.dashboard'),
            'guru' => route('guru.dashboard'),
            'siswa' => route('siswa.dashboard'),
            'wali-kelas' => route('wali-kelas.dashboard'),
            default => url('/'),
        };
    }
}

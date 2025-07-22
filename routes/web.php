<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    HomeController,
    ProfileController,
    UserController,
    GuruController,
    SiswaController,
    KelasController,
    TahunSemesterController,
    AbsensiController,
    AdminController,
    NilaiController,
    RaporController,
    MapelController,
    BabController,
    DashboardController,
    EkstraController,
    P5Controller
};
use App\Http\Middleware\CheckRole;
use Illuminate\Support\Facades\Auth;

// Halaman Awal
Route::get('/', function () {
    return view('welcome');
});

// Dashboard Umum
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Profil User
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Pilih Role
Route::middleware(['auth'])->group(function () {
    Route::get('/select-role', function () {
        $roles = Auth::user()->roles;
        return view('auth.select-role', compact('roles'));
    })->name('select.role');

    Route::post('/select-role', function (\Illuminate\Http\Request $request) {
        $request->validate([
            'role' => 'required|exists:roles,name'
        ]);

        session(['active_role' => $request->role]);

        return redirect("/dashboard/{$request->role}");
    })->name('select.role.submit');
});

// ===========================
// Routes berdasarkan role
// ===========================

// ADMIN
Route::middleware(['auth', CheckRole::class . ':admin'])->prefix('admin')->as('admin.')->group(function () {

    Route::get('/', [AdminController::class, 'index'])->name('dashboard');

    Route::get('/profile', [UserController::class, 'profile'])->name('profile.profile');

    // Manajemen data master
    Route::resource('user', UserController::class);
    Route::resource('guru', GuruController::class);
    Route::resource('siswa', SiswaController::class);
    Route::resource('admin', KelasController::class);
    Route::resource('tahun-semester', TahunSemesterController::class);
    Route::resource('mapel', MapelController::class);
    Route::resource('ekstra', EkstraController::class);
    Route::resource('p5', P5Controller::class);
    Route::resource('bab', BabController::class);
    Route::resource('bab', BabController::class);
    Route::resource('bab', BabController::class);
    Route::resource('param-ekstra', BabController::class);
    Route::resource('bab', BabController::class);
});

// GURU
// Route::middleware(['auth', CheckRole::class.':guru'])->group(function () {
Route::get('/absensi', [AbsensiController::class, 'index'])->name('absensi.index');
Route::post('/absensi', [AbsensiController::class, 'store'])->name('absensi.store');
Route::get('/absensi/{kelas}/{tanggal}', [AbsensiController::class, 'show'])->name('absensi.show');

Route::get('/nilai', [NilaiController::class, 'index'])->name('nilai.index');
Route::post('/nilai', [NilaiController::class, 'store'])->name('nilai.store');
Route::get('/nilai/siswa/{siswa}', [NilaiController::class, 'show'])->name('nilai.show');
// });

// SISWA
// Route::middleware(['auth', CheckRole::class.':siswa'])->group(function () {
Route::get('/rapor', [RaporController::class, 'index'])->name('rapor.index');
Route::get('/rapor/{siswa}', [RaporController::class, 'show'])->name('rapor.show');
// });

// Profil Sekolah (umum)
Route::get('/profil-sekolah', fn() => view('profil-sekolah'))->name('profil.sekolah');

require __DIR__ . '/auth.php';

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    DashboardController,
    ProfileController,
    UserController,
    GuruController,
    SiswaController,
    WaliMuridController,
    KelasController,
    TahunSemesterController,
    AbsensiController,
    NilaiController,
    RaporController,
    MapelController,
    BabController,
    LingkupMateriController,
    TujuanPembelajaranController,
    EkstraController,
    ExportController,
    ParamEkstraController,
    P5MasterController,
    P5TemaController,
    P5DimensiController,
    P5ElemenController,
    P5SubElemenController,
    P5ProyekController,
    P5DokumentasiController,
    KelasMapelController,
    KelasSiswaController,
    KepalaSekolahMenuController,
    NilaiMapelController,
    NilaiEkstraController,
    NilaiP5Controller,
    PresensiHarianController,
    PresensiDetailController,
    RekapAbsensiController,
    SiswaMenuController
};

// Halaman Awal
Route::get('/', fn() => redirect('/dashboard'));

Route::middleware('auth')->get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Profil User
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ==========================
// Routes  for Authentication
// ==========================
Route::middleware(['auth', 'checkrole:admin'])->group(function () {
    Route::get('/admin', [DashboardController::class, 'index'])->name('dashboard.admin');

    Route::resource('user', UserController::class);
    Route::resource('guru', GuruController::class);
    Route::resource('siswa', SiswaController::class);
    Route::get('siswa/atur-kelas', [SiswaController::class, 'editKelas'])->name('siswa.aturKelas');
    Route::post('siswa/update-kelas', [SiswaController::class, 'updateKelas'])->name('siswa.updateKelas');
    Route::resource('wali-murid', WaliMuridController::class);
    Route::resource('kelas', KelasController::class);
    Route::resource('kelas-mapel', KelasMapelController::class);
    Route::prefix('kelas/{kelas}')->name('kelas.')->group(function () {
        Route::resource('mapel', KelasMapelController::class)->names('mapel');
    });
    Route::prefix('kelas/{kelas}')->name('kelas.')->group(function () {
        Route::resource('siswa', KelasSiswaController::class)->names('siswa');
    });
    Route::match(['get', 'post'], '/kelas/{kelas}/generate-absen', [KelasSiswaController::class, 'generateAbsen'])
        ->name('kelas.generate.absen');
    Route::post('/kelas/promote', [KelasSiswaController::class, 'promote'])
        ->middleware('checkrole:admin,kepala_sekolah')
        ->name('kelas.siswa.promote');
    Route::resource('tahun-semester', TahunSemesterController::class);
    // Route::resource('mapel', MapelMasterController::class);
    Route::resource('mapel', MapelController::class);
    Route::resource('bab', BabController::class);
    Route::resource('lingkup-materi', LingkupMateriController::class);
    Route::post('/lingkup-materi/duplikat', [LingkupMateriController::class, 'duplikatLingkupMateri'])->name('lingkup-materi.duplikat');
    Route::resource('tujuan-pembelajaran', TujuanPembelajaranController::class);
    Route::resource('ekstra', EkstraController::class);
    Route::get('ekstrakurikuler/{ekstra}/kelas', [EkstraController::class, 'kelas'])
        ->name('ekstra.kelas');
    Route::resource('param-ekstra', ParamEkstraController::class);
    Route::resource('p5', P5MasterController::class);
    Route::resource('p5-tema', P5TemaController::class);
    Route::resource('p5-dimensi', P5DimensiController::class);
    Route::resource('p5-elemen', P5ElemenController::class);
    Route::resource('p5-subelemen', P5SubElemenController::class);
    Route::resource('p5-proyek', P5ProyekController::class);
    Route::resource('p5-dokumentasi', P5DokumentasiController::class);
    // Route::resource('p5-proyek-dimensi', P5ProyekDimensiController::class);
    // Route::resource('p5-proyek-subelemen', P5ProyekSubElemenController::class);
    // Route::resource('dimensi-p5', DimensiP5Controller::class);
    Route::resource('rekap-absensi', RekapAbsensiController::class);
    Route::post('/rekap-absensi/update-batch', [RekapAbsensiController::class, 'updateBatch'])->name('rekap-absensi.update-batch');
    Route::resource('presensi-harian', PresensiHarianController::class);
    Route::get('/presensi-harian/{id}/detail', [PresensiDetailController::class, 'show'])->name('presensi-detail.show');
    Route::resource('presaensi-detail', PresensiDetailController::class);
    Route::resource('nilai-mapel',  NilaiMapelController::class);
    Route::post('/nilai-mapel/bulk-store', [NilaiMapelController::class, 'bulkStore'])->name('nilai-mapel.bulk-store');
    Route::get('nilai-mapel/sumatif', [NilaiMapelController::class, 'sumatif'])->name('nilai-mapel.sumatif');
    Route::resource('nilai-ekstra',  NilaiEkstraController::class);
    Route::post('/nilai-ekstra/update-batch', [NilaiEkstraController::class, 'updateBatch'])->name('nilai-ekstra.update-batch');
    Route::resource('nilai-p5',  NilaiP5Controller::class);
    Route::resource('rapor',  RaporController::class);
    Route::get('/export', [ExportController::class, 'export'])->name('export.excel');
});

Route::middleware(['auth', 'checkrole:guru'])->get('/pilih-peran', function () {
    return view('pilih-peran');
})->name('pilih-peran');

Route::middleware(['auth', 'checkrole:guru'])->group(function () {
    Route::get('/dashboard/guru', [DashboardController::class, 'index'])->name('dashboard.guru');
    Route::get('/absensi', [AbsensiController::class, 'index'])->name('absensi.index');
    Route::post('/absensi', [AbsensiController::class, 'store'])->name('absensi.store');
    Route::get('/absensi/{kelas}/{tanggal}', [AbsensiController::class, 'show'])->name('absensi.show');

    Route::get('/nilai', [NilaiController::class, 'index'])->name('nilai.index');
    Route::post('/nilai', [NilaiController::class, 'store'])->name('nilai.store');
    Route::get('/nilai/siswa/{siswa}', [NilaiController::class, 'show'])->name('nilai.show');

    Route::prefix('nilai')->group(function () {
        Route::get('/mapel', [NilaiMapelController::class, 'mapel'])->name('nilai.mapel');
        Route::post('/mapel', [NilaiController::class, 'simpanMapel'])->name('nilai.mapel.simpan');

        Route::get('/ekstra', [NilaiController::class, 'ekstra'])->name('nilai.ekstra');
        Route::post('/ekstra', [NilaiController::class, 'simpanEkstra'])->name('nilai.ekstra.simpan');

        Route::get('/p5', [NilaiController::class, 'p5'])->name('nilai.p5');
        Route::post('/p5', [NilaiController::class, 'simpanP5'])->name('nilai.p5.simpan');
    });
});

Route::middleware(['auth', 'checkrole:wali_kelas'])->group(function () {
    Route::get('/dashboard/wali-kelas', [DashboardController::class, 'index'])->name('dashboard.wali-kelas');
});

Route::middleware(['auth', 'checkrole:kepala_sekolah'])->group(function () {
    Route::get('/dashboard/kepala-sekolah', [DashboardController::class, 'index'])->name('dashboard.kepala-sekolah');
    Route::get('/kepala-sekolah/data-guru', [KepalaSekolahMenuController::class, 'dataGuru'])->name('data-guru.index');
    Route::get('/kepala-sekolah/data-siswa', [KepalaSekolahMenuController::class, 'dataSiswa'])->name('data-siswa.index');
    Route::get('/kepala-sekolah/data-wali-murid', [KepalaSekolahMenuController::class, 'dataWaliMurid'])->name('data-wali-murid.index');
    Route::get('/kepala-sekolah/rekap-absensi', [KepalaSekolahMenuController::class, 'rekapAbsensi'])->name('data-rekap-absensi.index');
    Route::get('/kepala-sekolah/nilai-mapel', [KepalaSekolahMenuController::class, 'nilaiMapel'])->name('data-nilai-mapel.index');
    Route::get('/kepala-sekolah/nilai-ekstra', [KepalaSekolahMenuController::class, 'nilaiEkstra'])->name('data-nilai-ekstra.index');
    Route::get('/kepala-sekolah/nilai-p5', [KepalaSekolahMenuController::class, 'nilaiP5'])->name('data-nilai-p5.index');
    Route::get('/kepala-sekolah/rapor', [KepalaSekolahMenuController::class, 'rapor'])->name('data-rapor.index');
});

Route::middleware(['auth', 'checkrole:siswa'])->group(function () {
    Route::get('/dashboard/siswa', [DashboardController::class, 'index'])->name('dashboard.siswa');
    Route::get('/absensi-siswa', [SiswaMenuController::class, 'absensi'])->name('absensi-siswa.index');
    Route::get('/nilai-mapel-siswa', [SiswaMenuController::class, 'nilaiMapel'])->name('nilai-mapel-siswa.index');
    Route::get('/nilai-ekstra-siswa', [SiswaMenuController::class, 'nilaiEkstra'])->name('nilai-ekstra-siswa.index');
    Route::get('/nilai-p5-siswa', [SiswaMenuController::class, 'nilaiP5'])->name('nilai-p5-siswa.index');
});


// Profil Sekolah (umum)
// Route::get('/profil-sekolah', fn() => view('profil-sekolah'))->name('profil.sekolah');

Route::get('/export', [ExportController::class, 'export'])->name('export.excel');

require __DIR__ . '/auth.php';

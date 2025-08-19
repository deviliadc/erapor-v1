<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    DashboardController,
    ProfileController,
    UserController,
    GuruController,
    SiswaController,
    WaliMuridController,
    FaseController,
    KelasController,
    TahunSemesterController,
    RaporController,
    MapelController,
    BabController,
    LingkupMateriController,
    TujuanPembelajaranController,
    EkstraController,
    ParamEkstraController,
    P5MasterController,
    P5TemaController,
    P5DimensiController,
    P5ElemenController,
    P5SubElemenController,
    P5CapaianController,
    P5ProyekController,
    // KelasMapelController,
    KelasSiswaController,
    MapelKelasController,
    NilaiMapelController,
    NilaiEkstraController,
    NilaiP5Controller,
    P5ProyekDetailController,
    PresensiHarianController,
    PresensiDetailController,
    RekapAbsensiController,
    SiswaMenuController,
    KepalaSekolahMenuController,
    PengaturanRaporController,
    TahunAjaranController
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
Route::middleware(['auth', 'checkrole:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('user/export', [UserController::class, 'export'])->name('user.export');
    Route::resource('user', UserController::class);
    // Route::get('/user/export', [UserController::class, 'export'])->name('user.export');
    Route::get('guru/template', [GuruController::class, 'template'])->name('guru.template');
    Route::get('guru/export', [GuruController::class, 'export'])->name('guru.export');
    Route::post('guru/import', [GuruController::class, 'import'])->name('guru.import');
    Route::resource('guru', GuruController::class);
    Route::get('siswa/template', [SiswaController::class, 'template'])->name('siswa.template');
    Route::get('siswa/export', [SiswaController::class, 'export'])->name('siswa.export');
    Route::post('siswa/import', [SiswaController::class, 'import'])->name('siswa.import');
    // Route::get('siswa/atur-kelas', [SiswaController::class, 'editKelas'])->name('siswa.aturKelas');
    // Route::post('siswa/update-kelas', [SiswaController::class, 'updateKelas'])->name('siswa.updateKelas');
    Route::resource('siswa', SiswaController::class);
    // Route::get('siswa/atur-kelas', [SiswaController::class, 'editKelas'])->name('siswa.aturKelas');
    // Route::post('siswa/update-kelas', [SiswaController::class, 'updateKelas'])->name('siswa.updateKelas');
    // Route::get('siswa/export', [SiswaController::class, 'export'])->name('siswa.export');
    // Route::get('siswa/template', [SiswaController::class, 'template'])->name('siswa.template');
    // Route::post('siswa/import', [SiswaController::class, 'import'])->name('siswa.import');
    Route::resource('wali-murid', WaliMuridController::class);
    Route::resource('fase', FaseController::class);
    Route::resource('kelas', KelasController::class);
    Route::resource('kelas-siswa', KelasSiswaController::class);
    Route::get('admin/kelas-siswa/{kelas}/detail', [KelasSiswaController::class, 'show'])
        ->name('kelas-siswa.detail');
    Route::put('kelas-siswa/{kelas}/update-wali', [KelasSiswaController::class, 'updateWali'])
        ->name('kelas-siswa.update-wali');
    Route::put('kelas-siswa/{kelas}/generate-absen', [KelasSiswaController::class, 'generateAbsen'])
        ->name('kelas-siswa.generate-absen');
    Route::post('kelas-siswa/promote', [KelasSiswaController::class, 'promote'])
        ->name('kelas-siswa.promote');
    Route::resource('mapel-kelas', MapelKelasController::class);
    Route::get('admin/mapel-kelas/{kelas}/detail', [MapelKelasController::class, 'show'])
        ->name('mapel-kelas.detail');
    // Route::prefix('kelas/{kelas}')->name('kelas.')->group(function () {
    //     Route::resource('mapel', KelasMapelController::class)->names('mapel');
    // });
    // Route::prefix('kelas/{kelas}')->name('kelas.')->group(function () {
    //     Route::resource('siswa', KelasSiswaController::class)->names('siswa');
    // });
    // Route::match(['get', 'post'], '/kelas/{kelas}/generate-absen', [KelasSiswaController::class, 'generateAbsen'])
    //     ->name('kelas.generate.absen');
    // Route::post('kelas/promote', [KelasSiswaController::class, 'promote'])
    //     ->middleware('checkrole:admin,guru')
    //     ->name('kelas.siswa.promote');
    Route::resource('tahun-ajaran', TahunAjaranController::class);
    Route::resource('tahun-semester', TahunSemesterController::class);
    // Route::resource('mapel', MapelMasterController::class);
    Route::resource('mapel', MapelController::class);
    Route::resource('bab', BabController::class);
    Route::resource('lingkup-materi', LingkupMateriController::class);
    // Route::post('lingkup-materi/duplikat', [LingkupMateriController::class, 'duplikatLingkupMateri'])->name('lingkup-materi.duplikat');
    Route::resource('tujuan-pembelajaran', TujuanPembelajaranController::class);
    Route::resource('ekstra', EkstraController::class);
    // Route::get('ekstrakurikuler/{ekstra}/kelas', [EkstraController::class, 'kelas'])
    //     ->name('ekstra.kelas');
    Route::resource('param-ekstra', ParamEkstraController::class);
    Route::resource('p5', P5MasterController::class);
    // Route::resource('p5-tema', P5TemaController::class);
    Route::resource('p5-dimensi', P5DimensiController::class);
    Route::resource('p5-elemen', P5ElemenController::class);
    Route::resource('p5-subelemen', P5SubElemenController::class);
    Route::resource('p5-capaian', P5CapaianController::class);
    Route::resource('p5-proyek', P5ProyekController::class);
    // Route::get('/p5-proyek/{id}', [P5ProyekController::class, 'show'])->name('p5-proyek.show');
    Route::resource('p5-proyek-detail', P5ProyekDetailController::class);
    // Route::resource('p5-dokumentasi', P5DokumentasiController::class);
    // Route::resource('p5-proyek-dimensi', P5ProyekDimensiController::class);
    // Route::resource('p5-proyek-subelemen', P5ProyekSubElemenController::class);
    // Route::resource('dimensi-p5', DimensiP5Controller::class);
    Route::resource('rekap-absensi', RekapAbsensiController::class);
    Route::post('rekap-absensi/update-batch', [RekapAbsensiController::class, 'updateBatch'])->name('rekap-absensi.update-batch');
    Route::resource('presensi-harian', PresensiHarianController::class);
    Route::get('presensi-harian/{id}/detail', [PresensiDetailController::class, 'show'])->name('presensi-detail.show');
    Route::resource('presensi-detail', PresensiDetailController::class);
    Route::resource('nilai-mapel',  NilaiMapelController::class);
    Route::post('nilai-mapel/pilih-materi', [NilaiMapelController::class, 'pilihMateri'])->name('nilai-mapel.pilih-materi');
    Route::post('nilai-mapel/update-batch', [NilaiMapelController::class, 'updateBatch'])->name('nilai-mapel.update-batch');
    // Route::get('nilai-mapel/sumatif', [NilaiMapelController::class, 'sumatif'])->name('nilai-mapel.sumatif');
    Route::resource('nilai-ekstra',  NilaiEkstraController::class);
    Route::post('nilai-ekstra/update-batch', [NilaiEkstraController::class, 'updateBatch'])->name('nilai-ekstra.update-batch');
    Route::resource('nilai-p5',  NilaiP5Controller::class);
    // Route::post('/nilai-p5/proyek-store', [NilaiP5Controller::class, 'proyekStore'])->name('nilai-p5.proyek-store');
    Route::post('nilai-p5/update-batch', [NilaiP5Controller::class, 'updateBatch'])->name('nilai-p5.update-batch');
    Route::resource('rapor',  RaporController::class);
    Route::resource('pengaturan-rapor',  PengaturanRaporController::class);
    // Route::get('arsip-siswa', [ArsipSiswaController::class, 'index'])->name('arsip-siswa.index');
    // Route::get('arsip-guru', [ArsipGuruController::class, 'index'])->name('arsip-guru.index');
});

Route::middleware(['auth', 'checkrole:guru'])->prefix('guru')->name('guru.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('siswa/template', [SiswaController::class, 'template'])->name('siswa.template');
    Route::get('siswa/export', [SiswaController::class, 'export'])->name('siswa.export');
    Route::post('siswa/import', [SiswaController::class, 'import'])->name('siswa.import');
    Route::get('siswa/atur-kelas', [SiswaController::class, 'editKelas'])->name('siswa.aturKelas');
    Route::post('siswa/update-kelas', [SiswaController::class, 'updateKelas'])->name('siswa.updateKelas');
    Route::resource('siswa', SiswaController::class)->except(['destroy']);
    // Route::get('siswa/atur-kelas', [SiswaController::class, 'editKelas'])->name('siswa.aturKelas');
    // Route::post('siswa/update-kelas', [SiswaController::class, 'updateKelas'])->name('siswa.updateKelas');
    // Route::get('siswa/import', [SiswaController::class, 'importForm'])->name('siswa.import.form');
    // Route::get('/siswa/header', [SiswaController::class, 'header'])->name('siswa.header');
    // Route::get('siswa/export', [SiswaController::class, 'export'])->name('siswa.export');
    // Route::get('siswa/template', [SiswaController::class, 'template'])->name('siswa.template');
    // Route::post('siswa/import', [SiswaController::class, 'import'])->name('siswa.import');
    Route::resource('wali-murid', WaliMuridController::class)->except(['destroy']);
    // Route::resource('fase', FaseController::class);
    // Route::resource('kelas', KelasController::class);
    // Route::resource('kelas-mapel', KelasMapelController::class);
    Route::prefix('kelas/{kelas}')->name('kelas.')->group(function () {
        Route::resource('mapel', MapelKelasController::class)->names('mapel');
    });
    Route::prefix('kelas/{kelas}')->name('kelas.')->group(function () {
        Route::resource('siswa', KelasSiswaController::class)->names('siswa');
    });
    Route::match(['get', 'post'], '/kelas/{kelas}/generate-absen', [KelasSiswaController::class, 'generateAbsen'])
        ->name('kelas.generate.absen');
    Route::post('kelas/promote', [KelasSiswaController::class, 'promote'])
        ->middleware('checkrole:admin,guru')
        ->name('kelas.siswa.promote');
    Route::resource('mapel', MapelController::class);
    Route::resource('bab', BabController::class);
    Route::resource('lingkup-materi', LingkupMateriController::class);
    Route::post('lingkup-materi/duplikat', [LingkupMateriController::class, 'duplikatLingkupMateri'])->name('lingkup-materi.duplikat');
    Route::resource('tujuan-pembelajaran', TujuanPembelajaranController::class);
    Route::resource('ekstra', EkstraController::class);
    Route::get('ekstrakurikuler/{ekstra}/kelas', [EkstraController::class, 'kelas'])
        ->name('ekstra.kelas');
    Route::resource('param-ekstra', ParamEkstraController::class);
    Route::resource('p5', P5MasterController::class);
    // Route::resource('p5-tema', P5TemaController::class);
    Route::resource('p5-dimensi', P5DimensiController::class);
    Route::resource('p5-elemen', P5ElemenController::class);
    Route::resource('p5-subelemen', P5SubElemenController::class);
    Route::resource('p5-capaian', P5CapaianController::class);
    Route::resource('p5-proyek', P5ProyekController::class);
    // Route::get('/p5-proyek/{id}', [P5ProyekController::class, 'show'])->name('p5-proyek.show');
    Route::resource('p5-proyek-detail', P5ProyekDetailController::class);
    // Route::resource('p5-dokumentasi', P5DokumentasiController::class);
    // Route::resource('p5-proyek-dimensi', P5ProyekDimensiController::class);
    // Route::resource('p5-proyek-subelemen', P5ProyekSubElemenController::class);
    // Route::resource('dimensi-p5', DimensiP5Controller::class);
    Route::resource('rekap-absensi', RekapAbsensiController::class);
    Route::post('rekap-absensi/update-batch', [RekapAbsensiController::class, 'updateBatch'])->name('rekap-absensi.update-batch');
    Route::resource('presensi-harian', PresensiHarianController::class);
    Route::get('presensi-harian/{id}/detail', [PresensiDetailController::class, 'show'])->name('presensi-detail.show');
    Route::resource('presensi-detail', PresensiDetailController::class);
    Route::resource('nilai-mapel',  NilaiMapelController::class);
    Route::post('nilai-mapel/pilih-materi', [NilaiMapelController::class, 'pilihMateri'])->name('nilai-mapel.pilih-materi');
    Route::post('nilai-mapel/bulk-store', [NilaiMapelController::class, 'bulkStore'])->name('nilai-mapel.bulk-store');
    // Route::get('nilai-mapel/sumatif', [NilaiMapelController::class, 'sumatif'])->name('nilai-mapel.sumatif');
    Route::resource('nilai-ekstra',  NilaiEkstraController::class);
    Route::post('nilai-ekstra/update-batch', [NilaiEkstraController::class, 'updateBatch'])->name('nilai-ekstra.update-batch');
    Route::resource('nilai-p5',  NilaiP5Controller::class);
    // Route::post('/nilai-p5/proyek-store', [NilaiP5Controller::class, 'proyekStore'])->name('nilai-p5.proyek-store');
    Route::post('nilai-p5/update-batch', [NilaiP5Controller::class, 'updateBatch'])->name('nilai-p5.update-batch');
    Route::resource('rapor',  RaporController::class);
    Route::get('rapor/export', [RaporController::class, 'export'])->name('rapor.export');
    Route::resource('pengaturan-rapor',  PengaturanRaporController::class);
});

Route::middleware(['auth', 'checkrole:kepala_sekolah'])->group(function () {
    Route::get('dashboard/kepala-sekolah', [DashboardController::class, 'index'])->name('dashboard.kepala-sekolah');
    Route::get('kepala-sekolah/data-guru', [KepalaSekolahMenuController::class, 'dataGuru'])->name('data-guru.index');
    // Route::get('/siswa', [SiswaController::class, 'index'])->name('kepsek.siswa.index');
    // Route::get('/siswa/{id}', [SiswaController::class, 'show'])->name('kepsek.siswa.show');
    Route::get('kepala-sekolah/data-siswa', [KepalaSekolahMenuController::class, 'dataSiswa'])->name('data-siswa.index');
    Route::get('kepala-sekolah/data-wali-murid', [KepalaSekolahMenuController::class, 'dataWaliMurid'])->name('data-wali-murid.index');
    Route::get('kepala-sekolah/rekap-absensi', [KepalaSekolahMenuController::class, 'rekapAbsensi'])->name('data-rekap-absensi.index');
    Route::get('kepala-sekolah/nilai-mapel', [KepalaSekolahMenuController::class, 'nilaiMapel'])->name('data-nilai-mapel.index');
    Route::get('kepala-sekolah/nilai-ekstra', [KepalaSekolahMenuController::class, 'nilaiEkstra'])->name('data-nilai-ekstra.index');
    Route::get('kepala-sekolah/nilai-p5', [KepalaSekolahMenuController::class, 'nilaiP5'])->name('data-nilai-p5.index');
    Route::get('kepala-sekolah/rapor', [KepalaSekolahMenuController::class, 'rapor'])->name('data-rapor.index');
});

Route::middleware(['auth', 'checkrole:siswa'])->group(function () {
    Route::get('dashboard/siswa', [DashboardController::class, 'index'])->name('dashboard.siswa');
    Route::get('absensi-siswa', [SiswaMenuController::class, 'absensi'])->name('absensi-siswa');
    Route::get('nilai-mapel-siswa', [SiswaMenuController::class, 'nilaiMapel'])->name('nilai-mapel-siswa');
    Route::get('nilai-ekstra-siswa', [SiswaMenuController::class, 'nilaiEkstra'])->name('nilai-ekstra-siswa');
    Route::get('nilai-p5-siswa', [SiswaMenuController::class, 'nilaiP5'])->name('nilai-p5-siswa');
});


// Profil Sekolah (umum)
// Route::get('/profil-sekolah', fn() => view('profil-sekolah'))->name('profil.sekolah');

require __DIR__ . '/auth.php';

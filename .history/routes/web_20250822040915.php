<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    ArsipController,
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
    LegerRaporController,
    PengaturanRaporController,
    TahunAjaranController,
    ValidasiSemesterController
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
    Route::get('guru/export', [GuruController::class, 'export'])->name('guru.export');
    Route::get('guru/template', [GuruController::class, 'template'])->name('guru.template');
    Route::post('guru/import', [GuruController::class, 'import'])->name('guru.import');
    Route::resource('guru', GuruController::class);
    Route::get('siswa/export', [SiswaController::class, 'export'])->name('siswa.export');
    Route::get('siswa/template', [SiswaController::class, 'template'])->name('siswa.template');
    Route::post('siswa/import', [SiswaController::class, 'import'])->name('siswa.import');
    Route::resource('siswa', SiswaController::class);
    Route::resource('wali-murid', WaliMuridController::class);
    Route::resource('fase', FaseController::class);
    Route::resource('kelas', KelasController::class);
    Route::resource('kelas-siswa', KelasSiswaController::class);
    Route::get('kelas-siswa/{kelas}/detail', [KelasSiswaController::class, 'show'])
        ->name('kelas-siswa.detail');
    Route::put('kelas-siswa/{kelas}/update-wali', [KelasSiswaController::class, 'updateWali'])
        ->name('kelas-siswa.update-wali');
    Route::put('kelas-siswa/{kelas}/generate-absen', [KelasSiswaController::class, 'generateAbsen'])
        ->name('kelas-siswa.generate-absen');
    Route::post('kelas-siswa/promote', [KelasSiswaController::class, 'promote'])
        ->name('kelas-siswa.promote');
    Route::post('kelas-siswa/promote-global', [KelasSiswaController::class, 'promoteGlobal'])->name('kelas-siswa.promoteGlobal');
    Route::post('kelas-siswa/luluskan', [KelasSiswaController::class, 'luluskan'])->name('kelas-siswa.luluskan');
    Route::resource('mapel-kelas', MapelKelasController::class);
    Route::get('mapel-kelas/{kelas}/detail', [MapelKelasController::class, 'show'])
        ->name('mapel-kelas.detail');
    Route::resource('tahun-ajaran', TahunAjaranController::class);
    Route::resource('tahun-semester', TahunSemesterController::class);
    Route::resource('mapel', MapelController::class);
    Route::resource('bab', BabController::class);
    Route::resource('lingkup-materi', LingkupMateriController::class);
    Route::resource('tujuan-pembelajaran', TujuanPembelajaranController::class);
    Route::resource('ekstra', EkstraController::class);
    Route::resource('param-ekstra', ParamEkstraController::class);
    Route::resource('p5', P5MasterController::class);
    // Route::resource('p5-tema', P5TemaController::class);
    Route::resource('p5-dimensi', P5DimensiController::class);
    Route::resource('p5-elemen', P5ElemenController::class);
    Route::resource('p5-subelemen', P5SubElemenController::class);
    Route::resource('p5-capaian', P5CapaianController::class);
    Route::resource('p5-proyek', P5ProyekController::class);
    Route::resource('p5-proyek-detail', P5ProyekDetailController::class);
    // Route::resource('p5-dokumentasi', P5DokumentasiController::class);
    Route::resource('rekap-absensi', RekapAbsensiController::class);
    Route::post('rekap-absensi/update-batch', [RekapAbsensiController::class, 'updateBatch'])->name('rekap-absensi.update-batch');
    Route::get('presensi-harian/export', [PresensiHarianController::class, 'export'])->name('presensi-harian.export');
    Route::get('presensi-harian/template-bulan/{bulan}/{tahun}', [PresensiHarianController::class, 'templateBulan'])
        ->name('presensi-harian.template-bulan');
    Route::post('presensi-harian/import', [PresensiHarianController::class, 'import'])->name('presensi-harian.import');
    Route::resource('presensi-harian', PresensiHarianController::class);
    Route::get('presensi-harian/{id}/detail', [PresensiDetailController::class, 'show'])->name('presensi-detail.show');
    Route::resource('presensi-detail', PresensiDetailController::class);
    Route::resource('nilai-mapel',  NilaiMapelController::class);
    Route::post('nilai-mapel/pilih-materi', [NilaiMapelController::class, 'pilihMateri'])->name('nilai-mapel.pilih-materi');
    Route::post('nilai-mapel/update-batch', [NilaiMapelController::class, 'updateBatch'])->name('nilai-mapel.update-batch');
    Route::resource('nilai-ekstra',  NilaiEkstraController::class);
    Route::post('nilai-ekstra/update-batch', [NilaiEkstraController::class, 'updateBatch'])->name('nilai-ekstra.update-batch');
    Route::resource('nilai-p5',  NilaiP5Controller::class);
    Route::post('nilai-p5/update-batch', [NilaiP5Controller::class, 'updateBatch'])->name('nilai-p5.update-batch');
    Route::resource('leger-rapor', LegerRaporController::class);
    Route::get('leger-rapor/export', [LegerRaporController::class, 'export'])->name('leger-rapor.export');
    Route::resource('rapor',  RaporController::class);
    Route::get('rapor/{siswa}/cetak-kelengkapan', [RaporController::class, 'cetakKelengkapan'])->name('rapor.cetakKelengkapan');
    Route::get('rapor/{siswa}/cetak-tengah', [RaporController::class, 'cetakTengah'])->name('rapor.cetakTengah');
    Route::get('rapor/{siswa}/cetak-akhir', [RaporController::class, 'cetakAkhir'])->name('rapor.cetakAkhir');
    Route::get('rapor/{siswa}/cetak-p5', [RaporController::class, 'cetakP5'])->name('rapor.cetakP5');
    Route::resource('pengaturan-rapor',  PengaturanRaporController::class);
    Route::resource('validasi-semester', ValidasiSemesterController::class);
    Route::post('validasi-semester/validate/{validasiSemester}', [ValidasiSemesterController::class, 'validateType'])->name('validasi-semester.validate');
    Route::post('validasi-semester/cancel/{validasiSemester}', [ValidasiSemesterController::class, 'cancelValidation'])->name('validasi-semester.cancel');
    Route::post('validasi-semester/validate-all', [ValidasiSemesterController::class, 'validateAll'])
        ->name('validasi_semester.validateAll');
    Route::get('arsip-siswa', [ArsipController::class, 'siswa'])->name('arsip-siswa.index');
    Route::get('arsip-siswa/export', [ArsipController::class, 'exportSiswa'])->name('arsip-siswa.export');
    Route::get('arsip-guru', [ArsipController::class, 'guru'])->name('arsip-guru.index');
    Route::get('arsip-guru/export', [ArsipController::class, 'exportGuru'])->name('arsip-guru.export');
});

Route::middleware(['auth', 'checkrole:guru'])->prefix('guru')->name('guru.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('siswa/export', [SiswaController::class, 'export'])->name('siswa.export');
    Route::get('siswa/template', [SiswaController::class, 'template'])->name('siswa.template');
    Route::post('siswa/import', [SiswaController::class, 'import'])->name('siswa.import');
    Route::resource('siswa', SiswaController::class);
    Route::resource('wali-murid', WaliMuridController::class);
    Route::resource('fase', FaseController::class);
    Route::resource('kelas', KelasController::class);
    Route::resource('kelas-siswa', KelasSiswaController::class);
    Route::get('kelas-siswa/{kelas}/detail', [KelasSiswaController::class, 'show'])
        ->name('kelas-siswa.detail');
    Route::put('kelas-siswa/{kelas}/update-wali', [KelasSiswaController::class, 'updateWali'])
        ->name('kelas-siswa.update-wali');
    Route::put('kelas-siswa/{kelas}/generate-absen', [KelasSiswaController::class, 'generateAbsen'])
        ->name('kelas-siswa.generate-absen');
    Route::post('kelas-siswa/promote', [KelasSiswaController::class, 'promote'])
        ->name('kelas-siswa.promote');
    Route::post('kelas-siswa/promote-global', [KelasSiswaController::class, 'promoteGlobal'])->name('kelas-siswa.promoteGlobal');
    Route::post('kelas-siswa/luluskan', [KelasSiswaController::class, 'luluskan'])->name('kelas-siswa.luluskan');
    Route::resource('mapel-kelas', MapelKelasController::class);
    Route::get('mapel-kelas/{kelas}/detail', [MapelKelasController::class, 'show'])
        ->name('mapel-kelas.detail');
    Route::resource('tahun-ajaran', TahunAjaranController::class);
    Route::resource('tahun-semester', TahunSemesterController::class);
    Route::resource('mapel', MapelController::class);
    Route::resource('bab', BabController::class);
    Route::resource('lingkup-materi', LingkupMateriController::class);
    Route::resource('tujuan-pembelajaran', TujuanPembelajaranController::class);
    Route::resource('ekstra', EkstraController::class);
    Route::resource('param-ekstra', ParamEkstraController::class);
    Route::resource('p5', P5MasterController::class);
    // Route::resource('p5-tema', P5TemaController::class);
    Route::resource('p5-dimensi', P5DimensiController::class);
    Route::resource('p5-elemen', P5ElemenController::class);
    Route::resource('p5-subelemen', P5SubElemenController::class);
    Route::resource('p5-capaian', P5CapaianController::class);
    Route::resource('p5-proyek', P5ProyekController::class);
    Route::resource('p5-proyek-detail', P5ProyekDetailController::class);
    // Route::resource('p5-dokumentasi', P5DokumentasiController::class);
    Route::resource('rekap-absensi', RekapAbsensiController::class);
    Route::post('rekap-absensi/update-batch', [RekapAbsensiController::class, 'updateBatch'])->name('rekap-absensi.update-batch');
    Route::get('presensi-harian/export', [PresensiHarianController::class, 'export'])->name('presensi-harian.export');
    Route::get('presensi-harian/template-bulan/{bulan}/{tahun}', [PresensiHarianController::class, 'templateBulan'])
        ->name('presensi-harian.template-bulan');
    Route::post('presensi-harian/import', [PresensiHarianController::class, 'import'])->name('presensi-harian.import');
    Route::resource('presensi-harian', PresensiHarianController::class);
    Route::get('presensi-harian/{id}/detail', [PresensiDetailController::class, 'show'])->name('presensi-detail.show');
    Route::resource('presensi-detail', PresensiDetailController::class);
    Route::resource('nilai-mapel',  NilaiMapelController::class);
    Route::post('nilai-mapel/pilih-materi', [NilaiMapelController::class, 'pilihMateri'])->name('nilai-mapel.pilih-materi');
    Route::post('nilai-mapel/update-batch', [NilaiMapelController::class, 'updateBatch'])->name('nilai-mapel.update-batch');
    Route::resource('nilai-ekstra',  NilaiEkstraController::class);
    Route::post('nilai-ekstra/update-batch', [NilaiEkstraController::class, 'updateBatch'])->name('nilai-ekstra.update-batch');
    Route::resource('nilai-p5',  NilaiP5Controller::class);
    Route::post('nilai-p5/update-batch', [NilaiP5Controller::class, 'updateBatch'])->name('nilai-p5.update-batch');
    Route::resource('leger-rapor', LegerRaporController::class);
    Route::get('leger-rapor/export', [LegerRaporController::class, 'export'])->name('leger-rapor.export');
    Route::resource('rapor',  RaporController::class);
    Route::get('rapor/{siswa}/cetak-kelengkapan', [RaporController::class, 'cetakKelengkapan'])->name('rapor.cetakKelengkapan');
    Route::get('rapor/{siswa}/cetak-tengah', [RaporController::class, 'cetakTengah'])->name('rapor.cetakTengah');
    Route::get('rapor/{siswa}/cetak-akhir', [RaporController::class, 'cetakAkhir'])->name('rapor.cetakAkhir');
    Route::get('rapor/{siswa}/cetak-p5', [RaporController::class, 'cetakP5'])->name('rapor.cetakP5');
    Route::resource('pengaturan-rapor',  PengaturanRaporController::class);
    Route::resource('validasi-semester', ValidasiSemesterController::class);
    Route::post('validasi-semester/validate/{validasiSemester}', [ValidasiSemesterController::class, 'validateType'])->name('validasi-semester.validate');
    Route::post('validasi-semester/cancel/{validasiSemester}', [ValidasiSemesterController::class, 'cancelValidation'])->name('validasi-semester.cancel');
    Route::post('validasi-semester/validate-all', [ValidasiSemesterController::class, 'validateAll'])
        ->name('validasi_semester.validateAll');
    Route::get('arsip-siswa', [ArsipController::class, 'siswa'])->name('arsip-siswa.index');
    Route::get('arsip-siswa/export', [ArsipController::class, 'exportSiswa'])->name('arsip-siswa.export');
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

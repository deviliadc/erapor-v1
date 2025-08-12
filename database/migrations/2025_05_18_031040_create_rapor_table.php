<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rapor', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelas_siswa_id')->constrained('kelas_siswa')->onDelete('cascade');
            $table->foreignId('siswa_id')->constrained('siswa')->onDelete('cascade');
            $table->foreignId('tahun_semester_id')->constrained('tahun_semester')->onDelete('cascade');

            // Ringkasan nilai
            $table->json('rekap_nilai_mapel')->nullable();
            $table->json('rekap_nilai_ekstra')->nullable();
            $table->json('rekap_nilai_p5')->nullable();

            // Catatan guru
            $table->text('catatan_wali')->nullable();

            // Rekap presensi
            $table->unsignedTinyInteger('jumlah_sakit')->nullable();
            $table->unsignedTinyInteger('jumlah_izin')->nullable();
            $table->unsignedTinyInteger('jumlah_alpha')->nullable();

            // Status validasi rapor
            $table->boolean('is_final')->default(false);
            $table->date('tanggal_finalisasi')->nullable();

            $table->timestamps();

            $table->unique([
                'siswa_id',
                'tahun_semester_id',
                // 'kelas_siswa_id',
            ], 'rapor_unik');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rapor');
    }
};

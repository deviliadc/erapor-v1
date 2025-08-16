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
        Schema::create('nilai_mapel', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('siswa_id')->constrained('siswa')->onDelete('cascade');
            $table->foreignId('kelas_siswa_id')->constrained('kelas_siswa')->onDelete('cascade');
            $table->foreignId('mapel_id')->constrained('mapel')->onDelete('cascade');
            $table->foreignId('tahun_semester_id')->constrained('tahun_semester')->onDelete('cascade');

            $table->float('nilai_akhir')->nullable();
            $table->text('deskripsi_tertinggi')->nullable();
            $table->text('deskripsi_terendah')->nullable();
            $table->enum('periode', ['tengah', 'akhir']); // tengah = UTS, akhir = rapor akhir
            // $table->boolean('is_validated')->default(false);
            $table->timestamps();

            $table->unique([
                'kelas_siswa_id',
                // 'siswa_id',
                'mapel_id',
                'tahun_semester_id',
                'periode'
            ], 'nilai_mapel_unique');
        });

        Schema::create('nilai_mapel_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nilai_mapel_id')->constrained('nilai_mapel')->onDelete('cascade');
            // $table->foreignId('kelas_siswa_id')->constrained('kelas_siswa')->onDelete('cascade');
            // $table->foreignId('siswa_id')->constrained('siswa')->onDelete('cascade');
            // $table->foreignId('mapel_id')->constrained('mapel')->onDelete('cascade');
            // $table->foreignId('tahun_semester_id')->constrained('tahun_semester')->onDelete('cascade');
            $table->foreignId('lingkup_materi_id')
                ->nullable()
                ->constrained('lingkup_materi')
                ->onDelete('set null');
            $table->foreignId('tujuan_pembelajaran_id')
                ->nullable()
                ->constrained('tujuan_pembelajaran')
                ->onDelete('set null');
            $table->enum('jenis_nilai', [
                'formatif',
                'sumatif',
                'uts-nontes',
                'uts-tes',
                'uas-nontes',
                'uas-tes'
            ]);
            // $table->float('nilai')->nullable();
            $table->decimal('nilai', 5, 2)->nullable();
            // $table->enum('periode', ['tengah', 'akhir']);
            // $table->boolean('is_validated')->default(false);
            $table->timestamps();

            $table->unique([
                // 'siswa_id',
                // 'mapel_id',
                // 'tahun_semester_id',
                // 'kelas_siswa_id',
                'nilai_mapel_id',
                'lingkup_materi_id',
                'tujuan_pembelajaran_id',
                'jenis_nilai',
                // 'periode'
            ], 'nilai_mapel_detail_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilai_mapel_detail');
        Schema::dropIfExists('nilai_mapel');
    }
};

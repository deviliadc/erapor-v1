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
            $table->foreignId('siswa_id')->constrained('siswa')->onDelete('cascade');
            $table->foreignId('kelas_siswa_id')->constrained('kelas_siswa')->onDelete('cascade');
            $table->foreignId('mapel_id')->constrained('mapel')->onDelete('cascade');
            // $table->foreignId('tahun_semester_id')->constrained('tahun_semester')->onDelete('cascade');

            $table->float('nilai_akhir')->nullable();
            $table->text('deskripsi_tertinggi')->nullable();
            $table->text('deskripsi_terendah')->nullable();
            $table->enum('periode', ['tengah', 'akhir']); // tengah = UTS, akhir = rapor akhir
            // $table->boolean('is_validated')->default(false);
            $table->timestamps();

            $table->unique([
                'kelas_siswa_id',
                'siswa_id',
                'mapel_id',
                // 'tahun_semester_id',
                'periode'
            ], 'nilai_mapel_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilai_mapel');
    }
};

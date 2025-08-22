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
        Schema::create('rekap_absensi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelas_siswa_id')->constrained('kelas_siswa')->onDelete('cascade');
            // $table->foreignId('siswa_id')->constrained('siswa')->onDelete('cascade');
            $table->foreignId('tahun_semester_id')->constrained('tahun_semester')->onDelete('cascade');
            $table->unsignedTinyInteger('total_sakit')->default(0);
            $table->unsignedTinyInteger('total_izin')->default(0);
            $table->unsignedTinyInteger('total_alfa')->default(0);
            $table->enum('periode', ['tengah', 'akhir']);
            // $table->boolean('is_validated')->default(false);
            $table->timestamps();

            $table->unique([
                // 'siswa_id',
                'tahun_semester_id',
                'kelas_siswa_id',
                'periode'
            ], 'rekap_absensi_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekap_absensi');
    }
};

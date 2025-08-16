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
        Schema::create('nilai_ekstra', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('siswa_id')->constrained('siswa')->onDelete('cascade');
            $table->foreignId('kelas_siswa_id')->constrained('kelas_siswa')->onDelete('cascade');
            $table->foreignId('ekstra_id')->constrained('ekstra')->onDelete('cascade');
            $table->foreignId('tahun_semester_id')->constrained('tahun_semester')->onDelete('cascade');
            $table->enum('periode', ['tengah', 'akhir']);
            $table->float('nilai_akhir')->nullable(); // rerata dari param_ekstra
            $table->string('deskripsi')->nullable(); // konversi dari nilai_akhir (opsional)
            // $table->boolean('is_validated')->default(false);
            $table->timestamps();

            $table->unique([
                // 'siswa_id',

                'kelas_siswa_id',
                'ekstra_id',
                'tahun_semester_id',
                'periode'
            ], 'nilai_ekstra_unique');
        });

        Schema::create('nilai_ekstra_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nilai_ekstra_id')->constrained('nilai_ekstra')->onDelete('cascade');
            $table->foreignId('param_ekstra_id')->constrained('param_ekstra')->onDelete('cascade');
            $table->enum('nilai', ['0', '1', '2', '3', '4'])->nullable(); // sesuai skor penilaian
            // $table->enum('periode', ['tengah', 'akhir']);
            // $table->boolean('is_validated')->default(false);
            $table->timestamps();

            $table->unique([
                'nilai_ekstra_id',
                'param_ekstra_id',
                // 'periode'
            ], 'nilai_ekstra_detail_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilai_ekstra_detail');
        Schema::dropIfExists('nilai_ekstra');
    }
};

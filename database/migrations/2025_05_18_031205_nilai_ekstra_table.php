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
            // $table->id();
            // $table->foreignId('siswa_id')->constrained('siswa')->onDelete('cascade');
            // $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');
            // $table->foreignId('tahun_semester_id')->constrained('tahun_semester')->onDelete('cascade');
            // $table->foreignId('ekstrakurikuler_id')->constrained('ekstrakurikuler')->onDelete('cascade');
            // $table->enum('predikat', ['A', 'B', 'C'])->nullable();
            // $table->text('deskripsi')->nullable();
            // $table->timestamps();

            // $table->unique(['siswa_id', 'ekstrakurikuler_id', 'tahun_semester_id'], 'nilai_ekstra_unik');
            $table->id();
            $table->foreignId('kelas_siswa_id')->constrained('kelas_siswa')->onDelete('cascade');
            // $table->foreignId('siswa_id')->constrained('siswa')->onDelete('cascade');
            $table->foreignId('ekstra_id')->constrained('ekstra')->onDelete('cascade');
            // $table->foreignId('tahun_semester_id')->constrained('tahun_semester')->onDelete('cascade');
            $table->enum('predikat', ['0', '1', '2', '3', '4'])->nullable();
            $table->text('deskripsi')->nullable();
            $table->enum('periode', ['tengah', 'akhir']);
            $table->float('nilai_akhir')->nullable();
            $table->boolean('is_validated')->default(false);
            $table->timestamps();

            $table->unique([
                // 'siswa_id',
                'ekstra_id',
                'kelas_siswa_id',
                'periode',
                // 'tahun_semester_id'
            ], 'nilai_ekstra_unique' );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilai_ekstra');
    }
};

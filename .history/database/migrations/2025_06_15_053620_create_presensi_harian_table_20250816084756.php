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
        Schema::create('presensi_harian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');
            $table->date('tanggal');
            // $table->foreignId('input_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('catatan')->nullable(); // Misalnya "Upacara bendera", opsional
            $table->enum('periode', ['tengah', 'akhir']);
            // $table->float('nilai_akhir')->nullable();
            // $table->boolean('is_validated')->default(false); // Untuk menandakan apakah presensi sudah diverifikasi
            $table->timestamps();

            $table->unique(['kelas_id', 'tanggal']); // Biar 1 kelas hanya 1 entri per hari
        });

        Schema::create('presensi_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('presensi_harian_id')->constrained('presensi_harian')->onDelete('cascade');
            $table->foreignId('kelas_siswa_id')->constrained('kelas_siswa')->onDelete('cascade');
            $table->enum('status', ['Hadir', 'Izin', 'Sakit', 'Alpha']);
            $table->text('keterangan')->nullable(); // opsional
            // $table->enum('periode', ['tengah', 'akhir']);
            // $table->float('nilai_akhir')->nullable();
            // $table->boolean('is_validated')->default(false);
            $table->timestamps();

            $table->unique(['presensi_harian_id', 'kelas_siswa_id']); // Satu siswa sekali saja per hari
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presensi_detail');
        Schema::dropIfExists('presensi_harian');
    }
};

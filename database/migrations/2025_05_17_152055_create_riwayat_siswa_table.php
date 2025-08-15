<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // public function up(): void
    // {
    //     Schema::create('riwayat_siswa', function (Blueprint $table) {
    //         $table->id();
    //         $table->unsignedTinyInteger('no_absen')->nullable();
    //         $table->foreignId('siswa_id')->constrained('siswa')->onDelete('cascade');
    //         $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');
    //         $table->foreignId('tahun_semester_id')->constrained('tahun_semester')->onDelete('cascade');
    //         $table->timestamps();
    //         $table->enum('status', ['Aktif', 'Lulus', 'Keluar', 'Mutasi'])->default('Aktif');
    //         $table->date('tanggal_masuk')->nullable();
    //         $table->date('tanggal_keluar')->nullable();
    //         $table->string('keterangan');

    //         // Untuk mencegah data ganda
    //         $table->unique(['siswa_id', 'kelas_id', 'tahun_semester_id']);
    //     });
    // }

    /**
     * Reverse the migrations.
     */
    // public function down(): void
    // {
    //     Schema::dropIfExists('riwayat_siswa');
    // }
};

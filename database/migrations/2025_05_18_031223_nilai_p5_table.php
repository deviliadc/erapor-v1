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
        Schema::create('nilai_p5', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->onDelete('cascade');
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');
            $table->foreignId('tahun_semester_id')->constrained('tahun_semester')->onDelete('cascade');
            $table->foreignId('p5_id')->constrained('p5')->onDelete('cascade');
            $table->foreignId('rapor_id')->constrained('rapor')->onDelete('cascade');
            $table->enum('predikat', ['Sangat Berkembang', 'Berkembang Sesuai Harapan', 'Mulai Berkembang', 'Belum Berkembang']);
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilai_p5');
    }
};

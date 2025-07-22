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
        Schema::create('siswa_ekstra', function (Blueprint $table) {
            // $table->id();

            // $table->foreignId('siswa_id')->constrained('siswa')->onDelete('cascade');
            // $table->foreignId('ekstra_id')->constrained('ekstra')->onDelete('cascade');
            // $table->foreignId('tahun_semester_id')->constrained('tahun_semester')->onDelete('cascade');
            // $table->timestamps();

            $table->id();
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');
            $table->foreignId('ekstra_id')->constrained('ekstra')->onDelete('cascade');
            $table->foreignId('tahun_semester_id')->constrained('tahun_semester')->onDelete('cascade');
            $table->timestamps();

            // Satu siswa hanya bisa ikut ekstra yang sama satu kali dalam satu semester
            $table->unique(['kelas_id', 'ekstra_id', 'tahun_semester_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswa_ekstra');
    }
};

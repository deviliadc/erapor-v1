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
        Schema::create('guru_kelas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guru_id')->constrained('guru')->onDelete('cascade');
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');
            // $table->foreignId('tahun_semester_id')->constrained('tahun_semester')->onDelete('cascade');
            $table->foreignId('mapel_id')->nullable()->constrained('mapel')->nullOnDelete(); // opsional
            $table->enum('peran', ['wali', 'pengajar'])->default('pengajar'); // ← Tambahkan ini
            $table->unsignedTinyInteger('urutan')->nullable()->default(0); // Urutan mapel dalam kelas
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guru_kelas');
    }
};

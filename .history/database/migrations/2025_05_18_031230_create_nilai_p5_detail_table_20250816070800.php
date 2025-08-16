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
        Schema::create('nilai_p5_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nilai_p5_id')->constrained('nilai_p5')->onDelete('cascade');
            // $table->foreignId('kelas_siswa_id')->constrained('kelas_siswa')->onDelete('cascade');
            // $table->foreignId('siswa_id')->constrained('siswa')->onDelete('cascade');
            // $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');
            // $table->foreignId('tahun_semester_id')->constrained('tahun_semester')->onDelete('cascade');
            // $table->foreignId('p5_proyek_id')->constrained('p5_proyek')->onDelete('cascade');
            $table->foreignId('p5_sub_elemen_id')->constrained('p5_sub_elemen')->onDelete('cascade');
            $table->foreignId('p5_dimensi_id')->constrained('p5_dimensi')->onDelete('cascade');
            $table->enum('predikat', ['Sangat Baik', 'Baik', 'Cukup', 'Perlu Bimbingan'])->nullable();
            $table->text('deskripsi')->nullable();
            // $table->enum('periode', ['tengah', 'akhir'])->default('akhir'); // tengah = UTS, akhir = rapor akhir
            // $table->float('nilai_akhir')->nullable();
            // $table->boolean('is_validated')->default(false);
            $table->timestamps();

            $table->unique([
                'nilai_p5_id',
                // 'kelas_siswa_id',
                // 'siswa_id',
                // 'p5_proyek_id',
                'p5_sub_elemen_id',
                'p5_dimensi_id',
                'periode',
            ],
                'nilai_p5_unik'
            );
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

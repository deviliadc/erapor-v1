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
            $table->timestamps();

            $table->unique(['kelas_id', 'tanggal']); // Biar 1 kelas hanya 1 entri per hari
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presensi_harian');
    }
};

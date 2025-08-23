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
        Schema::create('pengaturan_rapor', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tahun_semester_id')->nullable()->constrained('tahun_semester')->nullOnDelete();
            $table->string('nama_kepala_sekolah');
            $table->string('nip_kepala_sekolah')->nullable();
            // $table->string('jabatan')->default('Kepala Sekolah');
            $table->string('ttd')->nullable();
            $table->string('tempat'); // misal: Bojonegoro
            $table->date('tanggal_cetak')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengaturan_rapor');
    }
};

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
        Schema::create('wali_murid', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->onDelete('cascade');
            $table->string('nama_ayah');
            $table->string('nama_ibu');
            $table->string('nama_wali')->nullable();
            $table->string('no_hp')->nullable();
            $table->string('pekerjaan_ayah');
            $table->string('pekerjaan_ibu');
            $table->string('pekerjaan_wali')->nullable();
            $table->string('alamat');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wali_murid');
    }
};

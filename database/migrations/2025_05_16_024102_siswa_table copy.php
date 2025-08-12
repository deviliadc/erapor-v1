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
    //     Schema::create('siswa', function (Blueprint $table) {
    //         $table->id();
    //         $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade');
    //         $table->foreignId('wali_murid_id')->nullable()->constrained('wali_murid')->onDelete('set null');
    //         $table->string('nis')->unique(); // Nomor Induk Siswa
    //         $table->string('nisn')->unique(); // Nomor Induk Siswa
    //         $table->string('nama');
    //         $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
    //         $table->string('tempat_lahir');
    //         $table->date('tanggal_lahir');
    //         $table->string('pendidikan_sebelumnya')->nullable();
    //         $table->text('alamat');
    //         $table->string('email')->nullable();
    //         $table->string('no_hp')->nullable();
    //         $table->enum('agama', ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu'])->default('Islam');
    //         $table->enum('status', ['Aktif', 'Lulus', 'Keluar', 'Mutasi'])->default('Aktif');
    //         $table->timestamps();
    //     });
    // }

    /**
     * Reverse the migrations.
     */
    // public function down(): void
    // {
    //     Schema::dropIfExists('siswa');
    // }
};

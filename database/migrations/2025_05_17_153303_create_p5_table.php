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
        // Schema::create('p5_tema', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('nama_tema');
        //     $table->text('deskripsi')->nullable();
        //     $table->timestamps();
        // });

        Schema::create('p5_dimensi', function (Blueprint $table) {
            $table->id();
            $table->string('nama_dimensi');
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });

        Schema::create('p5_elemen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('p5_dimensi_id')->constrained('p5_dimensi')->cascadeOnDelete();
            $table->string('nama_elemen');
            // $table->text('deskripsi')->nullable();
            $table->timestamps();
        });

        Schema::create('p5_sub_elemen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('p5_elemen_id')->constrained('p5_elemen')->cascadeOnDelete();
            $table->string('nama_sub_elemen');
            // $table->text('deskripsi')->nullable();
            // $table->text('capaian')->nullable();
            $table->timestamps();
        });

        Schema::create('p5_proyek', function (Blueprint $table) {
            $table->id();
            $table->string('nama_proyek');
            $table->text('deskripsi')->nullable();
            // $table->foreignId('kelas_id')->constrained('kelas');
            $table->foreignId('tahun_semester_id')->constrained('tahun_semester');
            // $table->foreignId('mapel_id')->nullable()->constrained('mapel');
            // $table->foreignId('guru_id')->nullable()->constrained('guru');
            // $table->foreignId('p5_tema_id')->constrained('p5_tema');
            // $table->string('status')->default('draft'); // Optional: add status field
            $table->timestamps();
        });

        Schema::create('p5_capaian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fase_id')->constrained('fase')->cascadeOnDelete();
            $table->foreignId('p5_sub_elemen_id')->constrained('p5_sub_elemen')->cascadeOnDelete();
            $table->text('capaian');
            $table->timestamps();
        });

        // Schema::create('p5_proyek_dimensi', function (Blueprint $table) {
        //     $table->id();
        //     $table->foreignId('p5_proyek_id')->constrained('p5_proyek')->cascadeOnDelete();
        //     $table->foreignId('p5_dimensi_id')->constrained('p5_dimensi')->cascadeOnDelete();
        //     $table->timestamps();
        // });

        // Schema::create('p5_proyek_elemen', function (Blueprint $table) {
        //     $table->id();
        //     $table->foreignId('p5_proyek_dimensi_id')->constrained('p5_proyek_dimensi')->cascadeOnDelete();
        //     $table->foreignId('p5_elemen_id')->constrained('p5_elemen')->cascadeOnDelete();
        //     $table->timestamps();
        // });

        // Schema::create('p5_proyek_sub_elemen', function (Blueprint $table) {
        //     $table->id();
        //     $table->foreignId('p5_proyek_elemen_id')->constrained('p5_proyek_elemen')->cascadeOnDelete();
        //     $table->foreignId('p5_sub_elemen_id')->constrained('p5_sub_elemen')->cascadeOnDelete();
        //     $table->timestamps();
        // });

        // Schema::create('p5_dokumentasi', function (Blueprint $table) {
        //     $table->id();
        //     $table->foreignId('p5_proyek_id')->constrained('p5_proyek')->cascadeOnDelete();
        //     $table->string('file_path');
        //     $table->string('keterangan')->nullable();
        //     $table->timestamps();
        // });

        Schema::create('p5_proyek_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('p5_proyek_id')->constrained('p5_proyek')->cascadeOnDelete();
            $table->foreignId('p5_dimensi_id')->constrained('p5_dimensi')->cascadeOnDelete();
            $table->foreignId('p5_elemen_id')->constrained('p5_elemen')->cascadeOnDelete();
            $table->foreignId('p5_sub_elemen_id')->constrained('p5_sub_elemen')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::dropIfExists('p5_dokumentasi');
        Schema::dropIfExists('p5_proyek_detail');
        // Schema::dropIfExists('p5_proyek_sub_elemen');
        // Schema::dropIfExists('p5_proyek_elemen');
        // Schema::dropIfExists('p5_proyek_dimensi');
        Schema::dropIfExists('p5_capaian');
        Schema::dropIfExists('p5_proyek');
        Schema::dropIfExists('p5_sub_elemen');
        Schema::dropIfExists('p5_elemen');
        Schema::dropIfExists('p5_dimensi');
        // Schema::dropIfExists('p5_tema');
    }
};

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
        // Schema::create('nilai_ekstra_detail', function (Blueprint $table) {
        //     // $table->id();
        //     // $table->foreignId('nilai_ekstra_id')->constrained('nilai_ekstra')->onDelete('cascade');
        //     // $table->foreignId('siswa_id')->constrained('siswa')->onDelete('cascade');
        //     // $table->string('jenis_nilai'); // e.g. spiritual, sosial, catatan
        //     // $table->text('nilai')->nullable(); // bisa berupa huruf (A, B, dll) atau teks catatan
        //     // $table->timestamps();

        //     // $table->unique(['nilai_ekstra_id', 'siswa_id', 'jenis_nilai'], 'nilai_ekstra_detail_unique');
        //     $table->id();
        //     $table->foreignId('nilai_ekstra_id')->constrained('nilai_ekstra')->onDelete('cascade');
        //     $table->foreignId('param_ekstra_id')->constrained('param_ekstra')->onDelete('cascade');
        //     // $table->string('nilai')->nullable(); // atau bisa enum: A, B, C, dst
        //     $table->enum('nilai', ['0', '1', '2', '3', '4'])->nullable();
        //     $table->enum('periode', ['tengah', 'akhir']);
        //     $table->boolean('is_validated')->default(false);
        //     $table->timestamps();

        //     $table->unique(['nilai_ekstra_id', 'parameter_ekstra_id']);
        // });

        Schema::create('nilai_ekstra_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nilai_ekstra_id')->constrained('nilai_ekstra')->onDelete('cascade');
            $table->foreignId('param_ekstra_id')->constrained('param_ekstra')->onDelete('cascade');
            $table->enum('nilai', ['0', '1', '2', '3', '4'])->nullable(); // sesuai skor penilaian
            $table->enum('periode', ['tengah', 'akhir']);
            $table->boolean('is_validated')->default(false);
            $table->timestamps();

            $table->unique([
                'nilai_ekstra_id',
                'param_ekstra_id',
                'periode'
            ], 'nilai_ekstra_detail_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilai_ekstra_detail');
    }
};

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
        Schema::create('tahun_ajaran', function (Blueprint $table) {
            $table->id();
            $table->string('tahun'); // Contoh: 2024/2025
            // $table->date('mulai');  // 2025-07-01
            // $table->date('selesai'); // 2026-06-30
            $table->boolean('is_active')->default(false);
            $table->timestamps();

                $table->unique('tahun'); 
        });

        Schema::create('tahun_semester', function (Blueprint $table) {
            $table->id();
            // $table->string('tahun'); // Contoh: 2024/2025
            $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajaran')->onDelete('cascade');
            $table->enum('semester', ['Ganjil', 'Genap']);
            // $table->date('mulai');  // 2025-07-01
            // $table->date('selesai'); // 2025-12-31
            $table->boolean('is_active')->default(false);
            // $table->boolean('is_validated_uts')->default(false);   // validasi rapor tengah semester (UTS)
            // $table->boolean('is_validated_uas')->default(false);   // validasi rapor akhir semester (UAS)
            $table->timestamps();

            $table->unique(['tahun_ajaran_id', 'semester']);
            // $table->unique(['is_active'], 'unique_active_semester')->where('is_active', true);
            // $table->unique(['tahun', 'semester']); // mencegah duplikasi
            // $table->unique(['tahun_ajaran_id', 'is_active'], 'unique_active_per_tahun')->where('is_active', true);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tahun_semester');
        Schema::dropIfExists('tahun_ajaran');
    }
};

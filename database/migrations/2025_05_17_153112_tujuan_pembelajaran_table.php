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
        Schema::create('tujuan_pembelajaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lingkup_materi_id')->constrained('lingkup_materi')->cascadeOnDelete();
            // $table->foreignId('tahun_semester_id')->nullable()->constrained('tahun_semester')->nullOnDelete();
            $table->string('subbab')->unique();
            $table->text('tujuan');
            // $table->enum('periode', ['tengah', 'akhir']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tujuan_pembelajaran');
    }
};

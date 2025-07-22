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
        Schema::create('lingkup_materi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guru_kelas_id')->constrained()->onDelete('cascade');
            $table->foreignId('bab_id')->nullable()->constrained('bab')->nullOnDelete();
            $table->string('nama');
            $table->enum('periode', ['tengah', 'akhir'])->default('tengah');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lingkup_materi');
    }
};

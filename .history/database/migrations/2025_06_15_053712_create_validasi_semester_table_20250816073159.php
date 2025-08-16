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
        Schema::create('validasi_semester', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tahun_semester_id')->constrained();
            $table->enum('tipe', ['UTS', 'UAS', 'P5', 'Ekstra', 'Presensi']);
            $table->boolean('is_validated')->default(false);
            $table->timestamp('validated_at')->nullable();
            $table->foreignId('validated_by')->nullable()->constrained('users');
            $table->timestamps();

            $table->unique(['tahun_semester_id', 'tipe']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('validasi_semester');
    }
};

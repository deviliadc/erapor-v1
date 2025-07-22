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
        Schema::create('des_mapel', function (Blueprint $table) {
            $table->id();
            $table->integer('nilai_min');
            $table->integer('nilai_max');
            $table->text('des_min');
            $table->text('des_max');
            $table->foreignId('mapel_id')->constrained('mapel')->onDelete('cascade');
            $table->timestamps(); // opsional: menambah created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('des_mapel');
    }
};

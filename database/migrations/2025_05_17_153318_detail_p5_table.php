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
        Schema::create('detail_p5', function (Blueprint $table) {
            $table->id();
            $table->string('dimensi');
            $table->string('elemen');
            $table->string('subelemen');
            $table->foreignId('p5_id')->constrained('p5')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_p5');
    }
};

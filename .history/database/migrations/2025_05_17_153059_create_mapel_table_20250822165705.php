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
        Schema::create('mapel', function (Blueprint $table) {
            $table->id();
            $table->string('kode_mapel')->unique(); // Misal: MTK01
            $table->string('nama'); // Misal: Matematika
            $table->enum('kategori', ['Wajib', 'Muatan Lokal'])->default('Wajib'); // Wajib/Muatan Lokal
            // $table->enum('agama', ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu'])
            //     ->nullable(); // hanya diisi kalau mapel agama
             $table->unsignedTinyInteger('urutan')->nullable()->default(0); // Urutan mapel dalam kelas
           
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mapel');
    }
};

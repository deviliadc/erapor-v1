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
        Schema::create('pengumuman', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('isi');
            $table->string('lampiran')->nullable(); // opsional: file seperti PDF, gambar
            $table->date('tanggal_mulai'); // pengumuman mulai ditampilkan
            $table->date('tanggal_berakhir')->nullable(); // setelah ini dianggap diarsip
            $table->foreignId('dibuat_oleh')->constrained('users')->onDelete('cascade');

            // opsional: ditujukan untuk role tertentu (admin, guru, wali, siswa)
            $table->enum('ditujukan_ke', ['semua', 'admin', 'guru', 'wali', 'siswa'])->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengumuman');
    }
};

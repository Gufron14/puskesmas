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
        Schema::create('pemeriksaans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pasien_id')->nullable(); // Hanya kolom, tanpa constraint
            $table->unsignedBigInteger('obat_id')->nullable()->constrained('obats')->nullOnDelete();
            $table->dateTime('tanggal_pemeriksaan')->nullable();
            $table->integer('tensi_sistolik')->default(0);
            $table->integer('tensi_diastolik')->default(0);
            $table->text('gejala')->nullable();
            $table->text('catatan_dokter')->nullable();
            $table->json('resep_obat')->nullable(); // Simpan array obat
            $table->integer('biaya')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemeriksaans');
    }
};

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
            $table->foreignId('pasien_id')->constrained()->onDelete('cascade');
            $table->dateTime('tanggal_pemeriksaan');
            $table->integer('tensi_sistolik')->default(0);
            $table->integer('tensi_diastolik')->default(0);
            $table->text('gejala')->nullable();
            $table->text('catatan_dokter')->nullable();
            $table->json('resep_obat')->nullable(); // Simpan array obat
            $table->decimal('biaya', 10, 2)->default(0);
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

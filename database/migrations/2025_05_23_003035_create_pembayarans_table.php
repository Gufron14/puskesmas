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
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pemeriksaan_id')->nullable();
            $table->string('metode');
            $table->decimal('biaya_pemeriksaan', 10, 2)->default(0);
            $table->decimal('total_obat', 10, 2)->default(0);
            $table->decimal('total_tagihan', 10, 2)->default(0);
            $table->decimal('jumlah_bayar', 10, 2)->default(0);
            $table->decimal('kembalian', 10, 2)->default(0);
            $table->timestamps();
            $table->foreign('pemeriksaan_id')->references('id')->on('pemeriksaans')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayarans');
    }
};

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
        Schema::create('pasiens', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pasien');
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->integer('usia');
            $table->string('nik', 16)->unique();
            $table->text('alamat')->nullable();
            $table->string('telepon')->nullable();
            $table->date('tanggal_antrian')->nullable();
            $table->integer('nomor_antrian')->nullable();
             $table->string('status')->default('menunggu');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pasiens');
    }
};

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
        Schema::table('obats', function (Blueprint $table) {
                    $table->unsignedBigInteger('jenis_obat_id')->after('nama');
        $table->foreign('jenis_obat_id')->references('id')->on('jenis_obats');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('obats', function (Blueprint $table) {
                    $table->dropForeign(['jenis_obat_id']);
        $table->dropColumn('jenis_obat_id');
        });
    }
};

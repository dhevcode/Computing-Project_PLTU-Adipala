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
        Schema::table('mahasiswa', function (Blueprint $table) {
            $table->foreign(['jurusan_id'], 'fk_mahasiswa_jurusan')->references(['id_jurusan'])->on('jurusan')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign(['universitas_id'], 'fk_mahasiswa_universitas')->references(['id_universitas'])->on('universitas')->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mahasiswa', function (Blueprint $table) {
            $table->dropForeign('fk_mahasiswa_jurusan');
            $table->dropForeign('fk_mahasiswa_universitas');
        });
    }
};

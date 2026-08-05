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
        Schema::create('mahasiswa', function (Blueprint $table) {
            $table->bigIncrements('id_mhs');
            $table->string('nim', 30)->unique('nim');
            $table->string('nama_lengkap', 150);
            $table->string('email', 150);
            $table->string('no_telepon', 20);
            $table->unsignedBigInteger('universitas_id')->index('fk_mahasiswa_universitas');
            $table->unsignedBigInteger('jurusan_id')->index('fk_mahasiswa_jurusan');
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable()->useCurrent();

            $table->index(['nama_lengkap', 'nim'], 'idx_mahasiswa_search');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mahasiswa');
    }
};

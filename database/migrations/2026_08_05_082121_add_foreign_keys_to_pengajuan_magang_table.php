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
        Schema::table('pengajuan_magang', function (Blueprint $table) {
            $table->foreign(['created_by'], 'fk_pengajuan_admin')->references(['id_users'])->on('users')->onUpdate('cascade')->onDelete('set null');
            $table->foreign(['divisi_id'], 'fk_pengajuan_divisi')->references(['id_divisi'])->on('divisi')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign(['mahasiswa_id'], 'fk_pengajuan_mahasiswa')->references(['id_mhs'])->on('mahasiswa')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengajuan_magang', function (Blueprint $table) {
            $table->dropForeign('fk_pengajuan_admin');
            $table->dropForeign('fk_pengajuan_divisi');
            $table->dropForeign('fk_pengajuan_mahasiswa');
        });
    }
};

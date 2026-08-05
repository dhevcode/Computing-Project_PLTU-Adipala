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
        Schema::create('pengajuan_magang', function (Blueprint $table) {
            $table->bigIncrements('id_pengajuan');
            $table->unsignedBigInteger('mahasiswa_id')->index('fk_pengajuan_mahasiswa');
            $table->unsignedBigInteger('divisi_id')->index('fk_pengajuan_divisi');
            $table->string('nama_pembimbing', 150)->nullable();
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->enum('status', ['Akan Magang', 'Sedang Magang', 'Selesai Magang'])->default('Akan Magang');
            $table->string('surat_permohonan')->nullable()->comment('Path/Link File Surat PDF');
            $table->unsignedBigInteger('created_by')->nullable()->index('fk_pengajuan_admin')->comment('Admin yang meng-ACC/menginput data ini');
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable()->useCurrent();

            $table->index(['status', 'tanggal_mulai', 'tanggal_selesai'], 'idx_magang_filter');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_magang');
    }
};

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
        Schema::create('sertifikat', function (Blueprint $table) {
            $table->bigIncrements('id_sertifikat');
            $table->unsignedBigInteger('mahasiswa_id');
            $table->unsignedBigInteger('pengajuan_id');
            $table->string('file_sertifikat');
            $table->string('status', 50)->default('Diterbitkan');
            $table->date('tanggal_terbit')->nullable();
            $table->unsignedBigInteger('uploaded_by')->nullable();
            $table->timestamp('uploaded_at')->nullable();
            $table->timestamps();

            $table->foreign('mahasiswa_id')->references('id_mhs')->on('mahasiswa')->onDelete('cascade');
            $table->foreign('pengajuan_id')->references('id_pengajuan')->on('pengajuan_magang')->onDelete('cascade');
            $table->foreign('uploaded_by')->references('id_users')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sertifikat');
    }
};

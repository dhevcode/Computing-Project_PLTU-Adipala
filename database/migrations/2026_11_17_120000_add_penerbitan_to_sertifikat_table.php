<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Data penerbitan sertifikat otomatis dari Penilaian Akhir (template sertifikat 2 halaman):
 * nomor sertifikat, kota terbit, dan penandatangan yang diisi admin saat menerbitkan.
 * Sertifikat yang diunggah manual tetap memakai kolom lama (kolom baru boleh kosong).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sertifikat', function (Blueprint $table) {
            $table->string('nomor_sertifikat', 100)->nullable()->after('pengajuan_id');
            $table->string('kota_terbit', 100)->nullable()->after('tanggal_terbit');
            $table->string('penandatangan_jabatan', 150)->nullable()->after('kota_terbit');
            $table->string('penandatangan_nama', 150)->nullable()->after('penandatangan_jabatan');
            $table->string('penandatangan_identitas', 150)->nullable()->after('penandatangan_nama');
            $table->string('sumber', 20)->default('upload')->after('status'); // upload | generate
        });
    }

    public function down(): void
    {
        Schema::table('sertifikat', function (Blueprint $table) {
            $table->dropColumn([
                'nomor_sertifikat',
                'kota_terbit',
                'penandatangan_jabatan',
                'penandatangan_nama',
                'penandatangan_identitas',
                'sumber',
            ]);
        });
    }
};

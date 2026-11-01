<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Modifikasi ENUM status untuk menyertakan 'Menunggu Verifikasi' dan 'Ditolak'
        DB::statement("ALTER TABLE pengajuan_magang MODIFY COLUMN status ENUM('Menunggu Verifikasi', 'Akan Magang', 'Sedang Magang', 'Selesai Magang', 'Ditolak') NOT NULL DEFAULT 'Menunggu Verifikasi'");

        // 2. Tambah kolom pendukung approval / penolakan
        Schema::table('pengajuan_magang', function (Blueprint $table) {
            if (!Schema::hasColumn('pengajuan_magang', 'alasan_penolakan')) {
                $table->text('alasan_penolakan')->nullable()->after('surat_balasan')->comment('Alasan jika pendaftaran ditolak oleh Admin');
            }

            if (!Schema::hasColumn('pengajuan_magang', 'catatan_admin')) {
                $table->text('catatan_admin')->nullable()->after('alasan_penolakan')->comment('Catatan penerimaan atau arahan magang dari Admin');
            }

            if (!Schema::hasColumn('pengajuan_magang', 'verified_at')) {
                $table->timestamp('verified_at')->nullable()->after('catatan_admin')->comment('Waktu verifikasi/penerimaan/penolakan');
            }

            if (!Schema::hasColumn('pengajuan_magang', 'verified_by')) {
                $table->unsignedBigInteger('verified_by')->nullable()->after('verified_at')->comment('Admin yang melakukan verifikasi');
                $table->foreign('verified_by')->references('id_users')->on('users')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengajuan_magang', function (Blueprint $table) {
            if (Schema::hasColumn('pengajuan_magang', 'verified_by')) {
                $table->dropForeign(['verified_by']);
                $table->dropColumn('verified_by');
            }
            if (Schema::hasColumn('pengajuan_magang', 'verified_at')) {
                $table->dropColumn('verified_at');
            }
            if (Schema::hasColumn('pengajuan_magang', 'catatan_admin')) {
                $table->dropColumn('catatan_admin');
            }
            if (Schema::hasColumn('pengajuan_magang', 'alasan_penolakan')) {
                $table->dropColumn('alasan_penolakan');
            }
        });

        DB::statement("ALTER TABLE pengajuan_magang MODIFY COLUMN status ENUM('Akan Magang', 'Sedang Magang', 'Selesai Magang') NOT NULL DEFAULT 'Akan Magang'");
    }
};

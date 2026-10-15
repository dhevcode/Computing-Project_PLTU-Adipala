<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengajuan_magang', function (Blueprint $table) {
            if (!Schema::hasColumn('pengajuan_magang', 'proposal')) {
                $table->string('proposal')->nullable()->after('surat_permohonan');
            }
            if (!Schema::hasColumn('pengajuan_magang', 'cv')) {
                $table->string('cv')->nullable()->after('proposal');
            }
            if (!Schema::hasColumn('pengajuan_magang', 'surat_balasan')) {
                $table->string('surat_balasan')->nullable()->after('cv');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pengajuan_magang', function (Blueprint $table) {
            if (Schema::hasColumn('pengajuan_magang', 'surat_balasan')) {
                $table->dropColumn('surat_balasan');
            }
            if (Schema::hasColumn('pengajuan_magang', 'cv')) {
                $table->dropColumn('cv');
            }
            if (Schema::hasColumn('pengajuan_magang', 'proposal')) {
                $table->dropColumn('proposal');
            }
        });
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengajuan_magang', function (Blueprint $table) {
            $table->string('surat_balasan')->nullable()->after('cv');
        });
    }

    public function down(): void
    {
        Schema::table('pengajuan_magang', function (Blueprint $table) {
            $table->dropColumn('surat_balasan');
        });
    }
};
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
            if (!Schema::hasColumn('pengajuan_magang', 'pembimbing_id')) {
                $table->unsignedBigInteger('pembimbing_id')->nullable()->after('nama_pembimbing')->index('fk_pengajuan_pembimbing');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengajuan_magang', function (Blueprint $table) {
            if (Schema::hasColumn('pengajuan_magang', 'pembimbing_id')) {
                $table->dropColumn('pembimbing_id');
            }
        });
    }
};

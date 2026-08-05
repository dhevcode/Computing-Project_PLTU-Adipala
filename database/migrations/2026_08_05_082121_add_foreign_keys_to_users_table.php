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
        Schema::table('users', function (Blueprint $table) {
            $table->foreign(['created_by'], 'fk_users_created_by')->references(['id_users'])->on('users')->onUpdate('cascade')->onDelete('set null');
            $table->foreign(['id_divisi'], 'fk_users_divisi')->references(['id_divisi'])->on('divisi')->onUpdate('restrict')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('fk_users_created_by');
            $table->dropForeign('fk_users_divisi');
        });
    }
};

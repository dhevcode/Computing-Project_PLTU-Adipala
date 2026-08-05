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
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id_users');
            $table->unsignedBigInteger('id_divisi')->nullable()->index('fk_users_divisi');
            $table->string('nama_lengkap', 150);
            $table->string('username', 150)->unique('username');
            $table->string('email', 150)->unique('email');
            $table->string('password');
            $table->enum('role', ['Super Admin', 'Admin'])->default('Admin');
            $table->boolean('is_active')->default(true)->comment('1 = Aktif, 0 = Nonaktif');
            $table->unsignedBigInteger('created_by')->nullable()->index('fk_users_created_by')->comment('ID Super Admin yang membuat akun ini');
            $table->timestamp('last_login_at')->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable()->useCurrent();

            $table->index(['username', 'email', 'is_active'], 'idx_user_auth');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

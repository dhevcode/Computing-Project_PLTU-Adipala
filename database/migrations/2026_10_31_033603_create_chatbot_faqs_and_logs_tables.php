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
        // 1. Tabel FAQ Chatbot
        Schema::create('chatbot_faqs', function (Blueprint $table) {
            $table->bigIncrements('id_faq');
            $table->string('kode', 20)->unique()->comment('Contoh: FAQ-01, FAQ-02');
            $table->string('pertanyaan', 255);
            $table->text('jawaban');
            $table->string('kategori', 50)->default('Umum')->comment('Umum, Pendaftaran, Pelaksanaan, Aturan, Kontak');
            $table->enum('role_access', ['all', 'guest', 'student'])->default('all')->comment('all = Guest & Student, guest = Hanya Guest, student = Hanya Student');
            $table->integer('urutan')->default(1);
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('click_count')->default(0);
            $table->timestamps();

            $table->index(['role_access', 'is_active', 'urutan'], 'idx_faq_filter');
        });

        // 2. Tabel Log Riwayat Percakapan / Klik FAQ
        Schema::create('chatbot_logs', function (Blueprint $table) {
            $table->bigIncrements('id_log');
            $table->unsignedBigInteger('faq_id')->nullable()->index('fk_log_faq');
            $table->unsignedBigInteger('user_id')->nullable()->index('fk_log_user');
            $table->string('user_type', 20)->default('guest')->comment('guest, student, admin');
            $table->string('pertanyaan', 255);
            $table->string('session_id', 100)->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();

            $table->foreign('faq_id')->references('id_faq')->on('chatbot_faqs')->onDelete('set null');
            $table->foreign('user_id')->references('id_users')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chatbot_logs');
        Schema::dropIfExists('chatbot_faqs');
    }
};

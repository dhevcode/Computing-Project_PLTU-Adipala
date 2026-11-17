<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Media sosial perusahaan di Info Perusahaan (masukan PLTU, meet 9 Nov): Instagram, YouTube, Facebook.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->string('instagram_url', 255)->nullable()->after('website');
            $table->string('youtube_url', 255)->nullable()->after('instagram_url');
            $table->string('facebook_url', 255)->nullable()->after('youtube_url');
        });
    }

    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn(['instagram_url', 'youtube_url', 'facebook_url']);
        });
    }
};

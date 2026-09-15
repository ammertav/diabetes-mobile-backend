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
        Schema::table('cms_contents', function (Blueprint $table) {
            $table->string('media_type')->default('image')->after('body');
            $table->string('media_url')->nullable()->after('media_type');
            $table->string('youtube_id')->nullable()->after('media_url');
            $table->string('thumbnail_url')->nullable()->after('youtube_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cms_contents', function (Blueprint $table) {
            $table->dropColumn(['media_type', 'media_url', 'youtube_id', 'thumbnail_url']);
        });
    }
};

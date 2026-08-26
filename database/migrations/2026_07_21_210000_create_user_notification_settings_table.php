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
        Schema::create('user_notification_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('user_id')->constrained()->onDelete('cascade');
            $table->boolean('niat_puasa_enabled')->default(true);
            $table->string('niat_puasa_time')->default('20:00');
            $table->boolean('sahur_enabled')->default(true);
            $table->string('sahur_time')->default('03:30');
            $table->boolean('fbg_reminder_enabled')->default(true);
            $table->string('fbg_reminder_time')->default('17:45');
            $table->boolean('motivation_enabled')->default(true);
            $table->timestamps();

            $table->unique('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_notification_settings');
    }
};

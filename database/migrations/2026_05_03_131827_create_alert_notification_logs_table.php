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
        Schema::create('alert_notification_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('safety_alert_id')->constrained()->cascadeOnDelete();
            $table->string('fcm_token');
            $table->timestamp('sent_at')->nullable();
            $table->string('status')->nullable(); // success / failed
            $table->text('error')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alert_notification_logs');
    }
};

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
        Schema::create('fgb_records', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained()->onDelete('cascade');
            $table->foreignUuid('user_protocol_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignUuid('fasting_log_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('value_mg_dl', 5, 1);
            $table->string('context_tag')->index();
            $table->boolean('is_fasting_day')->default(false);
            $table->timestamp('client_timestamp');
            $table->timestamp('server_timestamp');
            $table->timestamps();

            $table->index(['user_id', 'server_timestamp']);
            $table->index(['user_id', 'context_tag']);
            $table->index(['user_id', 'is_fasting_day']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fgb_records');
    }
};

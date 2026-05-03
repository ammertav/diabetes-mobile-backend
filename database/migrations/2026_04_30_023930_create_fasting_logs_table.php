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
        Schema::create('fasting_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_protocol_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->date('planned_date');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();

            $table->string('status')->default('planned');

            $table->string('mood')->nullable();
            $table->string('skip_reason')->nullable();
            $table->string('notes', 500)->nullable();
            $table->integer('actual_duration_min')->nullable();

            $table->timestamp('confirmed_at')->nullable();
            $table->timestamps();

            $table->unique(['user_protocol_id', 'planned_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fasting_logs');
    }
};

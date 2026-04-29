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
        Schema::create('fasting_protocol_days', function (Blueprint $table) {
            $table->id()->primary();
            $table->foreignUuid('fasting_protocol_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->unsignedTinyInteger('day');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fasting_protocol_days');
    }
};

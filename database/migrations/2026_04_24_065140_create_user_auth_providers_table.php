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
        Schema::create('user_auth_providers', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('user_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->enum('provider', ['email', 'google', 'apple']);
            $table->string('provider_id')->nullable();
            $table->string('password_hash')->nullable();
            $table->timestamps();

            $table->unique(['provider', 'provider_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_auth_providers');
    }
};

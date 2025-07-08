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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('website_id')->constrained()->onDelete('cascade');
            $table->uuid('session_id');
            $table->string('event_type');
            $table->string('url')->nullable();
            $table->string('referrer')->nullable();
            $table->string('user_agent')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->json('payload')->nullable();
            $table->string('country_code', 2)->nullable();
            $table->string('country_name', 100)->nullable();
            $table->json('extra')->nullable();
            $table->timestamps();

            $table->index(['website_id', 'event_type']);
            $table->index(['website_id', 'session_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};

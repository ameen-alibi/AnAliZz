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
        Schema::create('metrics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('website_id')->constrained()->onDelete('cascade');
            $table->date('metric_date');
            $table->bigInteger('pageviews')->default(0);
            $table->bigInteger('unique_visitors')->default(0);
            $table->bigInteger('sessions')->default(0);
            $table->decimal('bounce_rate', 5, 2)->nullable();
            $table->integer('avg_session_duration')->nullable();
            $table->timestamps();

            $table->unique(['website_id', 'metric_date']);
            $table->index('website_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('metrics');
    }
};

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
        Schema::create('checkins', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->unsignedBigInteger('agent_id');
            $table->foreignId('campaign_id')->nullable();
            $table->nullableMorphs('person');
            $table->point('location')->nullable();
            $table->string('url', 2048)->nullable();
            $table->json('data')->nullable();
            $table->foreign('agent_id')->references('id')->on('users');
            $table->timestamp('data_retrieved_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('checkins');
    }
};

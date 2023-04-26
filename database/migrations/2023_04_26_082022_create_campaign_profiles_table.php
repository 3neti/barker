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
        Schema::create('campaign_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained()->cascadeOnDelete();
            $table->string('profile'); //Gender, Age, Complexion, Height
            $table->json('options'); //"Male" | "Female"; "Young" | "Middle-Aged" | "Old"; "Dark-Skinned" | "Brown-Skinned" | "Fair-Skinned";  "Short" | "Average Height" | "Tall"
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaign_profiles');
    }
};

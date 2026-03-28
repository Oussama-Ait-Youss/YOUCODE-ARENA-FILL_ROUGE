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
        Schema::create('competitor_profiles', function (Blueprint $table) {
            // $table->id();
            $table->foreignId('user_id')->primary()->constrained('users')->cascadeOnDelete();
            $table->integer('games_won')->default(0);
            $table->integer('games_loss')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('competitor_profiles');
    }
};

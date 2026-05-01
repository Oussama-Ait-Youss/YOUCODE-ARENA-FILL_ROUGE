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
    Schema::create('matches', function (Blueprint $table) {
        $table->id();
        $table->foreignId('tournament_id')->constrained()->cascadeOnDelete();
        $table->foreignId('team1_id')->nullable()->constrained('teams')->nullOnDelete();
        $table->foreignId('team2_id')->nullable()->constrained('teams')->nullOnDelete();

        $table->foreignId('winner_team_id')->nullable()->constrained('teams')->nullOnDelete();
        $table->string('score', 50)->nullable(); 
        
        $table->string('status')->default('Programmé'); 
        $table->dateTime('played_at')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matches');
    }
};

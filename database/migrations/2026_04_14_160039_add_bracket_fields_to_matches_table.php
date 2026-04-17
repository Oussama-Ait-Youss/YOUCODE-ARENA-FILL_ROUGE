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
        Schema::table('matches', function (Blueprint $table) {
            $table->foreignId('next_match_id')->nullable()->constrained('matches')->nullOnDelete();
            $table->integer('round')->default(1);
            $table->integer('position_in_round')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('matches', function (Blueprint $table) {
            $table->dropForeign(['next_match_id']);
            $table->dropColumn(['next_match_id', 'round', 'position_in_round']);
        });
    }
};

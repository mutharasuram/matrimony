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
        Schema::table('profile', function (Blueprint $table) {
            $table->string('preferred_citizenship')->nullable();
            $table->string('eating_habit')->nullable();
            $table->string('drinking_habit')->nullable();
            $table->string('smoking_habit')->nullable();
            $table->text('hobbies_and_interests')->nullable();
            $table->text('music')->nullable();
            $table->text('sports')->nullable();
            $table->text('food')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profile', function (Blueprint $table) {
            $table->dropColumn(['preferred_citizenship', 'eating_habit', 'drinking_habit', 'smoking_habit', 'hobbies_and_interests', 'music', 'sports', 'food']);
        });
    }
};

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
            $table->string('preferred_eating_habit')->nullable();
            $table->string('preferred_drinking_habit')->nullable();
            $table->string('preferred_smoking_habit')->nullable();
            $table->text('preferred_hobbies_and_interests')->nullable();
            $table->text('preferred_music')->nullable();
            $table->text('preferred_sports')->nullable();
            $table->text('preferred_food')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profile', function (Blueprint $table) {
            $table->dropColumn('preferred_eating_habit');
            $table->dropColumn('preferred_drinking_habit');
            $table->dropColumn('preferred_smoking_habit');
            $table->dropColumn('preferred_hobbies_and_interests');
            $table->dropColumn('preferred_music');
            $table->dropColumn('preferred_sports');
            $table->dropColumn('preferred_food');
        });
    }
};

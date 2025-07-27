<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('profile', function (Blueprint $table) {
            $table->string('education_category')->nullable();
            $table->string('habit')->nullable();
            $table->boolean('isEligible')->nullable()->default(false);
            $table->string('income')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('profile', function (Blueprint $table) {
            $table->dropColumn(['education_category', 'habit', 'isEligible', 'income']);
        });
    }
};

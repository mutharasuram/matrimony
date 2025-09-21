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
            // Family Details Fields
            $table->string('father_occupation')->nullable();
            $table->string('mother_occupation')->nullable();
            $table->integer('no_of_brothers')->nullable();
            $table->integer('no_of_sisters')->nullable();
            
            // Partner Preference Fields
            $table->string('preferred_age_min')->nullable();
            $table->string('preferred_age_max')->nullable();
            $table->string('preferred_height_min')->nullable();
            $table->string('preferred_height_max')->nullable();
            $table->string('preferred_marital_status')->nullable();
            $table->string('preferred_physical_status')->nullable();
            $table->string('preferred_mother_tongue')->nullable();
            $table->string('preferred_subcaste')->nullable();
            $table->string('preferred_chevvai_dosham')->nullable();
            $table->string('preferred_education')->nullable();
            $table->string('preferred_employed_in')->nullable();
            $table->string('preferred_occupation')->nullable();
            $table->string('preferred_annual_income_min')->nullable();
            $table->string('preferred_annual_income_max')->nullable();
            $table->string('preferred_country')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profile', function (Blueprint $table) {
            // Family Details Fields
            $table->dropColumn([
                'father_occupation',
                'mother_occupation',
                'no_of_brothers',
                'no_of_sisters'
            ]);
            
            // Partner Preference Fields
            $table->dropColumn([
                'preferred_age_min',
                'preferred_age_max',
                'preferred_height_min',
                'preferred_height_max',
                'preferred_marital_status',
                'preferred_physical_status',
                'preferred_mother_tongue',
                'preferred_subcaste',
                'preferred_chevvai_dosham',
                'preferred_education',
                'preferred_employed_in',
                'preferred_occupation',
                'preferred_annual_income_min',
                'preferred_annual_income_max',
                'preferred_country'
            ]);
        });
    }
};

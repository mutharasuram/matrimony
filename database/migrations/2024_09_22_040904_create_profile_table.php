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
        Schema::create('profile', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('profile_created_by', ['self', 'parent', 'sibling', 'relative', 'friend']);
            $table->enum('gender', ['male', 'female']);
            $table->string('name');
            $table->date('dob');
            $table->string('mother_tongue');
            $table->string('subcaste')->nullable();
            $table->enum('willing_to_marry_from_subcaste', ['yes', 'no']);
            $table->enum('marital_status', ['Unmarried', 'Widower', 'Divorced', 'Separated']);
            $table->string('country_living_in');
            $table->string('residing_state');
            $table->string('residing_city');
            $table->string('citizenship');
            $table->string('height');
            $table->string('education');
            $table->string('employed_in')->nullable();
            $table->string('occupation')->nullable();
            $table->string('annual_income')->nullable();
            $table->enum('physical_status', ['normal', 'physically_challenged']);
            $table->enum('family_status', ['middle_class', 'upper_middle class', 'rich_affluent']);
            $table->enum('family_type', ['joint_family', 'nuclear_family']);
            $table->text('about_me')->nullable();
            $table->enum('dosham', ['yes', 'no', 'donot_know']);
            $table->string('star_nakshatram')->nullable();
            $table->string('rasi')->nullable();
            $table->string('gothram')->nullable();
            $table->time('time_of_birth')->nullable();
            $table->string('country_of_birth')->nullable();
            $table->string('state_of_birth')->nullable();
            $table->string('city_of_birth')->nullable();
            $table->string('horoscope_chart_style')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profile');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('profile', function (Blueprint $table) {
            $table->string('about_my_family')->nullable();
            $table->string('fewlines_about_my_partner')->nullable();
        });
        
        // Update family_status enum to include new values
        DB::statement("ALTER TABLE profile MODIFY COLUMN family_status ENUM('poor', 'lower_middle', 'middle_class', 'upper_middle class', 'rich_affluent')");
        
        // Update family_type enum to include new value
        DB::statement("ALTER TABLE profile MODIFY COLUMN family_type ENUM('joint_family', 'nuclear_family', 'small_family')");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profile', function (Blueprint $table) {
            $table->dropColumn('about_my_family');
            $table->dropColumn('fewlines_about_my_partner');
        });
        
        // Revert family_status enum to original values
        DB::statement("ALTER TABLE profile MODIFY COLUMN family_status ENUM('middle_class', 'upper_middle class', 'rich_affluent')");
        
        // Revert family_type enum to original values
        DB::statement("ALTER TABLE profile MODIFY COLUMN family_type ENUM('joint_family', 'nuclear_family')");
    }
};

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
        // Create the profile_img table
        Schema::create('profile_img', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('img_path'); // Path to the image
            $table->timestamps(); // Created at and updated at timestamps
        });

        // Add the profile_img_id column to the profiles table
        Schema::table('profile', function (Blueprint $table) {
            $table->unsignedBigInteger('profile_img_id')->nullable()->after('id'); // New column
            $table->foreign('profile_img_id') // Foreign key
                ->references('id')
                ->on('profile_img')
                ->onDelete('cascade') // Cascade on delete
                ->onUpdate('cascade'); // Cascade on update
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove the foreign key and column from profiles table
        Schema::table('profile', function (Blueprint $table) {
            $table->dropForeign(['profile_img_id']); // Drop foreign key
            $table->dropColumn('profile_img_id'); // Drop column
        });

        // Drop the profile_img table
        Schema::dropIfExists('profile_img');
    }
};

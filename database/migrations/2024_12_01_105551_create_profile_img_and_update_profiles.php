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
            $table->unsignedBigInteger('profile_id'); // Foreign key to profiles table
            $table->string('img_path'); // Path to the image
            $table->timestamps(); // Created at and updated at timestamps

            // Foreign key constraint with cascading behavior
            $table->foreign('profile_id')
                ->references('id')
                ->on('profile')
                ->onDelete('cascade') // Delete images if the profile is deleted
                ->onUpdate('cascade'); // Update on profile ID change
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the profile_img table
        Schema::dropIfExists('profile_img');
    }
};

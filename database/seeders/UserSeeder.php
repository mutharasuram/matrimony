<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Profile;

class UserSeeder extends Seeder
{
    public function run()
    {

        Profile::factory()
            ->count(30)
            ->create();
    }
}

// executed command
// php artisan db:seed --class=UserSeeder

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@matrimony.com',
            'password' => Hash::make('admin123'),
            'mobile' => '1234567890',
            'm_id' => 'mv' . time() . rand(1000, 9999),
            'is_admin' => true,
        ]);

        $this->command->info('Admin user created successfully!');
        $this->command->info('Email: admin@matrimony.com');
        $this->command->info('Password: admin123');
    }
} 
<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plainPassword = 'password123';

        $user = User::create([
            'name' => 'Administrator',
            'role' => 'admin',
            'email' => 'superadmin@example.com',
            'password' => Hash::make($plainPassword),
        ]);
        
        $user = User::create([
            'name' => 'Administrator',
            'role' => 'admin',
            'email' => 'admin@example.com',
            'password' => Hash::make($plainPassword),
        ]);

        $this->command->info('Admin user created successfully.');
        $this->command->info('Email: ' . $user->email);
        $this->command->info('Password: ' . $plainPassword);
    }
}
<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class RandomCustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 1; $i <= 20; $i++) {

            User::create([
                'name' => $faker->name,

                'email' => $faker->unique()->safeEmail,

                'email_verified_at' => now(),

                'password' => Hash::make('password'),

                'type' => 'client',

                'is_active' => $faker->randomElement([
                    'active',
                    'inactive',
                    'deactivated',
                ]),

                'last_login_at' => $faker->dateTimeBetween('-1 year', 'now'),

                'login_count' => $faker->numberBetween(0, 100),

                'role' => 'customer',

                'first_name' => $faker->firstName,

                'last_name' => $faker->lastName,

                'phone' => $faker->phoneNumber,

                'date_of_birth' => $faker->date(
                    'Y-m-d',
                    '-18 years'
                ),

                'gender' => $faker->randomElement([
                    'Male',
                    'Female',
                    'Other'
                ]),

                'avatar' => null,

                'bio' => $faker->paragraph,

                'language' => 'en',

                'currency' => 'INR',

                'last_login_ip' => $faker->ipv4,
            ]);
        }
        $this->command->info('Random customer Seeding completed!.');
    }
}
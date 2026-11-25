<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            EmailTemplateSeeder::class,
            MenuSeeder::class,
            PageContentSeeder::class,
            PolicySeeder::class,
            RandomCustomerSeeder::class,   
            TaxSeeder::class,
            ZoneSeeder::class,
        ]);
    }
}
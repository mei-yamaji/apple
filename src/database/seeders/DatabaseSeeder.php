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
        // CategoriesTableSeederを呼び出す
        $this->call([
            CategoriesTableSeeder::class,
        ]);
    }
}
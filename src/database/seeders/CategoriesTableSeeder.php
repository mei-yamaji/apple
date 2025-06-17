<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->insert([
            [
                'name' => '質問',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '趣味',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '技術',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
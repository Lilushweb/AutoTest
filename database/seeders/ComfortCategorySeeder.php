<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ComfortCategory;

class ComfortCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $comfortCategories = [
            ['name' => 'first'],
            ['name' => 'second'],
            ['name' => 'third'],
        ];
        foreach ($comfortCategories as $category) {
            ComfortCategory::create($category);
        }
    }
}

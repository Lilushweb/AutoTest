<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Position;
use App\Models\ComfortCategory;

class PositionComfortCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Получаем позиции
        $developer = Position::where('name', 'Developer')->first();
        $manager = Position::where('name', 'Manager')->first();

        // Получаем категории комфорта
        $first = ComfortCategory::where('name', 'first')->first();
        $second = ComfortCategory::where('name', 'second')->first();
        $third = ComfortCategory::where('name', 'third')->first();

        // Привязка через pivot
        $developer->comfortCategories()->sync([$second->id, $third->id]);
        $manager->comfortCategories()->sync([$first->id, $second->id]);
    }
}

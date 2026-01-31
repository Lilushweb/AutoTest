<?php

namespace Database\Seeders;

use App\Models\Position;
use Illuminate\Database\Seeder;

class PositionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $positions = [
            ['name' => 'Developer'],
            ['name' => 'Manager'],
            ['name' => 'Designer'],
            ['name' => 'QA Engineer'],
        ];

        foreach ($positions as $position) {
            Position::create($position);
        }
    }
}

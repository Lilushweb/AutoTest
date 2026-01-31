<?php

namespace Database\Factories\Models;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Positions>
 */
class PositionsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
        ];
    }
    public function basePosition()
    {
        return [
            [
                'id' => 1,
                'name' => 'Developer',
            ],
            [
                'id' => 2,
                'name' => 'Manager',
            ],
            [
                'id' => 3,
                'name' => 'Designer',
            ],
            [
                'id' => 4,
                'name' => 'QA Engineer',
            ],
        ];
    }
}

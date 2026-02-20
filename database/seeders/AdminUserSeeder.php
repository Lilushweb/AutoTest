<?php

namespace Database\Seeders;

use App\Models\Position;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Создаёт базового администратора для входа в Filament (/admin).
     * Логин: admin@example.com, пароль: password
     */
    public function run(): void
    {
        $position = Position::first();
        if (! $position) {
            $position = Position::create(['name' => 'Administrator']);
        }

        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'position_id' => $position->id,
                'role' => 'admin',
            ]
        );
    }
}

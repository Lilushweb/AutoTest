<?php

namespace Tests\Feature;

use App\Models\Car;
use App\Models\CarBooking;
use App\Models\ComfortCategory;
use App\Models\Position;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

abstract class ApiTestCase extends TestCase
{
    use RefreshDatabase;

    protected function createUser(array $overrides = []): User
    {
        $position = Position::first() ?? Position::create(['name' => 'Test Position']);
        return User::factory()->create(array_merge([
            'position_id' => $position->id,
            'role' => 'employee',
        ], $overrides));
    }

    protected function createManager(): User
    {
        return $this->createUser(['role' => 'manager']);
    }

    protected function createAdmin(): User
    {
        return $this->createUser(['role' => 'admin']);
    }

    protected function createPositionWithCategories(array $categoryNames = ['first']): Position
    {
        $position = Position::create(['name' => 'Test Position']);
        foreach ($categoryNames as $name) {
            $cat = ComfortCategory::create(['name' => $name]);
            $position->comfortCategories()->attach($cat->id);
        }
        return $position->fresh(['comfortCategories']);
    }

    protected function createComfortCategory(string $name = 'first'): ComfortCategory
    {
        return ComfortCategory::create(['name' => $name]);
    }

    protected function createCar(array $overrides = []): Car
    {
        $category = ComfortCategory::first() ?? ComfortCategory::create(['name' => 'first']);
        return Car::create(array_merge([
            'model' => 'Test Car',
            'comfort_category_id' => $category->id,
        ], $overrides));
    }

    protected function createCarBooking(User $user, Car $car, ?string $start = null, ?string $end = null): CarBooking
    {
        $start = $start ?? Carbon::now()->addDay()->startOfHour()->toDateTimeString();
        $end = $end ?? Carbon::now()->addDay()->addHours(2)->toDateTimeString();
        return CarBooking::create([
            'car_id' => $car->id,
            'user_id' => $user->id,
            'start_time' => $start,
            'end_time' => $end,
        ]);
    }

    protected function authJson(User $user): array
    {
        $token = $user->createToken('test')->plainTextToken;
        return [
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];
    }
}

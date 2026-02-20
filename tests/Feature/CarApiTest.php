<?php

namespace Tests\Feature;

use App\Models\Car;
use App\Models\ComfortCategory;
use App\Models\Position;
use Carbon\Carbon;

class CarApiTest extends ApiTestCase
{
    public function test_cars_index_requires_auth(): void
    {
        $response = $this->getJson('/api/cars');
        $response->assertStatus(401);
    }

    public function test_cars_index_returns_paginated_list(): void
    {
        $user = $this->createUser();
        $cat = $this->createComfortCategory();
        Car::create(['model' => 'BMW', 'comfort_category_id' => $cat->id]);

        $response = $this->getJson('/api/cars', $this->authJson($user));
        $response->assertOk()->assertJsonStructure(['data', 'meta']);
    }

    public function test_cars_store_requires_manager_or_admin(): void
    {
        $user = $this->createUser(['role' => 'employee']);
        $cat = $this->createComfortCategory();

        $response = $this->postJson('/api/cars', [
            'model' => 'Audi',
            'comfort_category_id' => $cat->id,
        ], $this->authJson($user));

        $response->assertStatus(403);
    }

    public function test_cars_store_creates_car_as_manager(): void
    {
        $user = $this->createManager();
        $cat = $this->createComfortCategory();

        $response = $this->postJson('/api/cars', [
            'model' => 'Audi A4',
            'comfort_category_id' => $cat->id,
        ], $this->authJson($user));

        $response->assertStatus(201)->assertJsonFragment(['model' => 'Audi A4']);
        $this->assertDatabaseHas('cars', ['model' => 'audi a4']);
    }

    public function test_cars_update_as_manager(): void
    {
        $user = $this->createManager();
        $car = $this->createCar(['model' => 'Old Model']);

        $response = $this->putJson('/api/cars/' . $car->id, [
            'model' => 'Updated Model',
        ], $this->authJson($user));

        $response->assertOk()->assertJsonFragment(['model' => 'Updated Model']);
    }

    public function test_cars_destroy_as_manager(): void
    {
        $user = $this->createManager();
        $car = $this->createCar();

        $response = $this->deleteJson('/api/cars/' . $car->id, [], $this->authJson($user));
        $response->assertStatus(200);
        $this->assertDatabaseMissing('cars', ['id' => $car->id]);
    }

    public function test_cars_available_requires_auth(): void
    {
        $response = $this->getJson('/api/cars/available?start_time=' . urlencode(Carbon::now()->addHour()->toDateTimeString()) . '&end_time=' . urlencode(Carbon::now()->addHours(2)->toDateTimeString()));
        $response->assertStatus(401);
    }

    public function test_cars_available_returns_list_for_period(): void
    {
        $position = $this->createPositionWithCategories(['first']);
        $user = $this->createUser(['position_id' => $position->id]);
        $cat = ComfortCategory::where('name', 'first')->first();
        Car::create(['model' => 'Free Car', 'comfort_category_id' => $cat->id]);

        $start = Carbon::now()->addDay()->startOfHour();
        $end = $start->copy()->addHours(2);

        $response = $this->getJson('/api/cars/available?' . http_build_query([
            'start_time' => $start->toDateTimeString(),
            'end_time' => $end->toDateTimeString(),
        ]), $this->authJson($user));

        $response->assertOk()->assertJsonStructure(['data']);
    }
}

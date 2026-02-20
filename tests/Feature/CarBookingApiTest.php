<?php

namespace Tests\Feature;

use App\Models\Car;
use App\Models\CarBooking;
use Carbon\Carbon;

class CarBookingApiTest extends ApiTestCase
{
    public function test_car_bookings_index_requires_auth(): void
    {
        $response = $this->getJson('/api/carBookings');
        $response->assertStatus(401);
    }

    public function test_car_bookings_index_returns_only_own(): void
    {
        $user = $this->createUser();
        $car = $this->createCar();
        $this->createCarBooking($user, $car);

        $response = $this->getJson('/api/carBookings', $this->authJson($user));
        $response->assertOk()->assertJsonStructure(['data']);
    }

    public function test_car_bookings_store_creates_booking(): void
    {
        $user = $this->createUser();
        $car = $this->createCar();
        $start = Carbon::now()->addDays(2)->startOfHour();
        $end = $start->copy()->addHours(2);

        $response = $this->postJson('/api/carBookings', [
            'car_id' => $car->id,
            'start_time' => $start->toDateTimeString(),
            'end_time' => $end->toDateTimeString(),
        ], $this->authJson($user));

        $response->assertStatus(201);
        $this->assertDatabaseHas('car_bookings', ['user_id' => $user->id, 'car_id' => $car->id]);
    }

    public function test_car_bookings_update_own_as_user(): void
    {
        $user = $this->createUser();
        $car = $this->createCar();
        $booking = $this->createCarBooking($user, $car);
        $newStart = Carbon::now()->addDays(3)->startOfHour();
        $newEnd = $newStart->copy()->addHours(3);

        $response = $this->putJson('/api/carBookings/' . $booking->id, [
            'start_time' => $newStart->toDateTimeString(),
            'end_time' => $newEnd->toDateTimeString(),
        ], $this->authJson($user));

        $response->assertOk();
    }

    public function test_car_bookings_destroy_own(): void
    {
        $user = $this->createUser();
        $car = $this->createCar();
        $booking = $this->createCarBooking($user, $car);

        $response = $this->deleteJson('/api/carBookings/' . $booking->id, [], $this->authJson($user));
        $response->assertStatus(200);
        $this->assertDatabaseMissing('car_bookings', ['id' => $booking->id]);
    }
}

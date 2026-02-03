<?php

namespace App\Http\Service;

use App\Http\DTO\CarBookingDTO;
use App\Http\DTO\UpdateCarBookingDTO;
use App\Models\Car;
use App\Models\CarBooking;
use Illuminate\Support\Facades\DB;

class CarBookingService
{
    public function createCarBooking(CarBookingDTO $dto): CarBooking
    {
        DB::beginTransaction();
        try {
            $car = Car::findOrFail($dto->carId);
            $hasOverlap = $car->carBookings()
                ->where('start_time', '<', $dto->endTime)
                ->where('end_time', '>', $dto->startTime)
                ->exists();
            if ($hasOverlap) {
                throw new \InvalidArgumentException('Car is already booked for this time.');
            }

            $carBooking = CarBooking::create([
                'car_id' => $dto->carId,
                'user_id' => $dto->userId,
                'start_time' => $dto->startTime,
                'end_time' => $dto->endTime,
            ]);

            $car->update(['user_id' => $dto->userId]); // текущий владелец

            DB::commit();
            return $carBooking->load(['car', 'user']);
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updateCarBooking(CarBooking $carBooking, UpdateCarBookingDTO $dto): CarBooking
    {
        DB::beginTransaction();
        try {
            $carBooking->update([
                'start_time' => $dto->startTime ?? $carBooking->start_time,
                'end_time' => $dto->endTime ?? $carBooking->end_time,
            ]);

            DB::commit();
            return $carBooking->fresh(['car', 'user']);
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function deleteCarBooking(CarBooking $carBooking): void
    {
        DB::beginTransaction();
        try {
            $car = $carBooking->car;
            $carBooking->delete();
            if ($car && $car->user_id === $carBooking->user_id) {
                $car->update(['user_id' => null]);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}

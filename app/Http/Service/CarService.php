<?php

namespace App\Http\Service;

use App\Http\DTO\AvailableCarsFilterDTO;
use App\Http\DTO\CarDTO;
use App\Http\DTO\UpdateCarDTO;
use App\Models\Car;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class CarService
{
    public function getCars(int $perPage, int $page): LengthAwarePaginator
    {
        return Car::with(['comfortCategory', 'user'])
            ->paginate($perPage, ['*'], 'page', $page);
    }

    public function createCar(CarDTO $dto): Car
    {
        $car = Car::create([
            'model' => strtolower($dto->model),
            'comfort_category_id' => $dto->comfortCategoryId,
        ]);

        return $car->load(['comfortCategory', 'user']);
    }

    public function updateCar(Car $car, UpdateCarDTO $dto): Car
    {
        $car->update([
            'model' => $dto->model !== null ? strtolower($dto->model) : $car->model,
            'comfort_category_id' => $dto->comfortCategoryId ?? $car->comfort_category_id,
        ]);

        return $car->fresh(['comfortCategory', 'user']);
    }

    public function deleteCar(Car $car): void
    {
        $car->delete();
    }

    public function getAvailableCars(AvailableCarsFilterDTO $filter): Collection
    {
        $query = Car::query()
            ->with(['comfortCategory', 'user'])
            ->whereDoesntHave('carBookings', function ($q) use ($filter) {
                $q->where('start_time', '<', $filter->endTime)
                    ->where('end_time', '>', $filter->startTime);
            })
            ->when(!empty($filter->comfortCategoryIds), function ($q) use ($filter) {
                $q->whereIn('comfort_category_id', $filter->comfortCategoryIds);
            })
            ->when(!empty($filter->search), function ($q) use ($filter) {
                $q->whereRaw('LOWER(model) LIKE ?', ['%' . strtolower($filter->search) . '%']);
            });

        return $query->get();
    }
}

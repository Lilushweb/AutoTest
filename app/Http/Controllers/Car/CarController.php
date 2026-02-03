<?php

namespace App\Http\Controllers\Car;

use App\Http\DTO\AvailableCarsFilterDTO;
use App\Http\DTO\CarDTO;
use App\Http\DTO\UpdateCarDTO;
use App\Http\Requests\Car\AvailableCarsRequest;
use App\Http\Requests\Car\CreateCarRequest;
use App\Http\Requests\Car\UpdateCarRequest;
use App\Http\Resources\CarResource;
use App\Http\Service\CarService;
use App\Models\Car;
use App\Http\Controllers\Controller;
use App\Http\Requests\PaginationRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class CarController extends Controller
{
    public function __construct(
        private CarService $carService,
    ) {}

    public function index(PaginationRequest $request)
    {
        $this->authorize('viewAny', Car::class);
        $paginator = $request->validated();

        $cars = $this->carService->getCars(
            $paginator['per_page'],
            $paginator['page']
        );

        return CarResource::collection($cars);
    }

    public function store(CreateCarRequest $request)
    {
        $this->authorize('create', Car::class);
        $data = $request->validated();
        $dto = new CarDTO(
            model: $data['model'],
            comfortCategoryId: $data['comfort_category_id'],
        );

        $car = $this->carService->createCar($dto);

        return (new CarResource($car))->response()->setStatusCode(201);
    }

    public function update(UpdateCarRequest $request, Car $car)
    {
        $this->authorize('update', $car);
        $data = $request->validated();
        $dto = new UpdateCarDTO(
            model: $data['model'] ?? null,
            comfortCategoryId: $data['comfort_category_id'] ?? null,
        );

        $car = $this->carService->updateCar($car, $dto);

        return new CarResource($car);
    }

    public function destroy(Car $car)
    {
        $this->authorize('delete', $car);
        $this->carService->deleteCar($car);

        return response()->json(null, 200);
    }

    public function availableCars(AvailableCarsRequest $request)
    {
        $this->authorize('viewAny', Car::class);
        $data = $request->validated();
        $user = Auth::user()->load('position.comfortCategories');

        $comfortCategoryIds = $data['comfort_category_id'] ?? null;
        if ($comfortCategoryIds === null && $user->position) {
            $comfortCategoryIds = $user->position->comfortCategories->pluck('id')->toArray();
        }

        $dto = new AvailableCarsFilterDTO(
            startTime: Carbon::parse($data['start_time']),
            endTime: Carbon::parse($data['end_time']),
            userId: $user->id,
            search: $data['search'] ?? null,
            comfortCategoryIds: $comfortCategoryIds,
        );

        $availableCars = $this->carService->getAvailableCars($dto);

        return CarResource::collection($availableCars);
    }
}

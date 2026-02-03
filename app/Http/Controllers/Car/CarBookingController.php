<?php

namespace App\Http\Controllers\Car;

use App\Http\DTO\CarBookingDTO;
use App\Http\DTO\UpdateCarBookingDTO;
use App\Http\Requests\Car\CreateCarBookingRequest;
use App\Http\Requests\Car\UpdateCarBookingRequest;
use App\Http\Requests\PaginationRequest;
use App\Http\Resources\CarBookingResource;
use App\Http\Service\CarBookingService;
use App\Models\CarBooking;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class CarBookingController extends Controller
{
    public function __construct(
        private CarBookingService $carBookingService,
    ) {}

    public function index(PaginationRequest $request)
    {
        $this->authorize('viewAny', CarBooking::class);
        $paginator = $request->validated();
        $bookings = CarBooking::with(['car.comfortCategory', 'user'])
            ->where('user_id', Auth::id())
            ->paginate(
                $paginator['per_page'],
                ['*'],
                'page',
                $paginator['page']
            );

        return CarBookingResource::collection($bookings);
    }

    public function store(CreateCarBookingRequest $request)
    {
        $this->authorize('create', CarBooking::class);
        $validator = $request->validated();
        $dto = new CarBookingDTO(
            carId: $validator['car_id'],
            userId: Auth::id(),
            startTime: Carbon::parse($validator['start_time']),
            endTime: Carbon::parse($validator['end_time']),
        );

        $carBooking = $this->carBookingService->createCarBooking($dto);

        return (new CarBookingResource($carBooking))->response()->setStatusCode(201);
    }

    public function update(UpdateCarBookingRequest $request, CarBooking $carBooking)
    {
        $this->authorize('update', $carBooking);
        $validator = $request->validated();
        $dto = new UpdateCarBookingDTO(
            startTime: isset($validator['start_time']) ? Carbon::parse($validator['start_time']) : null,
            endTime: isset($validator['end_time']) ? Carbon::parse($validator['end_time']) : null,
        );

        $carBooking = $this->carBookingService->updateCarBooking($carBooking, $dto);

        return new CarBookingResource($carBooking);
    }

    public function destroy(CarBooking $carBooking)
    {
        $this->authorize('delete', $carBooking);
        $this->carBookingService->deleteCarBooking($carBooking);

        return response()->json(null, 200);
    }
}

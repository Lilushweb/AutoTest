<?php

namespace App\Http\Controllers\Car;

use App\Models\Car;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Car\CreateCar;
use App\Http\Requests\Car\UpdateCar;
use App\Http\Requests\PaginationRequest;

class CarController extends Controller
{

    public function index(PaginationRequest $request)
    {
        $paginator = $request->validated();
        $response = Car::with(['comfortCategory', 'user'])->paginate(
            $paginator['per_page'],
            ['*'],
            'page',
            $paginator['page']
        );

        return response()->json($response);
    }


    public function create(CreateCar $request)
    {
        $data = $request->validated();

        $car = Car::create([
            'model' => $data['model'],
            'comfort_category_id' => $data['comfort_category_id'],
        ]);

        return response()->json($car, 201);
    }

    public function update(UpdateCar $request, Car $cars)
    {
        $data = $request->validated();

        $cars->update([
            'model' => $data['model'] ?? $cars->model,
            'comfort_category_id' => $data['comfort_category_id'] ?? [],
        ]);

        return response()->json($cars, 200);

    }

    public function destroy(Car $cars)
    {
        $cars->delete();
        return response()->json(null, 200);
    }

    public function availableCars(Request $request)
    {
        //TODO: метод с поиском доступных автомобилей получения списка доступных текущему пользователю на запланированное время автомобилей с возможностью фильтрации по модели автомобиля, по категории комфорта
    }
}

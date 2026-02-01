<?php

namespace App\Http\Controllers;

use App\Http\DTO\ComfortCategoryDTO;
use App\Http\Requests\ComfortCategory\ComfortCategoryCreateRequest;
use App\Http\Requests\ComfortCategory\ComfortCategoryUpdateRequest;
use App\Http\Requests\PaginationRequest;
use App\Models\ComfortCategory;
use Illuminate\Http\Request;
use App\Http\Service\ComfortCategoryService;

class ComfortCategoryController extends Controller
{
    public function __construct(
        private ComfortCategoryService $comfortCategoryService,
    ) {
    }
    public function index(PaginationRequest $request)
    {
        $paginator = $request->validated();
        $response = ComfortCategory::with('position')->paginate(
            $paginator['per_page'],
            ['*'],
            'page',
            $paginator['page']
        );

        return response()->json($response);
    }

    public function store(ComfortCategoryCreateRequest $request)
    {
        $validator = $request->validated();
        $dto = new ComfortCategoryDTO(
            name: $validator['name'],
            positionIds: $validator['position_ids'],
        );
        $createComfortCategory = $this->comfortCategoryService->createComfortCategory($dto);
        return response()->json($createComfortCategory, 201);
    }


    public function update(ComfortCategoryUpdateRequest $request, ComfortCategory $comfortCategory)
    {
        $validator = $request->validated();
        $dto = new ComfortCategoryDTO(
            name: $validator['name'] ?? $comfortCategory->name,
            positionIds: $validator['position_ids'] ?? $comfortCategory->position->pluck('id')->toArray(),
        );
        $this->comfortCategoryService->updateComfortCategory($comfortCategory, $dto);
        return response()->json($comfortCategory, 200);
    }

    public function destroy(ComfortCategory $comfortCategory)
    {
        $comfortCategory->delete();
        return response()->json(null, 200);
    }
}

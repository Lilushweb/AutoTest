<?php

namespace App\Http\Controllers;

use App\Http\DTO\ComfortCategoryDTO;
use App\Http\Requests\ComfortCategory\ComfortCategoryCreateRequest;
use App\Http\Requests\ComfortCategory\ComfortCategoryUpdateRequest;
use App\Http\Requests\PaginationRequest;
use App\Http\Resources\ComfortCategoryResource;
use App\Models\ComfortCategory;
use App\Http\Service\ComfortCategoryService;

class ComfortCategoryController extends Controller
{
    public function __construct(
        private ComfortCategoryService $comfortCategoryService,
    ) {
    }
    public function index(PaginationRequest $request)
    {
        $this->authorize('viewAny', ComfortCategory::class);
        $paginator = $request->validated();
        $comfortCategories = ComfortCategory::with('position')->paginate(
            $paginator['per_page'],
            ['*'],
            'page',
            $paginator['page']
        );

        return ComfortCategoryResource::collection($comfortCategories);
    }

    public function store(ComfortCategoryCreateRequest $request)
    {
        $this->authorize('create', ComfortCategory::class);
        $validator = $request->validated();
        $dto = new ComfortCategoryDTO(
            name: $validator['name'],
            positionIds: $validator['position_ids'],
        );
        $comfortCategory = $this->comfortCategoryService->createComfortCategory($dto);
        return (new ComfortCategoryResource($comfortCategory))->response()->setStatusCode(201);
    }


    public function update(ComfortCategoryUpdateRequest $request, ComfortCategory $comfortCategory)
    {
        $this->authorize('update', $comfortCategory);
        $validator = $request->validated();
        $dto = new ComfortCategoryDTO(
            name: $validator['name'] ?? $comfortCategory->name,
            positionIds: $validator['position_ids'] ?? $comfortCategory->position->pluck('id')->toArray(),
        );
        $comfortCategory = $this->comfortCategoryService->updateComfortCategory($comfortCategory, $dto);
        return new ComfortCategoryResource($comfortCategory);
    }

    public function destroy(ComfortCategory $comfortCategory)
    {
        $this->authorize('delete', $comfortCategory);
        $comfortCategory->delete();
        return response()->json(null, 200);
    }
}

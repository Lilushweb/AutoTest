<?php

namespace App\Http\Controllers;

use App\Http\DTO\PositionDTO;
use App\Http\Requests\PaginationRequest;
use App\Http\Requests\Position\PositionCreateRequest;
use App\Http\Requests\Position\PositionUpdateRequest;
use App\Http\Resources\PositionResource;
use App\Http\Service\PositionService;
use App\Models\Position;

class PositionsController extends Controller
{
    public function __construct(
        private PositionService $positionService,
    ) {
    }

    public function index(PaginationRequest $request)
    {
        $this->authorize('viewAny', Position::class);
        $paginator = $request->validated();
        $positions = Position::with('comfortCategories')
            ->paginate(
                $paginator['per_page'],
                ['*'],
                'page',
                $paginator['page']
            );

        return PositionResource::collection($positions);
    }

    public function store(PositionCreateRequest $request)
    {
        $this->authorize('create', Position::class);
        $dto = new PositionDTO(
            name: $request->validated('name'),
            comfortCategoryIds: $request->validated('comfort_category'),
        );
        $position = $this->positionService->createPosition($dto);
        return (new PositionResource($position))->response()->setStatusCode(201);
    }

    public function update(PositionUpdateRequest $request, Position $position)
    {
        $this->authorize('update', $position);
        $validated = $request->validated();
        $dto = new PositionDTO(
            name: $validated['name'] ?? $position->name,
            comfortCategoryIds: $validated['comfort_category'] ?? $position->comfortCategories->pluck('id')->toArray(),
        );
        $position = $this->positionService->updatePosition($position, $dto);
        return new PositionResource($position);
    }

    public function destroy(Position $position)
    {
        $this->authorize('delete', $position);
        $position->delete();
        return response()->json(null, 200);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\DTO\PositionDTO;
use App\Http\Requests\PaginationRequest;
use App\Http\Requests\Position\PositionCreateRequest;
use App\Http\Service\PositionService;
use App\Models\Position;
use Illuminate\Http\Request;
use App\Http\Requests\Position\PositionUpdateRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PositionsController extends Controller
{
    public function __construct(
        private PositionService $positionService,
    ) {
    }

    public function index(PaginationRequest $request)
    {
        $paginator = $request->validated();
        $response = Position::with('comfortCategories')
            ->paginate(
                $paginator['per_page'],
                ['*'],
                'page',
                $paginator['page']
            );

        return response()->json($response);
    }

    public function store(PositionCreateRequest $request)
    {
        $dto = new PositionDTO(
            name: $request->validated('name'),
            comfortCategoryIds: $request->validated('comfort_category'),
        );
        $createPosition = $this->positionService->createPosition($dto);

        return response()->json($createPosition, 201);
    }

    public function update(PositionUpdateRequest $request, Position $position)
    {
        try {
            $position = Position::findOrFail($position->id);
            $dto = new PositionDTO(
                name: $request->validated('name'),
                comfortCategoryIds: $request->validated('comfort_category'),
            );
            $update = $this->positionService->updatePosition($position, $dto);
            return response()->json($update, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Position not found'], 404);
        }
    }

    public function destroy(Position $position)
    {
        $position->delete();
        return response()->json(null, 200);
    }
}

<?php

namespace App\Http\Service;

use App\Http\DTO\PositionDTO;
use App\Models\Position;
use Illuminate\Support\Facades\DB;
class PositionService
{
    public function createPosition(PositionDTO $dto): Position|array
    {
        try {
            DB::beginTransaction();

            $position = Position::create([
                'name' => $dto->name,
            ]);
            $position->comfortCategories()->sync($dto->comfortCategoryIds);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return ['error' => $e];
        }
        return $position;
    }
    public function updatePosition(Position $position, PositionDTO $dto): Position|array
    {
        try {
            DB::beginTransaction();

            $position->update([
                'name' => $dto->name,
            ]);
            $position->comfortCategories()->sync($dto->comfortCategoryIds);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return ['error' => $e];
        }

        return $position;
    }
}

<?php

namespace App\Http\Service;

use App\Http\DTO\PositionDTO;
use App\Models\Position;
use Illuminate\Support\Facades\DB;
class PositionService
{
    public function createPosition(PositionDTO $dto): Position
    {
        DB::beginTransaction();
        try {
            $position = Position::create([
                'name' => mb_strtolower($dto->name),
            ]);
            $position->comfortCategories()->sync($dto->comfortCategoryIds);

            DB::commit();
            return $position->load(['comfortCategories']);
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updatePosition(Position $position, PositionDTO $dto): Position
    {
        DB::beginTransaction();
        try {
            $position->update([
                'name' => mb_strtolower($dto->name),
            ]);
            $position->comfortCategories()->sync($dto->comfortCategoryIds);

            DB::commit();
            return $position->fresh(['comfortCategories']);
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}

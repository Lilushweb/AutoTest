<?php

namespace App\Http\Service;

use App\Http\DTO\ComfortCategoryDTO;
use App\Models\ComfortCategory;
use Illuminate\Support\Facades\DB;

class ComfortCategoryService
{
    public function createComfortCategory(ComfortCategoryDTO $dto): ComfortCategory
    {
        DB::beginTransaction();
        try {
            $comfortCategory = ComfortCategory::create([
                'name' => mb_strtolower($dto->name),
            ]);
            $comfortCategory->position()->sync($dto->positionIds);

            DB::commit();
            return $comfortCategory->load(['position']);
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updateComfortCategory(ComfortCategory $comfortCategory, ComfortCategoryDTO $dto): ComfortCategory
    {
        DB::beginTransaction();
        try {
            $comfortCategory->update([
                'name' => mb_strtolower($dto->name),
            ]);
            $comfortCategory->position()->sync($dto->positionIds);

            DB::commit();
            return $comfortCategory->fresh(['position']);
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}

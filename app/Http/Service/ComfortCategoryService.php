<?php

namespace App\Http\Service;

use App\Http\DTO\ComfortCategoryDTO;
use App\Models\ComfortCategory;
use Illuminate\Support\Facades\DB;

class ComfortCategoryService
{
    public function createComfortCategory(ComfortCategoryDTO $dto)
    {
        try {
            DB::beginTransaction();

            $comfortCategory = ComfortCategory::create([
                'name' => $dto->name,
            ]);
            $comfortCategory->position()->sync($dto->positionIds);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return ['error' => $e];
        }
        return $comfortCategory;
    }
    public function updateComfortCategory(ComfortCategory $comfortCategory,ComfortCategoryDTO $dto)
    {
        try {
            DB::beginTransaction();
            $comfortCategory->update([
                'name' => $dto->name,
            ]);
            $comfortCategory->position()->sync($dto->positionIds);
            $comfortCategory->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return ['error' => $e];
        }
        return $comfortCategory;
    }
}

<?php

namespace App\Filament\Resources\ComfortCategoryResource\Pages;

use App\Filament\Resources\ComfortCategoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListComfortCategories extends ListRecords
{
    protected static string $resource = ComfortCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

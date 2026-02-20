<?php

namespace App\Filament\Resources\ComfortCategoryResource\Pages;

use App\Filament\Resources\ComfortCategoryResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditComfortCategory extends EditRecord
{
    protected static string $resource = ComfortCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

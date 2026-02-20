<?php

namespace App\Filament\Resources\CarBookingResource\Pages;

use App\Filament\Resources\CarBookingResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCarBookings extends ListRecords
{
    protected static string $resource = CarBookingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

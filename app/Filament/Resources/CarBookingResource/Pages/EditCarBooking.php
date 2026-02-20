<?php

namespace App\Filament\Resources\CarBookingResource\Pages;

use App\Filament\Resources\CarBookingResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCarBooking extends EditRecord
{
    protected static string $resource = CarBookingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

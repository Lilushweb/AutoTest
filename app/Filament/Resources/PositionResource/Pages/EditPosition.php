<?php

namespace App\Filament\Resources\PositionResource\Pages;

use App\Filament\Resources\PositionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPosition extends EditRecord
{
    protected static string $resource = PositionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    public function mount(int | string $record): void
    {
        parent::mount($record);
        $this->form->fill([
            'comfortCategories' => $this->record->comfortCategories->pluck('id')->toArray(),
        ]);
    }

    protected function afterSave(): void
    {
        $ids = $this->form->getState()['comfortCategories'] ?? [];
        $this->record->comfortCategories()->sync($ids);
    }
}

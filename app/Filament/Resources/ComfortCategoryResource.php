<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ComfortCategoryResource\Pages;
use App\Models\ComfortCategory;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;

class ComfortCategoryResource extends Resource
{
    protected static ?string $model = ComfortCategory::class;

    protected static ?string $navigationIcon = 'heroicon-o-star';
    protected static ?string $navigationLabel = 'Категории комфорта';
    protected static ?string $modelLabel = 'категория комфорта';
    protected static ?string $pluralModelLabel = 'категории комфорта';
    protected static ?string $navigationGroup = 'Справочники';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Название')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('ID')->sortable(),
                TextColumn::make('name')->label('Название')->searchable()->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListComfortCategories::route('/'),
            'create' => Pages\CreateComfortCategory::route('/create'),
            'edit' => Pages\EditComfortCategory::route('/{record}/edit'),
        ];
    }
}

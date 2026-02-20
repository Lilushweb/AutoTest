<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CarResource\Pages;
use App\Models\Car;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;

class CarResource extends Resource
{
    protected static ?string $model = Car::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';
    protected static ?string $navigationLabel = 'Автомобили';
    protected static ?string $modelLabel = 'автомобиль';
    protected static ?string $pluralModelLabel = 'автомобили';
    protected static ?string $navigationGroup = 'Данные';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('model')
                    ->label('Модель')
                    ->required()
                    ->maxLength(255),
                Select::make('comfort_category_id')
                    ->label('Категория комфорта')
                    ->relationship('comfortCategory', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
                Select::make('user_id')
                    ->label('Водитель / владелец')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('ID')->sortable(),
                TextColumn::make('model')->label('Модель')->searchable()->sortable(),
                TextColumn::make('comfortCategory.name')->label('Категория комфорта')->sortable(),
                TextColumn::make('user.name')->label('Водитель')->placeholder('—')->sortable(),
            ])
            ->filters([
                SelectFilter::make('comfort_category_id')
                    ->label('Категория комфорта')
                    ->relationship('comfortCategory', 'name'),
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
            'index' => Pages\ListCars::route('/'),
            'create' => Pages\CreateCar::route('/create'),
            'edit' => Pages\EditCar::route('/{record}/edit'),
        ];
    }
}

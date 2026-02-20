<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CarBookingResource\Pages;
use App\Models\CarBooking;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;

class CarBookingResource extends Resource
{
    protected static ?string $model = CarBooking::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $navigationLabel = 'Бронирования';
    protected static ?string $modelLabel = 'бронирование';
    protected static ?string $pluralModelLabel = 'бронирования';
    protected static ?string $navigationGroup = 'Данные';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('car_id')
                    ->label('Автомобиль')
                    ->relationship('car', 'model')
                    ->required()
                    ->searchable()
                    ->preload(),
                Select::make('user_id')
                    ->label('Пользователь')
                    ->relationship('user', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
                DateTimePicker::make('start_time')
                    ->label('Начало')
                    ->required()
                    ->seconds(false),
                DateTimePicker::make('end_time')
                    ->label('Окончание')
                    ->required()
                    ->seconds(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('ID')->sortable(),
                TextColumn::make('car.model')->label('Автомобиль')->searchable()->sortable(),
                TextColumn::make('user.name')->label('Пользователь')->searchable()->sortable(),
                TextColumn::make('start_time')->label('Начало')->dateTime()->sortable(),
                TextColumn::make('end_time')->label('Окончание')->dateTime()->sortable(),
            ])
            ->filters([
                SelectFilter::make('car_id')
                    ->label('Автомобиль')
                    ->relationship('car', 'model'),
                SelectFilter::make('user_id')
                    ->label('Пользователь')
                    ->relationship('user', 'name'),
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
            'index' => Pages\ListCarBookings::route('/'),
            'create' => Pages\CreateCarBooking::route('/create'),
            'edit' => Pages\EditCarBooking::route('/{record}/edit'),
        ];
    }
}

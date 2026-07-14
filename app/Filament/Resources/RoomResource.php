<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoomResource\Pages;
use App\Filament\Resources\RoomResource\RelationManagers\ImagesRelationManager;
use App\Filament\Resources\RoomResource\RelationManagers\SeasonalRatesRelationManager;
use App\Models\Room;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class RoomResource extends Resource
{
    protected static ?string $model = Room::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Dasar')->schema([
                    Forms\Components\Select::make('property_id')
                        ->relationship('property', 'name')
                        ->required()
                        ->searchable()
                        ->preload(),
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('slug')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(255),
                    Forms\Components\RichEditor::make('description')
                        ->columnSpanFull(),
                ])->columns(2),

                Forms\Components\Section::make('Harga & Kapasitas')->schema([
                    Forms\Components\TextInput::make('base_price')
                        ->numeric()
                        ->required()
                        ->prefix('THB'),
                    Forms\Components\TextInput::make('capacity_adults')
                        ->numeric()
                        ->required(),
                    Forms\Components\TextInput::make('capacity_children')
                        ->numeric()
                        ->default(0),
                    Forms\Components\TextInput::make('room_size')
                        ->numeric()
                        ->suffix('m²'),
                ])->columns(2),

                Forms\Components\Section::make('Pengaturan Tambahan')->schema([
                    Forms\Components\Select::make('amenities')
                        ->relationship('amenities', 'name')
                        ->multiple()
                        ->preload()
                        ->columnSpanFull(),
                    Forms\Components\Toggle::make('is_active')
                        ->default(true),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('property.name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('base_price')
                    ->money('THB')
                    ->sortable(),
                Tables\Columns\TextColumn::make('capacity_adults')
                    ->label('Adults'),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            ImagesRelationManager::class,
            SeasonalRatesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRooms::route('/'),
            'create' => Pages\CreateRoom::route('/create'),
            'edit' => Pages\EditRoom::route('/{record}/edit'),
        ];
    }
}

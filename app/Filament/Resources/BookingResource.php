<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookingResource\Pages;
use App\Filament\Resources\BookingResource\RelationManagers\PaymentsRelationManager;
use App\Models\Booking;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Tamu')->schema([
                    Forms\Components\TextInput::make('guest_first_name')
                        ->required()           
                        ->maxLength(255),
                    Forms\Components\TextInput::make('guest_last_name')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('guest_email')
                        ->email()
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('guest_phone')
                        ->tel()
                        ->required()
                        ->maxLength(20),
                ])->columns(2),

                Forms\Components\Section::make('Detail Reservasi')->schema([
                    Forms\Components\TextInput::make('booking_code')
                        ->default(fn() => 'BK-' . strtoupper(uniqid()))
                        ->disabled()
                        ->dehydrated()
                        ->required()
                        ->maxLength(255),
                    Forms\Components\Select::make('room_id')
                        ->relationship('room', 'name')
                        ->required()
                        ->searchable()
                        ->preload(),
                    Forms\Components\DatePicker::make('check_in')
                        ->required(),
                    Forms\Components\DatePicker::make('check_out')
                        ->required()
                        ->after('check_in'),
                    Forms\Components\TextInput::make('adult_count')
                        ->numeric()
                        ->required()
                        ->default(1),
                    Forms\Components\TextInput::make('child_count')
                        ->numeric()
                        ->default(0),
                    Forms\Components\Select::make('discount_id')
                        ->relationship('discount', 'code')
                        ->searchable()
                        ->preload(),
                    Forms\Components\Select::make('status')
                        ->options([
                            'pending' => 'pending',
                            'paid' => 'paid',
                            'cancelled' => 'cancelled',
                            'completed' => 'completed',
                        ])
                        ->required()
                        ->default('pending'),
                    Forms\Components\TextInput::make('total_amount')
                        ->numeric()
                        ->required()
                        ->prefix('THB'),
                    Forms\Components\Textarea::make('special_requests')
                        ->columnSpanFull(),
                ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('booking_code')
                    ->searchable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('guest_first_name')
                    ->label('Tamu')
                    ->searchable()
                    ->formatStateUsing(fn($record) => $record->guest_first_name . ' ' . $record->guest_last_name),
                Tables\Columns\TextColumn::make('room.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('check_in')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('check_out')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_amount')
                    ->money('THB')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'warning',
                        'paid' => 'success',
                        'cancelled' => 'danger',
                        'completed' => 'info',
                    }),
            ])
            ->defaultSort('created_at', 'desc')
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
            PaymentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBookings::route('/'),
            'create' => Pages\CreateBooking::route('/create'),
            'edit' => Pages\EditBooking::route('/{record}/edit'),
        ];
    }
}

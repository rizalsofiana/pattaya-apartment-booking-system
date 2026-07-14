<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DiscountResource\Pages;
use App\Filament\Resources\DiscountResource\RelationManagers;
use App\Models\Discount;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DiscountResource extends Resource
{
    protected static ?string $model = Discount::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Detail Kupon')->schema([
                    Forms\Components\TextInput::make('code')
                        ->label('Kode Diskon')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(255)
                        ->extraInputAttributes(['style' => 'text-transform: uppercase']),
                    Forms\Components\Select::make('type')
                        ->label('Tipe Diskon')
                        ->options([
                            'percentage' => 'Persentase (%)',
                            'fixed' => 'Nominal Tetap (THB)',
                        ])
                        ->required(),
                    Forms\Components\TextInput::make('value')
                        ->label('Nilai Diskon')
                        ->numeric()
                        ->required(),
                    Forms\Components\TextInput::make('max_uses')
                        ->label('Maksimal Kuota (Opsional)')
                        ->numeric(),
                    Forms\Components\TextInput::make('used_count')
                        ->label('Telah Digunakan')
                        ->numeric()
                        ->default(0)
                        ->disabled() // Tidak bisa diedit manual
                        ->dehydrated(false), // Jangan kirim ke database saat update
                ])->columns(2),

                Forms\Components\Section::make('Masa Berlaku')->schema([
                    Forms\Components\DateTimePicker::make('valid_from')
                        ->label('Berlaku Dari'),
                    Forms\Components\DateTimePicker::make('valid_until')
                        ->label('Berlaku Sampai'),
                ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->searchable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'percentage' => 'info',
                        'fixed' => 'success',
                    }),
                Tables\Columns\TextColumn::make('value'),
                Tables\Columns\TextColumn::make('valid_until')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('used_count')
                    ->label('Terpakai'),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDiscounts::route('/'),
            'create' => Pages\CreateDiscount::route('/create'),
            'edit' => Pages\EditDiscount::route('/{record}/edit'),
        ];
    }
}

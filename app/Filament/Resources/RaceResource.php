<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RaceResource\Pages;
use App\Models\Race;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class RaceResource extends Resource
{
    protected static ?string $model = Race::class;

    protected static ?string $navigationIcon = 'heroicon-o-finger-print';
    protected static ?string $navigationGroup = 'Mascotas';
    protected static ?string $modelLabel = 'Razas';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nombre de la raza')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Select::make('species_id')
                    ->label('Especie')
                    ->relationship('specie', 'specie')
                    ->preload()  
                    ->searchable()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Raza'),

                Tables\Columns\TextColumn::make('specie.specie')
                    ->label('Especie'),
            ])
            ->filters([])
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRaces::route('/'),
            'create' => Pages\CreateRace::route('/create'),
            'edit' => Pages\EditRace::route('/{record}/edit'),
        ];
    }
}

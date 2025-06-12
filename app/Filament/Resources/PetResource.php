<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PetResource\Pages;
use App\Models\Pet;
use App\Models\Race;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PetResource extends Resource
{
    protected static ?string $model = Pet::class;

    protected static ?string $navigationIcon = 'heroicon-o-bug-ant';
    protected static ?string $navigationGroup = 'Mascotas';
    protected static ?string $modelLabel = 'Mascota';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nombre')
                    ->required()
                    ->maxLength(255),

                Forms\Components\DatePicker::make('birthdate')
                    ->label('Nacimiento')
                    ->required(),

                
                Forms\Components\Select::make('owner_id')
                    ->label('Dueño')
                    ->options(\App\Models\Owner::with('user')->get()->pluck('user.name', 'id'))
                    ->searchable()
                    ->preload()
                    ->required(),
                    
                Forms\Components\TextInput::make('gender')
                    ->label('Género')
                    ->required(),

                Forms\Components\TextInput::make('weight')
                    ->label('Peso')
                    ->required()
                    ->numeric(),

                Forms\Components\Textarea::make('allergies')
                    ->label('Alergias')
                    ->required()
                    ->columnSpanFull(),

                Forms\Components\Select::make('species_id')
                    ->label('Especie')
                    ->relationship('specie', 'specie')
                    ->searchable()
                    ->preload()
                    ->reactive()
                    ->required(),

                Forms\Components\Select::make('race_id')
                    ->label('Raza')
                    ->options(function (callable $get) {
                        $speciesId = $get('species_id');
                        if (!$speciesId) {
                            return [];
                        }
                        return Race::where('species_id', $speciesId)->pluck('name', 'id');
                    })
                    ->searchable()
                    ->required(),

                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable(),

                Tables\Columns\TextColumn::make('birthdate')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('gender'),

                Tables\Columns\TextColumn::make('weight')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('allergies') 

                    ->label('Alergias'),

                Tables\Columns\TextColumn::make('specie.specie')
                    ->label('Especie')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('race.name')
                    ->label('Raza')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('owner.user.name')
                    ->label('Dueño')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPets::route('/'),
            'create' => Pages\CreatePet::route('/create'),
            'edit' => Pages\EditPet::route('/{record}/edit'),
        ];
    }
}

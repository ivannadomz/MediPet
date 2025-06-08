<?php

namespace App\Filament\Resources\OwnerResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PetsRelationManager extends RelationManager
{
    protected static string $relationship = 'pets';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nombre de la mascota')
                    ->required(),
                Forms\Components\DatePicker::make('birthdate')
                    ->label('Fecha de nacimiento')
                    ->required(),
                Forms\Components\Select::make('owner_id')
                    ->label('Dueño')
                    ->options(\App\Models\Owner::with('user')->get()->pluck('user.name', 'id'))
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('gender')
                    ->label('Género')
                    ->options([
                        'H' => 'Hembra',
                        'M' => 'Macho',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('weight')
                    ->label('Peso (kg)')
                    ->numeric()
                    ->required(),
                Forms\Components\TextInput::make('allergies')
                    ->label('Alergias')
                    ->required(),
                Forms\Components\Select::make('specie_id')
                    ->label('Especie')
                    ->relationship('specie', 'specie')
                    ->searchable()
                    ->preload()
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('pet.name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre de la Mascota')
                    ->searchable(),
                Tables\Columns\TextColumn::make('birthdate')
                    ->label('Fecha de Nacimiento')
                    ->date()
                    ->dateTime(),
                Tables\Columns\TextColumn::make('gender')
                    ->label('Género'),
                Tables\Columns\TextColumn::make('weight')
                    ->label('Peso (kg)')
                    ->numeric(),
                Tables\Columns\TextColumn::make('allergies')
                    ->label('Alergias')
                    ->limit(50),
                Tables\Columns\TextColumn::make('specie.specie')
                    ->label('Especie'),
                Tables\Columns\TextColumn::make('owner.user.name')
                    ->label('Dueño'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}

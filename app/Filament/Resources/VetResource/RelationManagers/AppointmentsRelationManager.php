<?php

namespace App\Filament\Resources\VetResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Textarea; 

class AppointmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'appointments';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('pet_id')
                    ->label('Nombre de la Mascota')
                    ->relationship('pet', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\DateTimePicker::make('appointment_date')
                    ->label('Fecha de cita')
                    ->required(),
                Forms\Components\Select::make('branch_id')
                    ->label('Sucursal')
                    ->relationship('branch', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('status')
                    ->label('Estatus')
                    ->options([
                        'scheduled' => 'Programada',
                        'completed' => 'Completada',
                        'canceled' => 'Cancelada',
                    ])
                    ->required(),
                Textarea::make('reason')
                    ->label('Motivo')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('appointment_date')
            ->columns([
                Tables\Columns\TextColumn::make('pet.name')
                    ->label('Mascota')
                    ->searchable(),
                Tables\Columns\TextColumn::make('appointment_date')
                    ->label('Fecha Programada')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('branch.name')
                    ->label('Sucursal'),
                Tables\Columns\TextColumn::make('status')
                    ->label('Estatus')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'scheduled' => 'info',
                        'completed' => 'success',
                        'canceled' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('reason')
                    ->label('Motivo')
                    ->limit(50),
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

<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AppointmentResource\Pages;
use App\Filament\Resources\AppointmentResource\RelationManagers;
use App\Models\Appointment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AppointmentResource extends Resource
{
    protected static ?string $model = Appointment::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $navigationGroup = 'Historial';
    protected static ?string $modelLabel = 'Citas';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('vet_id')
                    ->label('Veterinario')
                    ->options(\App\Models\Vet::with('user')->get()->pluck('user.name', 'id'))
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('pet_id')
                    ->label('Mascota')
                    ->relationship('pet', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('branch_id')
                    ->label('Sucursal')
                    ->relationship('branch', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\DatePicker::make('appointment_date')
                    ->label('Fecha de cita')
                    ->required(),
                Forms\Components\Select::make('status')
                    ->label('Estatus')
                    ->options([
                        'scheduled' => 'Programada',
                        'completed' => 'Completada',
                        'canceled' => 'Cancelada',
                    ])
                    ->required(),
                Forms\Components\Textarea::make('reason')
                    ->label('Motivo')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('vet.user.name')
                    ->label('Veterinario')
                    ->searchable(),
                Tables\Columns\TextColumn::make('pet.name')
                    ->label('Mascota')
                    ->searchable(),
                Tables\Columns\TextColumn::make('branch.name')
                    ->label('Sucursal'),
                Tables\Columns\TextColumn::make('appointment_date')
                    ->label('Fecha de cita')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Estatus')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'scheduled' => 'info',
                        'completed' => 'success',
                        'canceled' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            RelationManagers\PrescriptionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAppointments::route('/'),
            'create' => Pages\CreateAppointment::route('/create'),
            'edit' => Pages\EditAppointment::route('/{record}/edit'),
        ];
    }
}

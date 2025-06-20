<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PrescriptionResource\Pages;
use App\Filament\Resources\PrescriptionResource\RelationManagers;
use App\Models\Prescription;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PrescriptionResource extends Resource
{
    protected static ?string $model = Prescription::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationGroup = 'Historial';
    protected static ?string $modelLabel = 'Prescripciones';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                
                Forms\Components\TextInput::make('reason')
                    ->label('Motivo')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Select::make('appointment_id')
                    ->label('Fecha de cita')
                    ->relationship('appointment', 'appointment_date')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\DatePicker::make('date')
                    ->label('Fecha de prescripción')
                    ->default(now()),
                Forms\Components\TextArea::make('specifications')
                    ->label('Especificaciones')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('diagnosis')
                    ->label('Diagnóstico')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('treatment')
                    ->label('Tratamiento')
                    ->required()
                    ->maxLength(255),
                Forms\Components\FileUpload::make('xray_file')
                    ->label('Radiografía')
                    ->directory('xrays')
                    ->required(false),
                Forms\Components\FileUpload::make('lab_file')
                    ->label('Archivo de laboratorio')
                    ->directory('labs')
                    ->required(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('reason')
                    ->label('Motivo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('appointment.appointment_date')
                    ->label('Fecha de cita')
                    ->date(),
                Tables\Columns\TextColumn::make('date')
                    ->label('Fecha de prescripción')
                    ->date()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('specifications')
                    ->label('Especificaciones'),
                Tables\Columns\TextColumn::make('diagnosis')
                    ->label('Diagnóstico'),
                Tables\Columns\TextColumn::make('treatment')
                    ->label('Tratamiento'),
                Tables\Columns\TextColumn::make('xray_file')
                    ->label('Radiografía')
                    ->searchable(),
                Tables\Columns\TextColumn::make('lab_file')
                    ->label('Archivo de laboratorio')
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
            'index' => Pages\ListPrescriptions::route('/'),
            'create' => Pages\CreatePrescription::route('/create'),
            'edit' => Pages\EditPrescription::route('/{record}/edit'),
        ];
    }
}

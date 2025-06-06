<?php

namespace App\Filament\Resources\SpecieResource\Pages;

use App\Filament\Resources\SpecieResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSpecies extends ListRecords
{
    protected static string $resource = SpecieResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

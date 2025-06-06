<?php

namespace App\Filament\Resources\VetResource\Pages;

use App\Filament\Resources\VetResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVets extends ListRecords
{
    protected static string $resource = VetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

<?php

namespace App\Filament\Resources\PlantillaCorreoResource\Pages;

use App\Filament\Resources\PlantillaCorreoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPlantillaCorreos extends ListRecords
{
    protected static string $resource = PlantillaCorreoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

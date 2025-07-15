<?php

namespace App\Filament\Admin\Resources\SeguimientoKpiDiarioResource\Pages;

use App\Filament\Admin\Resources\SeguimientoKpiDiarioResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSeguimientoKpiDiarios extends ListRecords
{
    protected static string $resource = SeguimientoKpiDiarioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}

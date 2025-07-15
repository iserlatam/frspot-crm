<?php

namespace App\Filament\Admin\Resources\SeguimientoKpiDiarioResource\Pages;

use App\Filament\Admin\Resources\SeguimientoKpiDiarioResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSeguimientoKpiDiario extends EditRecord
{
    protected static string $resource = SeguimientoKpiDiarioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

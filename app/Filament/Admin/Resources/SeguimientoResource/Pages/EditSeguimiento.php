<?php

namespace App\Filament\Admin\Resources\SeguimientoResource\Pages;

use App\Filament\Admin\Resources\SeguimientoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSeguimiento extends EditRecord
{
    protected static string $resource = SeguimientoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

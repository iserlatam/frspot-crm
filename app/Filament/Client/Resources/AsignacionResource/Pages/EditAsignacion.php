<?php

namespace App\Filament\Client\Resources\AsignacionResource\Pages;

use App\Filament\Client\Resources\AsignacionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAsignacion extends EditRecord
{
    protected static string $resource = AsignacionResource::class;

    protected static ?string $title = 'Editar asignacion';

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

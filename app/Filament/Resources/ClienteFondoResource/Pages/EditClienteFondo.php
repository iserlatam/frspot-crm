<?php

namespace App\Filament\Resources\ClienteFondoResource\Pages;

use App\Filament\Resources\ClienteFondoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditClienteFondo extends EditRecord
{
    protected static string $resource = ClienteFondoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

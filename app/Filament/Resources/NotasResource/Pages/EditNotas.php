<?php

namespace App\Filament\Resources\NotasResource\Pages;

use App\Filament\Resources\NotasResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditNotas extends EditRecord
{
    protected static string $resource = NotasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

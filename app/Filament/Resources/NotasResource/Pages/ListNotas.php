<?php

namespace App\Filament\Resources\NotasResource\Pages;

use App\Filament\Resources\NotasResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNotas extends ListRecords
{
    protected static string $resource = NotasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

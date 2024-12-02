<?php

namespace App\Filament\Admin\Resources\AsignacionResource\Pages;

use App\Filament\Admin\Resources\AsignacionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAsignacions extends ListRecords
{
    protected static string $resource = AsignacionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

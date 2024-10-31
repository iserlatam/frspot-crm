<?php

namespace App\Filament\Resources\ClienteResource\Pages;

use App\Filament\Resources\ClienteResource;
use App\Filament\Resources\ClienteResource\Widgets\ClienteCountStat;
use App\Filament\Resources\ClienteResource\Widgets\ClienteFondoTable;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListClientes extends ListRecords
{
    protected static string $resource = ClienteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getHeaderWidgets(): array
    {
        return [
            ClienteCountStat::class,
        ];
    }

    public function getFooterWidgets(): array
    {
        return [
            ClienteFondoTable::class,
        ];
    }
}

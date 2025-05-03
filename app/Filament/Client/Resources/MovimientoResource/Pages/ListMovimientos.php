<?php

namespace App\Filament\Client\Resources\MovimientoResource\Pages;

use App\Filament\Client\Resources\MovimientoResource;
use App\Filament\Client\Widgets\AccountClientInfo;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMovimientos extends ListRecords
{
    protected static string $resource = MovimientoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    function getHeaderWidgets(): array
    {
        return [
            AccountClientInfo::class,
        ];
    }
}

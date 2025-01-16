<?php

namespace App\Filament\Client\Resources\MovimientoResource\Pages;

use App\Filament\Client\Resources\MovimientoResource;
use App\Filament\Client\Resources\MovimientoResource\Widgets\InfoAccountClient;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMovimiento extends EditRecord
{
    protected static string $resource = MovimientoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            InfoAccountClient::class,
        ];
    }
}

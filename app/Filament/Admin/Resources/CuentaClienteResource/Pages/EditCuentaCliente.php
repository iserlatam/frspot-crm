<?php

namespace App\Filament\Admin\Resources\CuentaClienteResource\Pages;

use App\Filament\Admin\Resources\CuentaClienteResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCuentaCliente extends EditRecord
{
    protected static string $resource = CuentaClienteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

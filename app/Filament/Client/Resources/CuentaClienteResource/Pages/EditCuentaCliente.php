<?php

namespace App\Filament\Client\Resources\CuentaClienteResource\Pages;

use App\Filament\Client\Resources\CuentaClienteResource;
use App\Filament\Client\Resources\CuentaClienteResource\Widgets\AccountInfoWidget;
use App\Models\CuentaCliente;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Contracts\Support\Htmlable;

class EditCuentaCliente extends EditRecord
{

    protected static string $resource = CuentaClienteResource::class;

    protected static ?string $modelLabel = 'Cuenta';

    public function getTitle(): string | Htmlable
    {
        return 'Editando cuenta - ID: ' . $this->data['id'];
    }

    public function getHeaderWidgets(): array
    {
        return [
            AccountInfoWidget::class,
        ];
    }
}

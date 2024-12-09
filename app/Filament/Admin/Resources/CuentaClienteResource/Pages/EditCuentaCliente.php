<?php

namespace App\Filament\Admin\Resources\CuentaClienteResource\Pages;

use App\Filament\Admin\Resources\CuentaClienteResource;
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
}

<?php

namespace App\Filament\Admin\Resources\MovimientoResource\Widgets;

use App\Helpers\StatusConverter;
use App\Models\CuentaCliente;
use App\Models\Movimiento;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

class InfoAccountClient extends BaseWidget
{
    public ?Movimiento $record;

    protected function getStats(): array
    {
        return [
            // Saldo actual de la cuenta a la que se le solicita el movimiento
            Stat::make('Saldo disponible', Number::currency($this->record->cuentaCliente->monto_total))
                ->description('Estado de la cuenta: ' . StatusConverter::formatAccountStatus($this->record->cuentaCliente->estado_cuenta)),
        ];
    }
}

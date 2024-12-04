<?php

namespace App\Filament\Admin\Resources\MovimientoResource\Widgets;

use App\Models\CuentaCliente;
use App\Models\Movimiento;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class InfoAccountClient extends BaseWidget
{
    public ?Movimiento $record;

    protected function getStats(): array
    {
        return [
            Stat::make('Información de cuenta seleccionada', $this->record->cuentaCliente->user->name),
        ];
    }
}

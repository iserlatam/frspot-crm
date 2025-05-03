<?php

namespace App\Filament\Client\Widgets;

use App\Helpers\StatusConverter;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

class AccountClientInfo extends BaseWidget
{
    protected ?string $heading = 'Información de la cuenta';

    protected ?string $description = 'Se muestran los datos más relevantes para el usuario.';

    protected function getStats(): array
    {
        // $statusColor = StatusConverter::colorAccountStatus($this->client->monto_total ?? 'Desconocido');

        return [
            Stat::make('Saldo disponible', Number::currency(auth()->user()->cuentaCliente->monto_total ?? 0)),
        ];
    }
}

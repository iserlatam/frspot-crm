<?php

namespace App\Filament\Admin\Resources\CuentaClienteResource\Widgets;

use App\Helpers\StatusConverter;
use App\Models\CuentaCliente;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

class AccountInfoWidget extends BaseWidget
{
    public ?CuentaCliente $record;

    protected ?string $heading = 'Información de la cuenta';

    protected ?string $description = 'Se muestran los datos mas relevantes para el usuario.';

    protected function getStats(): array
    {
        $statusColor = StatusConverter::colorAccountStatus($this->record->estado_cuenta);

        return [
            // Saldo actual de la cuenta a la que se le solicita el movimiento
            Stat::make('Saldo disponible', Number::currency($this->record->monto_total)),

            Stat::make('Estado de la cuenta', StatusConverter::formatAccountStatus($this->record->estado_cuenta))
                ->color($statusColor)
                ->description('Última actualización: ' . $this->record->updated_at->format('M, d. Y H:i')),

            Stat::make('Divisa de la cuenta', $this->record->divisa),

            Stat::make('Total depositado', Number::currency($this->record->sum_dep))
                ->description('Número de depósitos: ' . $this->record->no_dep),


            Stat::make('Total retirado', Number::currency($this->record->sum_retiros))
                ->description('Número de retiros: ' . $this->record->no_retiros),

            # Stat for user owner
            Stat::make('Propietario de la cuenta', $this->record->user->name)
                ->description('Asesor asignado: ' . $this->record->user->asignacion->asesor->user->name),
        ];
    }
}

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

    protected ?string $description = 'Se muestran los datos más relevantes para el usuario.';

    protected function getStats(): array
    {
        $statusColor = StatusConverter::colorAccountStatus($this->record->estado_cuenta ?? 'Desconocido');

        return [
            Stat::make('Saldo disponible', Number::currency($this->record->monto_total ?? 0)),

            Stat::make('Estado de la cuenta', StatusConverter::formatAccountStatus($this->record->estado_cuenta ?? 'Desconocido'))
                ->color($statusColor)
                ->description('Última actualización: ' . ($this->record->updated_at ? $this->record->updated_at->format('M, d. Y H:i') : 'No disponible')),

            Stat::make('Divisa de la cuenta', $this->record->divisa ?? 'No especificado'),

            Stat::make('Total depositado', Number::currency($this->record->sum_dep ?? 0))
                ->description('Número de depósitos: ' . ($this->record->no_dep ?? 0)),

            Stat::make('Total retirado', Number::currency($this->record->sum_retiros ?? 0))
                ->description('Número de retiros: ' . ($this->record->no_retiros ?? 0)),

            Stat::make('Propietario de la cuenta', $this->record->user->name ?? 'No asignado')
                ->description('Asesor asignado: ' . ($this->record->user?->asignacion?->asesor?->user->name ?? 'No asignado')),
        ];
    }
}

<?php

namespace App\Filament\Resources\ClienteResource\Widgets;

use App\Models\Cliente;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ClienteCountStat extends BaseWidget
{
    protected function getStats(): array
    {
        $totalClientes = Cliente::count();

        return [
            Stat::make('# de clientes en total', $totalClientes)
                ->color('green'),
            Stat::make(
                '# Clientes hombres',
                Cliente::query()
                    ->where('genero', 'm')
                    ->count())
        ];
    }
}

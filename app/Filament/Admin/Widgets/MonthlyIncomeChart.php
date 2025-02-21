<?php

namespace App\Filament\Admin\Widgets;

use App\Helpers\Helpers;
use Filament\Widgets\ChartWidget;

class MonthlyIncomeChart extends ChartWidget
{
    protected static ?string $heading = 'Ingresos registrados mensuales';

    protected static ?string $description = 'Sumatoria total de todos los ingresos de depositos en la ultima semana.';

    public static function canView(): bool
    {
        return Helpers::isOwner();
    }

    protected function getData(): array
    {
        $incomes = \DB::table('cuenta_clientes')
            ->selectRaw('MONTH(created_at) as month, SUM(monto_total) as total')
            ->groupBy('month')
            ->get()
            ->pluck('total', 'month')
            ->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Ingresos Mensuales',
                    'data' => array_values($incomes),
                ],
            ],
            'labels' => array_keys($incomes),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}

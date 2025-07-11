<?php

namespace App\Filament\Admin\Widgets;

use App\Helpers\Helpers;
use App\Models\Asesor;
use App\Models\Seguimiento;
use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;

class DailySeguimientosKpiChartRetencion extends ChartWidget
{
    protected static ?string $heading = 'Seguimientos diarios por asesor Retencion';

    /* Refresca cada 60 000 ms (1 min) sin recargar la página */
    protected static ?string $pollingInterval = '60s';

    protected int|string|array $columnSpan = 'full'; // ocupa todo el ancho

    /* ---------- 1. Tipo de gráfica (Chart.js) ---------- */
    protected function getType(): string
    {
        // ‘bar’ + indexAxis='y'  →  barras horizontales
        return 'bar';
    }
    protected function getData(): array
    {
        $start = now()->startOfDay();
        $end = now()->endOfDay();
        
        // llamar asesores tipo asesor Retencion
        $asesores = Asesor::query()
            ->where('tipo_asesor','retencion')
            ->whereHas('user.roles', fn ($q) => $q->whereIn('name', ['asesor','team retencion']))
            ->with('user:id,name')
            ->get();

        // preparar lso datos para la grafica
        $labels =[];
        $data = [];
        $colors =[];    

        foreach($asesores as $asesor){
            // se realiza un contedo del total de total de clientes se realcia un conteo por cada cliente distinto contactado
            $totalClientes = Seguimiento::query()
                ->where('asesor_id',$asesor->id)
                ->whereBetween('created_at',[$start, $end])
                ->distinct('user_id')
                ->count('user_id');

            $labels[] = $asesor->user->name ?? "Asesor {$asesor->id}";
            $data[]   = $totalClientes;
            $colors[] = $totalClientes >= 30
                ? 'rgba(16,185,129,0.8)'   // verde Tailwind emerald-500
                : 'rgba(239,68,68,0.8)';   // rojo Tailwind red-500
        }

        // se devuelve el formato que espera recibir Chart.js

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'labels' => 'Clientes distintos contactado hoy',
                    'data'   => $data,
                    'backgroundColor' => $colors 
                ],
            ],
        ];
    }


    protected function getOptions(): array|RawJs|null
    {
        return[
            'chart' => [
                'type' => $this->getType(),
                'data' => $this->getdata(),

                // aqui se ubican las opciones nativas de charts.js para que funcione
                'options' => [
                    /* Barras horizontales */
                    'indexAxis' => 'y',

                    /* Espacio y aspecto */
                    'responsive'           => true,
                    'maintainAspectRatio'  => false,

                    /* Eje X con ticks 0-200 */
                    'scales' => [
                        'x' => [
                            'beginAtZero' => true,
                            'max'         => 200,
                            'ticks'       => [
                                'stepSize' => 20,        // marcas cada 20
                                'color'    => '#e5e7eb', // gris-200
                            ],
                            'grid'        => [
                                'color' => 'rgba(255,255,255,0.08)',
                            ],
                        ],
                        'y' => [
                            'ticks' => [
                                'color' => '#e5e7eb',
                            ],
                            'grid'  => [
                                'display' => false,
                            ],
                        ],
                    ],
                    /* Tooltip simplificado */
                    'plugins' => [
                        'tooltip' => [
                            'callbacks' => [
                                'label' => \Illuminate\Support\Js::from(
                                    "ctx.parsed.x + ' clientes'"
                                ),
                            ],
                        ],
                    ],
                ],
            ]
        ];
    }

    public static function canView(): bool
    {
        // (ajusta a tus roles reales)
        return Helpers::isSuperAdmin();
    }

}

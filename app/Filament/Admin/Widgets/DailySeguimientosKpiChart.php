<?php
/**
 *  APP/Filament/Widgets/DailySeguimientosKpiChart.php
 *
 *  Muestra, para **hoy**, cuántos clientes distintos ha contactado cada asesor
 *  ( tipo FTD / Retención / Recovery ).  Si un asesor hizo 3 comentarios al mismo
 *  cliente cuenta solo 1.  Barra **verde** si llegó a 130, roja si no.
 */

namespace App\Filament\Admin\Widgets;

use App\Helpers\Helpers;
use Filament\Widgets\ChartWidget;
use App\Models\Seguimiento;
use App\Models\Asesor;
use App\Models\SeguimientoKpiDiario;
use Illuminate\Support\Carbon;

class DailySeguimientosKpiChart extends ChartWidget
{
    /* Texto que aparece encima del gráfico */
    protected static ?string $heading = 'Seguimientos diarios por asesor FTD';

    /* Refresca cada 60 000 ms (1 min) sin recargar la página */
    protected static ?string $pollingInterval = '60s';

    protected int|string|array $columnSpan = 'full'; // ocupa todo el ancho

    /* ---------- 1. Tipo de gráfica (Chart.js) ---------- */
    protected function getType(): string
    {
        // ‘bar’ + indexAxis='y'  →  barras horizontales
        return 'bar';
    }

    /* ---------- 2. Datos que alimentan Chart.js ---------- */
    protected function getData(): array
    {
        /* 2-a) Rango de hoy según la TZ de la app */
        $start = now()->startOfDay();   // 00:00
        $end   = now()->endOfDay();     // 23:59:59
        $metaAsesor = 130;
        $metaTeam   = 10;

        /* 2-b) Traemos TODOS los asesores FTD, Retención, Recovery
                (aunque no tengan actividad, saldrán con valor 0) */
        $asesores = Asesor::query()
            ->whereIn('tipo_asesor', ['ftd'])
            ->whereHas('user.roles', fn ($q) => $q->whereIn('name', ['asesor','team ftd']))
            ->with('user:id,name')     // para obtener el nombre a mostrar
            ->get();

        /* 2-c) Preparamos arrays para Chart.js */
        $labels = [];   // nombres en el eje Y
        $data   = [];   // nº de clientes contactados
        $colors = [];   // verde o rojo según KPI

        foreach ($asesores as $asesor) {

            $rol = $asesor->user->roles->pluck('name')->first();

            $metaObjetivo = $rol === 'team ftd' ? $metaTeam : $metaAsesor;

            //obtener el tipo asesor 
            $tipo_asesor = $asesor->tipo_asesor;

            // realizamos un conteo de los seguimientos diarios realizados por el asesor
            $cantidad_seguimientos = Seguimiento::query()
                ->where('asesor_id',$asesor->id)
                ->whereBetween('created_at',[$start, $end])
                ->count('id');

            
            /* Contamos clientes distintos (user_id) tocados HOY */
            $totalClientes = Seguimiento::query()
                ->where('asesor_id', $asesor->id)
                ->whereBetween('created_at', [$start, $end])
                ->distinct('user_id')
                ->count('user_id');
            
            //inicializamos variable y validamos que el total de clientes contactados sea mayor o igual a la meta diaria para FTD
            $cumplio_meta = $totalClientes >= $metaObjetivo ? true : false ;

            //inicializamos una variable para contar cuantos clientes faltaron para cumplir la meta diaria del asesor ftd
            $faltantes = max(0,$metaObjetivo - $totalClientes);

            // aqui iniciamos logica para cargar la informacion de lso kpi diarios al nuevo modelo de SeguimientoKpiDiario para mantener un historial de las metricas por asesor
            SeguimientoKpiDiario::updateOrCreate(
                [
                    'asesor_id' => $asesor->id ?? null,
                    'fecha_kpi' => $start->toDateString(),
                ],
                [
                    'rol_asesor' => $asesor->user->roles->pluck('name')->first(), //guardamos el id del asesor que realizo los seguimientos
                    'tipo_asesor' =>$tipo_asesor,                                 //guardamos el tipo del asesor relacionado Debe ser FTD
                    'nombre_asesor' => $asesor->user->name ?? null,               //guardamos el nombre del asesor relacionado al usuariuo
                    'cantidad_clientes' =>$totalClientes,                         //guardamos el total de los clientes distinstos contactados ese dia
                    'cantidad_total' => $cantidad_seguimientos,                   //guardamos la cantidad todal de seguimientos realizados ese dia por el asesor
                    'cumplio_meta' => $cumplio_meta,                              //verificamos si cumplio o no cumplio la meta minima diaria
                    'faltantes' => $faltantes,                                    //siel asesor no completo la meta mostramos un conteo de clientes faltantes para la meta
                ]
            );

            /* Rellenamos los arrays */
            $labels[] = $asesor->user->name ?? "Asesor {$asesor->id}";
            $data[]   = $totalClientes;
            $colors[] = $totalClientes >= $metaObjetivo
                ? 'rgba(16,185,129,0.8)'   // verde Tailwind emerald-500
                : 'rgba(239,68,68,0.8)';   // rojo Tailwind red-500
        }

        /* 2-d) Devolvemos el formato exacto que espera Chart.js */
        return [
            'labels'   => $labels,
            'datasets' => [
                [
                    'label'           => 'Clientes distintos contactados hoy',
                    'data'            => $data,
                    'backgroundColor' => $colors,
                ],
            ],
        ];
    }

    /* ---------- 3. Opciones extra de Chart.js ---------- */
    protected function getOptions(): array
    {
        return [
            'chart' => [
                'type' => $this->getType(),
                'data' => $this->getData(),

                /* Aquí van opciones nativas de Chart.js */
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
            ],
        ];
    }

    /* ---------- 4. Quién puede ver el widget ---------- */
    public static function canView(): bool
    {
        // (ajusta a tus roles reales)
        return Helpers::isSuperAdmin() || Helpers::isCrmJunior();
    }
}
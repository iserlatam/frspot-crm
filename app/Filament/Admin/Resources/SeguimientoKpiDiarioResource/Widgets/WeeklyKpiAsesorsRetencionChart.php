<?php

namespace App\Filament\Admin\Resources\SeguimientoKpiDiarioResource\Widgets;

use App\Models\SeguimientoKpiDiario;
use Carbon\CarbonPeriod;
use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;

class WeeklyKpiAsesorsRetencionChart extends ChartWidget
{
    protected static ?string $heading = 'grafico semanal de KPi para asesores Retencion';
    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {   
        //inicializamos las variables  con los dias de la semana para el widget
        $start = now()->startOfWeek();              //fecha lunes 00:00:00
        $end = $start->copy()->addDays(5);  //Fecha sabado 23:59:59
        
        //inicializamos las etiquetas fijas el eje X (dias de la semana 6 )
        $labels = collect(CarbonPeriod::create($start, $end))
        ->map(fn ($dia) => $dia->isoFormat('ddd'))
        ->toArray();    //arrar de ñps dias abreviados [Lun, Mar, Mie, Jue, Vie, Sab]
        //creamos una paleta de colores para asignarle a cada asesor cuando se grafiqeun en la tabla
        $palette = [
            '#C47D09', '#EF467E', '#0A61F4', '#10BBBB',
            '#74A309', '#13B766', '#9C990E', '#B0D8D8', 
        ];     
        //realizamos la consula a la tabla que guara lso seguimientos diarios en el historial
        $filas = SeguimientoKpiDiario::query()
            ->selectRaw('asesor_id, nombre_asesor,
                        DATE(fecha_kpi) AS dia,
                        SUM(cantidad_clientes)  AS clientes,
                        SUM(cantidad_total)     AS seguimientos')
            ->where('tipo_asesor', 'retencion')
            ->whereBetween('fecha_kpi', [$start, $end])   // Carbon instances
            ->groupBy('asesor_id', 'nombre_asesor', 'dia')
            ->orderBy('dia')
            ->get();
        
        //inicializamos el dataset que se le pasara al widget
        $datasets = [];
        $index = 0; // Inicializamos el índice para la paleta de colores

        $agrupado = $filas->groupBy('asesor_id')->sortKeys(); // Agrupamos por asesor

        //recorremos las filas obtenidas de la consulta
        foreach ($agrupado as $asesorId => $datosAsesor) {
            $points = [];
            $seguimientos = []; // Array para almacenar seguimientos
            
            //recorremos los dias de la semana
            foreach($labels as $dia => $labelDia){
                $date = $start->copy()->addDays($dia)->toDateString(); //obtenemos la fecha del dia
                $match = $datosAsesor->firstWhere('dia', $date); //buscamos si existe un registro para ese dia
                //si existe un registro para ese dia, agregamos la cantidad de clientes, sino agregamos 0 o N/A
                $points[] = $match ? $match->clientes : 0; //cantidad de clientes por dia
                $seguimientos[] = $match ? $match->seguimientos : 0; // cantidad de seguimientos por dia
            };
            
            //agregamos al informaicon obtenia al array de data set para pasar al chart
            $datasets[] =[
                'label' => $datosAsesor->first()->nombre_asesor, //nombre del asesor
                'data' => $points,                         //datos de los puntos
                'seguimientos' => $seguimientos,          // datos de seguimientos
                'borderColor' => $palette[$index], //color del borde
                'backgroundColor' => 'transparent', //color de fondo transparente
                'pointRadius' => 5, //radio de los puntos
                'tension' => 0.2, //tension de la linea
            ];
            $index++; // Incrementamos el índice para el siguiente asesor
        };

        return [
            'labels' => $labels, //etiquetas de los dias de la semana
            'datasets' => $datasets, //datos de los asesores
        ];
    }


    protected function getOptions(): array|RawJs|null
    {
        return RawJs::make(<<<'JS'
            {
                interaction: {
                    mode: 'index',
                    intersect: true
                },
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const datasetLabel = context.dataset.label || '';
                                const value = context.parsed.y;
                                const seguimientos = context.dataset.seguimientos ? context.dataset.seguimientos[context.dataIndex] : 0;
                                
                                return datasetLabel + ': ' + value + ' clientes / ' + seguimientos + ' seguimientos';
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        type: 'category',
                        title: {
                            display: true,
                            text: 'Días de la semana'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Total Clientes'
                        },
                        beginAtZero: true
                    }
                }
            }
        JS);
    }
}
<?php

namespace App\Filament\Admin\Resources\SeguimientoKpiDiarioResource\Widgets;

use App\Helpers\Helpers;
use App\Models\SeguimientoKpiDiario;
use Carbon\Carbon;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\isNull;

class ResumenKpiMensual extends BaseWidget
{

     /**
     * Devuelve el título dinámico del widget.
     * Este método ahora SÍ se ejecutará porque hemos eliminado la propiedad estática.
     */

    public static ?string $heading = 'Resumen Mensual de KPIs por Asesor';

    protected int|string|array $columnSpan = 'full';

    public static function canView(): bool
    {
        return Helpers::isSuperAdmin(); // Permite que el widget sea visible
    }

    public function table(Table $table): Table
    {
        // $inicioMes = now()->startOfMonth();
        // $finMes = now()->endOfMonth();

        //=================logica para obtener los meses disponibles =======
        $avaliableMonths = SeguimientoKpiDiario::query()
            ->select(DB::raw('DATE_FORMAT(fecha_kpi, "%Y-%m") as month_year'))  // obtenemos el mes y año de la fecha_kpi 2025-07
            ->distinct() // obtenemos los meses y años distintos
            ->orderBy('month_year', 'desc') // ordenamos de forma descendente para que el mes actual sea el primero
            ->get()
            ->mapWithKeys(function ($item){
                return [$item->month_year => Carbon::parse($item->month_year)->format('F  Y')]; // fomateamos als fechas a un array asociativo
            })
            ->toArray();

        return $table
            // ==============================================================================
            // Consulta a la base de datos
            //  obtener el resumen mensual de KPIs por asesor  del model SeguimientoKpiDiario
            // ==============================================================================

            ->query(
                SeguimientoKpiDiario::query()
                    // realizamos una consulta en las columnas del modelo con DB::raw para hacer lsoc alculos direcamente en la base sde datos
                    ->select(
                            'asesor_id as id', // <--- Esto es clave para la tabla
                        'nombre_asesor',
                        'rol_asesor',
                        'tipo_asesor',
                        //  se agregan funcionaes SQL para calcular los totales y promedios
                        DB::raw('SUM(cantidad_clientes) as total_clientes_mes'),                        //obtenemos la suma del total de clientes del asesor por mes en cantidad_clientes
                        DB::raw('SUM(cantidad_total) as total_seguimientos_mes'),                       //obtenemos la suma del total de seguimientos realizados por el  asesor en el mes
                        DB::raw('COUNT(id) as dias_trabajados'),                                        //contamos cada ID del seguimientoKpiDiario para saber cuántos días trabajó el asesor 
                        DB::raw('SUM(faltantes) as total_faltantes_mes'),                               //obtenemos la suma de los clientes faltantes del asesor en el mes 
                        DB::raw('SUM(CASE WHEN cumplio_meta THEN 1 ELSE 0 END) as dias_cumplio_meta'),
                         // <<< CAMBIO #1: CALCULAR EL PROMEDIO DIRECTAMENTE EN SQL
                        DB::raw('SUM(cantidad_clientes) / NULLIF(COUNT(id), 0) as promedio_calculado'),
                        // <<< CAMBIO #2: CALCULAR EL PORCENTAJE DIRECTAMENTE EN SQLe
                        DB::raw('(SUM(CASE WHEN cumplio_meta THEN 1 ELSE 0 END) * 100.0) / NULLIF(COUNT(id), 0) as porcentaje_calculado')  // calidamos la cantidad de dias en los que el asesor cumplio la meta
                    )
                    // ->whereBetween('fecha_kpi',[$inicioMes,$finMes])                           // obenemos los registros del mes actual con la variable inicioMes y finMes
                    ->groupBy('asesor_id', 'nombre_asesor', 'rol_asesor', 'tipo_asesor')               // agrupamos la infomriacion por cada asesor unico
            )
            ->columns([
                    Tables\Columns\TextColumn::make('nombre_asesor')
                        ->label('Asesor')
                        ->sortable(),
                    Tables\Columns\TextColumn::make('rol_asesor')
                        ->badge()
                        ->label('Rol')
                        ->toggleable()
                        ->toggledHiddenByDefault(true)
                        ->color(fn (string $state) => match($state){
                            'asesor' => 'success',
                            'team ftd' => 'warning',
                            'team retencion' => 'danger',
                        }),
                    Tables\Columns\TextColumn::make('tipo_asesor')
                        ->label('Tipo Asesor')
                        ->badge()
                        ->color(fn (string $state) => match ($state) {
                            'ftd' => 'primary',
                            'retencion' => 'success',
                        })
                        ->alignCenter(),

                    // Columna de DATO DIRECTO: Total de Clientes.
                    Tables\Columns\TextColumn::make('total_clientes_mes')
                        ->label('Total Clientes Mes')
                        ->sortable()
                        ->alignCenter()
                        ->numeric(),

                    // Columna de DATO DIRECTO: Total de Seguimientos.
                    Tables\Columns\TextColumn::make('total_seguimientos_mes')
                        ->label('Total Seguimientos Mes')
                        ->sortable()
                        ->toggleable()
                        ->tooltip('Seguimientos realizados en el mes')
                        ->alignCenter()
                        ->numeric(),

                    // Columna de DATO DIRECTO: Días Trabajados.
                    Tables\Columns\TextColumn::make('dias_trabajados')
                        ->label('Días Trabajados')
                        ->sortable()
                        ->toggleable()
                        ->toggledHiddenByDefault(true)
                        ->alignCenter()
                        ->numeric(),

                     // <<< CAMBIO #3: Columna de Porcentaje simplificada
                    Tables\Columns\TextColumn::make('porcentaje_calculado')
                        ->label('% Cumplimiento Meta')
                        ->sortable() // Ahora sí puede ser ordenable
                        ->formatStateUsing(fn ($state) => number_format($state, 1) . '%') // Solo añade el formato
                        ->alignCenter(),

                    // <<< CAMBIO #4: Columna de Promedio simplificada
                    Tables\Columns\TextColumn::make('promedio_calculado')
                        ->label('Promedio Diario Clientes')
                        ->sortable() // Ahora sí puede ser ordenable
                        ->formatStateUsing(fn ($state) => number_format($state, 1) . ' clientes*día') // Solo añade el formato
                        ->alignCenter(),

                        // Columna de DATO DIRECTO: Total Faltantes.
                    Tables\Columns\TextColumn::make('total_faltantes_mes')
                        ->label('Total Faltantes Mes')
                        ->sortable()
                        ->tooltip('Suma de los clientes faltantes en el mes')
                        ->alignCenter()
                        ->toggleable()
                        ->toggledHiddenByDefault(true)
                        ->numeric(),

                    // Columna de DATO DIRECTO: Días que cumplió la meta.
                    Tables\Columns\TextColumn::make('dias_cumplio_meta')
                        ->label('Días Cumplió Meta')
                        ->numeric()
                        ->tooltip('Metas Cumplidas / Dias Tabajados')
                        ->formatStateUsing(function ($state, $record){
                            return $state . '/' . $record->dias_trabajados;
                        })
                        ->alignCenter(),

                ])->paginated(false)
                ->filters([
                    Tables\Filters\SelectFilter::make('month_year')
                        ->label('Seleccionar mes')
                        ->options($avaliableMonths) // usamos la variable $avaliableMonths para obtener los meses disponibles
                        ->default(now()->format('Y-m')) // por defecto el mes actual
                        ->query(function($query, array $data){
                            if(blank($data['value'])){
                                return ;
                            }
                            $query->whereYear('fecha_kpi', Carbon::parse($data['value'])->year)
                                  ->whereMonth('fecha_kpi', Carbon::parse($data['value'])->month);

                        }),
                    Tables\Filters\SelectFilter::make('asesor_id')
                    ->label('Asesor')
                    ->options(
                        SeguimientoKpiDiario::query()
                            ->orderBy('nombre_asesor')
                            ->pluck('nombre_asesor', 'asesor_id')
                            ->toArray()
                    )
                    ->searchable()
                    ->query(function (Builder $query, array $data) {
                        if ($data['value']) {
                            $query->where('asesor_id', $data['value']);
                        }
                    }),
                ]);
    }
}

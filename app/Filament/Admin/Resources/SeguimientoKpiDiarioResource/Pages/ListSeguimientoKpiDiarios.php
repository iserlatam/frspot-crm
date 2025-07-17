<?php

namespace App\Filament\Admin\Resources\SeguimientoKpiDiarioResource\Pages;

use App\Filament\Admin\Resources\SeguimientoKpiDiarioResource;
use App\Filament\Admin\Resources\SeguimientoKpiDiarioResource\Widgets\ResumenKpiMensual;
use App\Filament\Admin\Resources\SeguimientoKpiDiarioResource\Widgets\WeeklyKpiAsesorsChart;
use App\Filament\Admin\Resources\SeguimientoKpiDiarioResource\Widgets\WeeklyKpiAsesorsRetencionChart;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSeguimientoKpiDiarios extends ListRecords
{
    protected static string $resource = SeguimientoKpiDiarioResource::class;

    // protected function getHeaderActions(): array
    // {
    //     return [
    //         // Actions\CreateAction::make(),
    //     ];
    // }

    protected function getHeaderWidgets(): array
    {
        return [
            WeeklyKpiAsesorsChart::class,
            WeeklyKpiAsesorsRetencionChart::class,
        ];
    }

    protected function getFooterWidgets(): array
    {
        return[
            ResumenKpiMensual::class,
        ];
    }
}

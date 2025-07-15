<?php

namespace App\Filament\Admin\Pages;

use App\Filament\Admin\Widgets\DailySeguimientosKpiChartRetencion;
use App\Filament\Admin\Widgets\SeguimientosTableRetencion;
use App\Helpers\Helpers;
use Filament\Pages\Page;
use Illuminate\Contracts\View\View;

class KpisRete extends Page
{
    protected static ?string $navigationIcon  = 'heroicon-o-chart-bar';
    protected static ?string $navigationLabel = 'Kpi Retencion';
    protected static ?string $navigationGroup = 'KPIs';
    protected static ?string $title = 'KPI Retencion';

    protected static string $view = 'filament.admin.pages.kpis--rete';

    public static function canAccess(): bool
    {
        return Helpers::isSuperAdmin();
    }

    public function getHeader(): ?View
    {
        return view('filament.admin.pages.kpisRete-header');
    }

    public function getHeaderWidgets(): array
    {
        return [
            DailySeguimientosKpiChartRetencion::class,
            SeguimientosTableRetencion::class,
        ];
    }


}

<?php

namespace App\Filament\Admin\Pages;

use App\Filament\Admin\Widgets\DailySeguimientosKpiChart;
use App\Filament\Admin\Widgets\SeguimientosTable;
use App\Helpers\Helpers;
use App\Models\Seguimiento;
use Filament\Pages\Page;
use Illuminate\Contracts\View\View;

class Kpis extends Page
{
    protected static ?string $navigationIcon  = 'heroicon-o-chart-bar-square';
    protected static ?string $navigationLabel = 'Kpis FTD';
    protected static ?string $navigationGroup = 'KPIs';
    protected static ?string $title = 'KPIs ftd';

    /* Vista Blade */
    protected static string $view = 'filament.admin.pages.kpis';

    public function getHeader(): ?View
    {
        return view('filament.admin.pages.kpis-header');
    }
    public static function canAccess(): bool
    {
        return Helpers::isSuperAdmin() || Helpers::isCrmJunior();
    }
    public function getHeaderWidgets(): array
    {
        return [
            DailySeguimientosKpiChart::class,
            SeguimientosTable::class,
        ];
    }
    

}
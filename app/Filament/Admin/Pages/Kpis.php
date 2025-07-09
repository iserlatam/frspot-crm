<?php

namespace App\Filament\Admin\Pages;

use App\Filament\Admin\Widgets\DailySeguimientosKpiChart;
use App\Filament\Admin\Widgets\SeguimientosTable;
use App\Helpers\Helpers;
use App\Models\Seguimiento;
use Filament\Pages\Page;

class Kpis extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar-square';

    protected static string $view = 'filament.admin.pages.kpis';

    // protected static ?string $navigationGroup = 'Dashboard';
    public static function canAccess(): bool
    {
        return Helpers::isSuperAdmin() || Helpers::isCrmManager();
    }
    public static function getNavigationLabel(): string
    {
        return __('Kpis');
    }
    
    public function getHeaderWidgets(): array
    {
        return [
            DailySeguimientosKpiChart::class,
            SeguimientosTable::class,
        ];
    }
    

}
<?php

namespace App\Filament\Admin\Pages;

use App\Filament\Admin\Widgets\AccountsTable;
use App\Filament\Admin\Widgets\MonthlyIncomeChart;
use App\Filament\Admin\Widgets\WeekRegisteredUsersChart;
use App\Helpers\Helpers;
use Filament\Actions\Action;
use Filament\Pages\Page;

class Dashboard extends \Filament\Pages\Dashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static ?string $activeNavigationIcon = 'heroicon-s-home';

    protected static string $view = 'filament.admin.pages.dashboard';

    public static function canAccess(): bool
    {
        return Helpers::isOwner();
    }

    public function getHeaderWidgets(): array
    {
        return [
            // GRAFICOS
            MonthlyIncomeChart::make(),
            WeekRegisteredUsersChart::make(),
            // ESTADISTICAS
            AccountsTable::make(),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('Gestionar movimientos')
                ->color('primary')
                ->visible(fn() => Helpers::isOwner())
                ->url(route('filament.admin.resources.movimientos.index')),
            Action::make('Gestionar clientes')
                ->color('secondary')
                ->visible(fn() => Helpers::isOwner())
                ->url(route('filament.admin.resources.users.index')),
            Action::make('Gestionar seguimientos')
                ->color('info')
                ->url(route('filament.admin.resources.seguimientos.index')),
        ];
    }

    // public function onboardingAction(): Action
    // {
    //     return Action::make('onboarding')
    //         ->modalHeading('Welcome')
    //         ->visible(fn(): bool => ! auth()->user()->isOnBoarded());
    // }
}

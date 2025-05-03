<?php

namespace App\Filament\Client\Pages;

use App\Filament\Client\Widgets\AccountClientInfo;
use Filament\Pages\Page;

class Dashboard extends Page
{
    protected static string $routePath = '/';

    protected static string $view = 'filament.client.pages.dashboard-page';

    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected function getHeaderWidgets(): array
    {
        return [
            AccountClientInfo::class,
        ];
    }
}

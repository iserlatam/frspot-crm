<?php

namespace App\Filament\Admin\Resources\MovimientoResource\Pages;

use App\Filament\Admin\Resources\MovimientoResource;
use App\Filament\Admin\Resources\MovimientoResource\Widgets\InfoAccountClient;
use App\Filament\Admin\Widgets\AccountsTable;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMovimiento extends CreateRecord
{
    protected static string $resource = MovimientoResource::class;

    protected function getFooterWidgets(): array
    {
        return [
            AccountsTable::class,
        ];
    }
}

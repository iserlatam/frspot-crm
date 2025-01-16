<?php

namespace App\Filament\Client\Resources\MovimientoResource\Pages;

use App\Filament\Client\Resources\MovimientoResource;
use App\Filament\Client\Resources\MovimientoResource\Widgets\InfoAccountClient;
use App\Filament\Client\Widgets\AccountsTable;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMovimiento extends CreateRecord
{
    protected static string $resource = MovimientoResource::class;
}

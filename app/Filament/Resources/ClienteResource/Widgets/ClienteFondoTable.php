<?php

namespace App\Filament\Resources\ClienteResource\Widgets;

use App\Models\ClienteFondo;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class ClienteFondoTable extends BaseWidget
{
    protected static ?string $model = ClienteFondo::class;

    public function table(Table $table): Table
    {
        return $table
            ->query(ClienteFondo::query())
            ->columns([
                Tables\Columns\TextColumn::make('monto_total')
                    ->numeric()
                    ->sortable(),
            ]);
    }
}

<?php

namespace App\Filament\Resources\ClienteResource\Pages;

use App\Filament\Resources\ClienteResource;
use App\Filament\Resources\ClienteResource\Widgets\ClienteCountStat;
use Filament\Actions;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewCliente extends ViewRecord
{
    protected static string $resource = ClienteResource::class;

    public string $description = 'La informacion sensible del cliente';

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Informacion personal')
                    ->columns(3)
                    ->collapsible()
                    ->description($this->description)
                    ->icon('heroicon-s-user')
                    ->schema([
                        Infolists\Components\TextEntry::make('identificacion'),
                        Infolists\Components\TextEntry::make('nombre'),
                        Infolists\Components\TextEntry::make('direccion')
                            ->label('Dirección'),
                    ])
            ]);
    }
}

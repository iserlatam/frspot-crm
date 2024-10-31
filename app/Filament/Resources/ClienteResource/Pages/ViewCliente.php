<?php

namespace App\Filament\Resources\ClienteResource\Pages;

use App\Filament\Resources\ClienteResource;
use Filament\Actions;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewCliente extends ViewRecord
{
    protected static string $resource = ClienteResource::class;

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\TextEntry::make('name'),
                Infolists\Components\TextEntry::make('email'),
                Infolists\Components\TextEntry::make('notes')
                    ->columnSpanFull(),
            ]);
    }
}

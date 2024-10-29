<?php

namespace App\Filament\Resources\AsesorResource\Pages;

use App\Filament\Resources\AsesorResource;
use App\Models\Asesor;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\Record;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListAsesors extends ListRecords
{
    protected static string $resource = AsesorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

 
    public function getTabs(): array
    {
        return [
            'todas' => Tab::make(),
            'activas' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('qr_estado', 'Activo'))
                ->badge(Asesor::query()->where('qr_estado','Activo')->count())
                ->icon('heroicon-m-user-group'),
                'inactivas' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('qr_estado', 'Inactivo'))
                ->badge(Asesor::query()->where('qr_estado','Inactivo')->count()),
        ];
    }
    }

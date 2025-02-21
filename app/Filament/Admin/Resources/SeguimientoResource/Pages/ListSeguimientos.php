<?php

namespace App\Filament\Admin\Resources\SeguimientoResource\Pages;

use App\Filament\Admin\Resources\SeguimientoResource;
use App\Models\Cliente;
use App\Models\Seguimiento;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSeguimientos extends ListRecords
{
    protected static string $resource = SeguimientoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->using(function(array $data, string $model){
                    $cliente = Cliente::where('user_id', $data['user_id'])->first();

                    $cliente->update([
                            'estado_cliente' => $data['estado'],
                            'origenes' => $data['origen'],
                            'fase_cliente' => $data['fase'],
                        ]);

                    // Agregar registro a HistorialSeguimiento


                    return $model::create([
                        'user_id' => $data['user_id'],
                        'asesor_id' => $data['asesor_id'],
                        'descripcion' => $data['descripcion'],
                    ]);
                }),
        ];
    }
}

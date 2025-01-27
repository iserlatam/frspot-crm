<?php

namespace App\Filament\Imports;

use App\Models\User;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class UserImporter extends Importer
{
    protected static ?string $model = User::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('name')
                ->requiredMapping(),
            ImportColumn::make('email')
                ->requiredMapping(),
            ImportColumn::make('celular')
                ->requiredMapping(false),
            ImportColumn::make('pais')
                ->requiredMapping(false),
            ImportColumn::make('origen')
                ->requiredMapping(false),
        ];
    }

    public function resolveRecord(): ?User
    {
        $user = User::create([
            'name' => substr($this->data['name'], 4) .  random_int(0000, 9999),
            'email' => $this->data['email'],
            'password' => Hash::make('Aa123456'),
        ])->assignRole('cliente');

        $user->cliente()->create([
            'nombre_completo' => $this->data['name'],
            'celular' => $this->data['celular'],
            'pais' => $this->data['pais'],
            'origenes' => $this->data['origen'],
        ]);

        // Abrir nueva cuenta
        $user->cuentaCliente()->create([
            'metodo_pago' => $this->data['metodo_pago'] ?? '',
            'monto_total' => $this->data['monto'] ?? 0,
        ]);

        // Asignar un asesor por defecto
        // $user->asesor()->create([
        //     'user_id' => 138,
        //     'tipo_asesor' => 'CRM',
        // ]);

        return $user;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'La importacion de los usuarios ha sido exitosa ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}

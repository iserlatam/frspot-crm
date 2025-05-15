<?php

namespace App\Filament\Imports;

use App\Helpers\NotificationHelpers;
use App\Models\User;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class UserImporter extends Importer
{
    protected static ?string $model = User::class;
    protected static array $duplicatedEmails = [];

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('name')->requiredMapping(),
            ImportColumn::make('email')->requiredMapping(),
            // Habilitar columnas adicionales según sea necesario
        ];
    }

    public function resolveRecord(): ?User
    {
        if (User::where('email', $this->data['email'])->exists()) {
            // Solo almacenar el correo duplicado
            self::$duplicatedEmails[] = $this->data['email'];
            return null;
        }

        return DB::transaction(function () {
            $user = User::create([
                'name' => substr($this->data['name'], 4) . random_int(0000, 9999),
                'email' => $this->data['email'],
                'password' => Hash::make('Aa123456'),
            ])->assignRole('cliente');

            $user->cliente()->create([
                'nombre_completo' => $this->data['name'] ?? '',
                'celular' => $this->data['celular'] ?? '',
                'estado_cliente' => $this->data['estado'] ?? '',
                'fase_cliente' => $this->data['fase'] ?? '',
                'origenes' => $this->data['origenes'] ?? '',
                'pais' => $this->data['pais'] ?? '',
                'infoeeuu' => $this->data['infoeeuu'] ?? '',
                'caso' => $this->data['caso'] ?? '',
            ]);

            $user->cuentaCliente()->create([
                'metodo_pago' => $this->data['metodo_pago'] ?? '',
                'monto_total' => $this->data['monto'] ?? 0,
            ]);

            return $user;
        });
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'La importación de los usuarios ha sido exitosa. '
            . number_format($import->successful_rows) . ' '
            . str('row')->plural($import->successful_rows) . ' importados.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' '
                . str('row')->plural($failedRowsCount) . ' fallaron al importar.';
        }

        // ✅ Verificamos si hay correos duplicados y notificamos una sola vez
        if (!empty(self::$duplicatedEmails)) {
            NotificationHelpers::sendNotificationlogic([
                'Error' => 'Se encontraron correos duplicados',
                'Total duplicados' => count(self::$duplicatedEmails),
                'Lista completa' => implode(', ', array_unique(self::$duplicatedEmails)),
            ], 'Error en importación');
        }

        return $body;
    }
}


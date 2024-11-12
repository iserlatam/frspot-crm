<?php

namespace App\Filament\Admin\Resources\UserResource\Pages;

use App\Filament\Admin\Resources\UserResource;
use App\Models\Cliente;
use App\Models\CuentaCliente;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    public $model = User::class;

    // protected static string $resource = UserResource::class;

    // protected function mutateFormDataBeforeCreate(array $data): array
    // {
    //     dd($this->data);
    // }

    protected function afterCreate(): void
    {

        $cuentaCliente = new CuentaCliente;

        dd($cuentaCliente->divisa);
        // dd(User::latest()
        //     ->first()
        //     ->cliente()
        //     ->get());

        // Runs after the form fields are saved to the database.
        $newCliente = User::latest()
            ->first()
            ->cliente()
            ->get();

        $cuentaCliente->billetera = $newCliente['billetera'];
        $cuentaCliente->divisa = $newCliente['divisa'];
        $cuentaCliente->cliente_id = $newCliente['id'];
        $cuentaCliente->save();

        // Create the client wallet account automatically
        // $newCuenta = CuentaCliente::create([
        //     'billetera' => $newCliente['billetera'],
        //     'divisa' => $newCliente['divisa'],
        //     'cliente_id' => $newCliente['id'],
        // ]);

    }
}

<?php

namespace App\Filament\Admin\Resources\UserResource\Pages;

use App\Filament\Admin\Resources\UserResource;
use App\Models\Cliente;
use App\Models\CuentaCliente;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    // protected function mutateFormDataBeforeCreate(array $data): array
    // {
    //     dd($this->data);
    // }

    // protected function afterCreate(): void
    // {

    //     // dd($cuentaCliente->divisa);
    //     // dd(User::latest()
    //     //     ->first()
    //     //     ->cliente()
    //     //     ->get());

    //     // dd($lastCliente['billetera']);

    //     // Create the client wallet account automatically
    //     // $newCuenta = CuentaCliente::create([
    //     //     'billetera' => $lastCliente['billetera'],
    //     //     'divisa' => $lastCliente['divisa'],
    //     //     'cliente_id' => $lastCliente['id'],
    //     // ]);

    // }
}

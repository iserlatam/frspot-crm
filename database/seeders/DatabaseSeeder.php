<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Cliente;
use App\Models\CuentaCliente;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(2)->create();
        // Cliente::factory(2)
        //     ->withUser()
        //     ->withCuentaCliente()
        //     ->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // $newCliente = Cliente::create($data);

        // $newCliente->syncRoles('cliente');

        // $newFondo = CuentaCliente::create([
        //     'billetera' => $data['billetera'],
        //     'divisa' => 'USDT',
        //     'cliente_id' => $newCliente['id'],
        // ]);
        $this->call(RolesTableSeeder::class);
        $this->call(PermissionsTableSeeder::class);
        $this->call(RoleHasPermissionsTableSeeder::class);
        $this->call(ModelHasRolesTableSeeder::class);
    }
}

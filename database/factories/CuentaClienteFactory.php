<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Cliente;
use App\Models\CuentaCliente;

class CuentaClienteFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CuentaCliente::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'divisa' => $this->faker->randomElement(['USDT', 'BITCOIN']),
            'suma_total_depositos' => 0,
            'suma_total_retiros' => 0,
            'cliente_id' => Cliente::factory(),
        ];
    }
}

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
            'billetera' => '4cc308a4165909452740c0cf23df1ae-e33c7c9535057ab4e03c88e189f980bf',
            'divisa' => $this->faker->randomElement(['USDT', 'BITCOIN']),
            'cliente_id' => Cliente::factory(),
        ];
    }
}

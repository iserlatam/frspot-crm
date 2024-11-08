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
            'billetera' => $this->faker->text(),
            'divisa' => $this->faker->regexify('[A-Za-z0-9]{15}'),
            'monto_total' => $this->faker->randomFloat(2, 0, 9999999999999.99),
            'ultimo_movimiento' => $this->faker->dateTime(),
            'suma_total_depositos' => $this->faker->randomFloat(2, 0, 9999999999999.99),
            'no_depositos' => $this->faker->word(),
            'suma_total_retiros' => $this->faker->randomFloat(2, 0, 9999999999999.99),
            'no_retiros' => $this->faker->word(),
            'cliente_id' => Cliente::factory(),
        ];
    }
}

<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\;
use App\Models\CuentaCliente;
use App\Models\User;

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
            'sistema_pago' => $this->faker->word(),
            'billetera' => $this->faker->text(),
            'divisa' => $this->faker->regexify('[A-Za-z0-9]{15}'),
            'estado_cuenta' => $this->faker->boolean(),
            'movimiento_id' => ::factory(),
            'monto_total' => $this->faker->randomFloat(3, 0, 999999999999.999),
            'sum_dep' => $this->faker->randomFloat(3, 0, 999999999999.999),
            'no_dep' => $this->faker->word(),
            'sum_retiros' => $this->faker->randomFloat(3, 0, 999999999999.999),
            'no_retiros' => $this->faker->word(),
            'user_id' => User::factory(),
        ];
    }
}

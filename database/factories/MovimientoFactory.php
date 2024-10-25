<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\IdPropietario;
use App\Models\Movimiento;

class MovimientoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Movimiento::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'id_propietario' => IdPropietario::factory(),
            'nombre' => $this->faker->regexify('[A-Za-z0-9]{50}'),
            'monto_ingreso' => $this->faker->randomFloat(2, 0, 9999999999.99),
            'monto_total' => $this->faker->randomFloat(2, 0, 9999999999.99),
            'fecha_actualizacion' => $this->faker->dateTime(),
            'total_depositos' => $this->faker->randomFloat(2, 0, 9999999999.99),
            'total_retiros' => $this->faker->randomFloat(2, 0, 9999999999.99),
        ];
    }
}

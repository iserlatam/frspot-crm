<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
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
            'no_radicado' => $this->faker->word(),
            'tipo_st' => $this->faker->randomElement(["d","r"]),
            'est_st' => $this->faker->randomElement(["a","b","c"]),
            'ingreso' => $this->faker->randomFloat(3, 0, 999999999.999),
            'comprobante_file' => $this->faker->text(),
            'razon_rechazo' => $this->faker->regexify('[A-Za-z0-9]{250}'),
            'cuenta_cliente_id' => $this->faker->word(),
        ];
    }
}

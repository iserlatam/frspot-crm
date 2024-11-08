<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\;
use App\Models\Cliente;
use App\Models\Seguimiento;

class SeguimientoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Seguimiento::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'descripción' => $this->faker->text(),
            'estado' => $this->faker->regexify('[A-Za-z0-9]{20}'),
            'fase' => $this->faker->regexify('[A-Za-z0-9]{50}'),
            'cliente_id' => Cliente::factory(),
            'asesor_id' => ::factory(),
        ];
    }
}

<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\;
use App\Models\Cliente;
use App\Models\Nota;

class NotaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Nota::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'nota' => $this->faker->text(),
            'fecha_creado' => $this->faker->dateTime(),
            'estado_nota' => $this->faker->regexify('[A-Za-z0-9]{20}'),
            'fase' => $this->faker->regexify('[A-Za-z0-9]{50}'),
            'cliente_id' => Cliente::factory(),
            'asesor_id' => ::factory(),
        ];
    }
}

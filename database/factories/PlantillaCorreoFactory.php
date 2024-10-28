<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\PlantillaCorreo;

class PlantillaCorreoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PlantillaCorreo::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'nombre_plantilla' => $this->faker->regexify('[A-Za-z0-9]{150}'),
            'cuerpo_plantilla' => $this->faker->text(),
            'asunto_plantilla' => $this->faker->regexify('[A-Za-z0-9]{150}'),
        ];
    }
}

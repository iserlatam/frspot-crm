<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\;
use App\Models\Asignacion;
use App\Models\Cliente;

class AsignacionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Asignacion::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'cliente_id' => Cliente::factory(),
            'asesor_id' => ::factory(),
        ];
    }
}

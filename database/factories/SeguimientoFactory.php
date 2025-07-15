<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Asesor;
use App\Models\Seguimiento;
use App\Models\User;

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
        $userId = User::inRandomOrder()->first()?->id;
        $asesorId = Asesor::inRandomOrder()->first()?->id;

        return [
            'descripcion' => $this->faker->text(),
            // 'estado' => $this->faker->regexify('[A-Za-z0-9]{20}'),
            // 'fase' => $this->faker->regexify('[A-Za-z0-9]{50}'),
            'user_id' => $userId,
            'asesor_id' => $asesorId,
        ];
    }
}

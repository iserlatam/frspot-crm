<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Asesor;
use App\Models\User;

class AsesorFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Asesor::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'tipo_asesor' => $this->faker->regexify('[A-Za-z0-9]{20}'),
            'user_id' => User::factory(),
        ];
    }
}

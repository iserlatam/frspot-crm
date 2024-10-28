<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Cliente;
use App\Models\ClienteFondo;

class ClienteFondoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ClienteFondo::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'monto_total' => $this->faker->randomFloat(2, 0, 9999999999.99),
            'total_depositos' => $this->faker->randomFloat(2, 0, 9999999999.99),
            'total_retiros' => $this->faker->randomFloat(2, 0, 9999999999.99),
            'cliente_id' => Cliente::factory(),
        ];
    }
}

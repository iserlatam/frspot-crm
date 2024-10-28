<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Cliente;
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
            'radicado' => $this->faker->regexify('[A-Za-z0-9]{50}'),
            'tipo_solicitud' => $this->faker->regexify('[A-Za-z0-9]{25}'),
            'monto_ingreso' => $this->faker->randomFloat(2, 0, 9999999999.99),
            'estado_solicitud' => $this->faker->regexify('[A-Za-z0-9]{20}'),
            'billetera' => $this->faker->regexify('[A-Za-z0-9]{250}'),
            'divisa' => $this->faker->regexify('[A-Za-z0-9]{10}'),
            'sistema_pago' => $this->faker->regexify('[A-Za-z0-9]{25}'),
            'comprobante_file' => $this->faker->regexify('[A-Za-z0-9]{250}'),
            'motivo_rechazo' => $this->faker->regexify('[A-Za-z0-9]{250}'),
            'cliente_id' => Cliente::factory(),
        ];
    }
}

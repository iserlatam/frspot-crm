<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Fondo;
use App\Models\IdPropietario;
use App\Models\IdSolicitudF;

class FondoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Fondo::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'id_solicitud_f' => IdSolicitudF::factory(),
            'id_propietario' => IdPropietario::factory(),
            'tipo_solicitud' => $this->faker->regexify('[A-Za-z0-9]{25}'),
            'estado' => $this->faker->regexify('[A-Za-z0-9]{20}'),
            'sistema_pago' => $this->faker->regexify('[A-Za-z0-9]{25}'),
            'fecha_creado' => $this->faker->dateTime(),
            'deposito' => $this->faker->randomFloat(2, 0, 99999999.99),
            'divisa' => $this->faker->regexify('[A-Za-z0-9]{10}'),
            'motivo_rechazo' => $this->faker->regexify('[A-Za-z0-9]{250}'),
            'comprobante' => $this->faker->regexify('[A-Za-z0-9]{250}'),
            'radicado' => $this->faker->regexify('[A-Za-z0-9]{50}'),
            'billetera' => $this->faker->regexify('[A-Za-z0-9]{250}'),
        ];
    }
}

<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Cliente;
use App\Models\User;

class ClienteFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Cliente::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'identificacion' => $this->faker->regexify('[A-Za-z0-9]{50}'),
            'nombre' => $this->faker->regexify('[A-Za-z0-9]{150}'),
            'direccion' => $this->faker->regexify('[A-Za-z0-9]{250}'),
            'genero' => $this->faker->regexify('[A-Za-z0-9]{1}'),
            'celular' => $this->faker->regexify('[A-Za-z0-9]{50}'),
            'fecha_nacido' => $this->faker->date(),
            'fecha_sistema' => $this->faker->dateTime(),
            'estado' => $this->faker->numberBetween(-10000, 10000),
            'promocion' => $this->faker->regexify('[A-Za-z0-9]{1}'),
            'codigo_unico' => $this->faker->regexify('[A-Za-z0-9]{20}'),
            'ciudad' => $this->faker->regexify('[A-Za-z0-9]{50}'),
            'infoeeuu' => $this->faker->regexify('[A-Za-z0-9]{2}'),
            'caso' => $this->faker->regexify('[A-Za-z0-9]{20}'),
            'tipo_doc_subm' => $this->faker->regexify('[A-Za-z0-9]{50}'),
            'activo_subm' => $this->faker->regexify('[A-Za-z0-9]{250}'),
            'metodoPago' => $this->faker->regexify('[A-Za-z0-9]{25}'),
            'cod_postal' => $this->faker->regexify('[A-Za-z0-9]{50}'),
            'pais' => $this->faker->regexify('[A-Za-z0-9]{50}'),
            'monto_pag' => $this->faker->randomFloat(2, 0, 99999999.99),
            'doc_soporte' => $this->faker->regexify('[A-Za-z0-9]{50}'),
            'archivo_soporte' => $this->faker->regexify('[A-Za-z0-9]{250}'),
            'comprobante_pag' => $this->faker->regexify('[A-Za-z0-9]{250}'),
            'estado_cliente' => $this->faker->regexify('[A-Za-z0-9]{50}'),
            'fase_cliente' => $this->faker->regexify('[A-Za-z0-9]{50}'),
            'origenes' => $this->faker->regexify('[A-Za-z0-9]{250}'),
            'billetera' => $this->faker->regexify('[A-Za-z0-9]{250}'),
            'user_id' => User::factory(),
        ];
    }
}

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
            'identificacion' => $this->faker->numberBetween(1000000, 9999999),
            'nombre' => $this->faker->name(),
            'direccion' => $this->faker->address(),
            'genero' => $this->faker->randomElement(['m', 'f']),
            'celular' => $this->faker->phoneNumber(),
            'fecha_nacido' => $this->faker->date(),
            'fecha_sistema' => $this->faker->dateTime(),
            'estado' => $this->faker->numberBetween(1, 2),
            // 'promocion' => $this->faker->regexify('[A-Za-z0-9]{1}'),
            // 'codigo_unico' => $this->faker->regexify('[A-Za-z0-9]{20}'),
            'ciudad' => $this->faker->city(),
            // 'infoeeuu' => $this->faker->regexify('[A-Za-z0-9]{2}'),
            // 'caso' => $this->faker->regexify('[A-Za-z0-9]{20}'),
            // 'tipo_doc_subm' => $this->faker->regexify('[A-Za-z0-9]{50}'),
            // 'activo_subm' => $this->faker->regexify('[A-Za-z0-9]{250}'),
            'metodoPago' => 'USDT',
            // 'cod_postal' => $this->faker->regexify('[A-Za-z0-9]{50}'),
            'pais' => $this->faker->country(),
            // 'monto_pag' => $this->faker->randomFloat(2, 0, 99999999.99),
            // 'doc_soporte' => $this->faker->regexify('[A-Za-z0-9]{50}'),
            // 'archivo_soporte' => $this->faker->regexify('[A-Za-z0-9]{250}'),
            // 'comprobante_pag' => $this->faker->regexify('[A-Za-z0-9]{250}'),
            'estado_cliente' => 'activo',
            'fase_cliente' => 'prospecto nuevo',
            'origenes' => 'USDT',
            'billetera' => $this->faker->text(80),
            'user_id' => User::factory(),
        ];
    }
}

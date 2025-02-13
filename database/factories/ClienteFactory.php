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
            'nombre_completo' => $this->faker->name(),
            'identificacion' => $this->faker->numberBetween(1000000000, 9999999999),
            'fecha_nacimiento' => $this->faker->date(),
            // 'genero' => $this->faker->regexify('[A-Za-z0-9]{15}'),
            'pais' => 'colombia',
            'ciudad' => $this->faker->city(),
            'direccion' => $this->faker->address(),
            'cod_postal' => $this->faker->postcode(),
            // 'celular' => $this->faker->regexify('[A-Za-z0-9]{25}'),
            'telefono' => $this->faker->phoneNumber(),
            // 'is_activo' => $this->faker->boolean(),
            // 'promocion' => $this->faker->regexify('[A-Za-z0-9]{50}'),
            // 'estado_cliente' => $this->faker->regexify('[A-Za-z0-9]{50}'),
            // 'fase_cliente' => $this->faker->regexify('[A-Za-z0-9]{50}'),
            // 'origenes' => $this->faker->regexify('[A-Za-z0-9]{60}'),
            // 'infoeeuu' => $this->faker->word(),
            // 'caso' => $this->faker->regexify('[A-Za-z0-9]{50}'),
            // 'tipo_doc_subm' => $this->faker->regexify('[A-Za-z0-9]{50}'),
            // 'activo_subm' => $this->faker->regexify('[A-Za-z0-9]{250}'),
            // 'metodo_pago' => $this->faker->regexify('[A-Za-z0-9]{25}'),
            // 'doc_soporte' => $this->faker->regexify('[A-Za-z0-9]{50}'),
            // 'archivo_soporte' => $this->faker->text(),
            // 'comprobante_pag' => $this->faker->text(),
            // 'user_id' => User::factory(),
        ];
    }
}

<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Cliente;
use App\Models\CuentaCliente;
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
            'identificacion' => $this->faker->regexify('[0-9]{10}'),
            'fecha_nacimiento' => $this->faker->date(),
            'genero' => $this->faker->randomElement(["f","m"]),
            'pais' => $this->faker->country(),
            'ciudad' => $this->faker->city(),
            'direccion' => $this->faker->address(),
            'cod_postal' => $this->faker->regexify('[0-9]{6}'),
            'celular' => $this->faker->phoneNumber(),
            'telefono' => $this->faker->phoneNumber(),
            'estado' => $this->faker->boolean(),
            'estado_cliente' => 'Nuevo',
            'fase_cliente' => 'Nuevo',
            'origenes' => 'META',
            'infoeeuu' => $this->faker->word(),
            'archivo_soporte' => $this->faker->text(),
            'comprobante_pag' => $this->faker->text(),
            'billetera' => $this->faker->regexify('[a-zA-Z0-9]{15}'),
            'user_id' => User::factory(),
        ];
    }

    public function withUser(): self
    {
        return $this->has(User::factory(), 'user');
    }

    public function withCuentaCliente(): self
    {
        return $this->has(CuentaCliente::factory(), 'cuentaCliente');
    }

}

<?php
namespace App\Helpers;

class OptionsHelper
{
    public static function estadoOptions():array
    {
        return self::getOptions('estado_cliente');
    }
    public static function faseOptions():array
    {
        return self::getOptions('fase_cliente');
    }
    public static function origenOptions():array
    {
        return self::getOptions('origenes');
    }

    public static function getOptions(string $campo):array
    {
        $path = base_path('\app\Helpers\opciones_cliente.json');
        if (!file_exists($path)) {
            return [];
        }
        $data =  json_decode(file_get_contents($path), true);

        // 👇 Verifica que esté cargando correctamente
        // if ($campo === 'origenes') {
        //     dd($data[$campo]);
        // }

        return collect($data[$campo] ?? [])
            ->mapWithKeys(fn($item) => [$item => $item])
            ->toArray();
    }

    public static function createOptions(string $campo, string $valor): void
    {
        $path = base_path('\app\Helpers\opciones_cliente.json');
        $data = file_exists($path)
        ? json_decode(file_get_contents($path), true)
        : [];

        if(!in_array($valor, $data[$campo] ?? [])){
            $data[$campo][] = $valor;
            file_put_contents($path, json_encode($data, JSON_PRETTY_PRINT));
        }
    }
}

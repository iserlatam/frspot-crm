<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeguimientoKpiDiario extends Model
{
    use HasFactory;

    protected $table = 'seguimientos_kpi_diarios';

    protected $fillable =[
        'asesor_id',            //asesor guardado del kpi
        'nombre_asesor',        //nombre del asesor relacionado
        'rol_asesor',           //rol del asesor relacionado(assor o team)
        'tipo_asesor',          //tipo de asesor  relacionado
        'fecha_kpi',            //fecha diaria del dia qeu se guardo el kpi
        'cantidad_clientes',    // cantidad de clientes distintos contactados por el asesor    
        'cantidad_total',       //cantidad total de seguimeintos realizados ese dia por el asesor
        'cumplio_meta',         // true o falce si el asesor cumplio o no cumplio con la meta prevista para su tipo asesor
        'faltantes',            // cantidad de clientes faltantes para cumplir la meta minima
    ];

    //cast para qeu laravel maneje correctamente los tipos
    protected $casts = [
        'fecha_kpi' => 'date',  //fecha de guardado del kpi
        'cumplio_meta' =>'boolean', // true o false y el sasor o team cumplio con la meta especializada
    ];

    // relacion para acceder facilmente al asesor
    public function asesor()
    {
        return $this->belongsTo(Asesor::class, 'asesor_id') ;
    }
}

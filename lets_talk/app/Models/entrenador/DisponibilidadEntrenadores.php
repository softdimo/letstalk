<?php

namespace App\Models\entrenador;

use Illuminate\Database\Eloquent\Model;

class DisponibilidadEntrenadores extends Model
{
    protected $connection = 'mysql';
    protected $table = 'disponibilidad_entrenadores';
    protected $primaryKey = 'id_horario';
    public $timestamps = true;
    protected $fillable = [
       'horario',
       'id_estado'
    ];
}

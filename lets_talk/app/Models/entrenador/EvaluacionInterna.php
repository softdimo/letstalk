<?php

namespace App\Models\entrenador;

use Illuminate\Database\Eloquent\Model;

class EvaluacionInterna extends Model
{
    protected $connection = 'mysql';
    protected $table = 'evaluacion_interna';
    protected $primaryKey = 'id_evaluacion_interna';
    public $timestamps = true;
    protected $fillable = [
       'evaluacion_interna',
       'id_estudiante',
       'id_instructor',
       'archivo_evaluacion',
       'id_trainer_horario'
    ];
}
